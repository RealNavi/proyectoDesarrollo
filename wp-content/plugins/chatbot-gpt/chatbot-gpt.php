<?php
/**
 * Plugin Name: Chatbot GPT
 * Description: Chatbot interactivo conectado a la API de OpenAI. Usa el shortcode [chatbot_gpt] en cualquier página.
 * Version:     1.0.0
 * Author:      Jhon Jairo Solano Carranza
 * Text Domain: chatbot-gpt
 */

defined('ABSPATH') || exit;

define('CB_VER', '1.0.0');
define('CB_URL', plugin_dir_url(__FILE__));

// Límite de caracteres por mensaje
define('CB_MAX_CHARS', 500);

/* ──────────────────────────────────────────────────────────────
   1. Registro de assets
────────────────────────────────────────────────────────────── */
add_action('wp_enqueue_scripts', 'cb_register_assets');
function cb_register_assets() {
    wp_register_style(
        'chatbot-gpt',
        CB_URL . 'assets/css/chatbot-gpt.css',
        [],
        CB_VER
    );

    wp_register_script(
        'chatbot-gpt',
        CB_URL . 'assets/js/chatbot-gpt.js',
        ['jquery'],
        CB_VER,
        true
    );

    wp_localize_script('chatbot-gpt', 'cbConfig', [
        'ajaxUrl'  => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('cb_nonce'),
        'maxChars' => CB_MAX_CHARS,
    ]);
}

/* ──────────────────────────────────────────────────────────────
   2. Marcado del chat (compartido por shortcode y widget flotante)
────────────────────────────────────────────────────────────── */
function cb_chat_widget_markup() {
    ob_start();
    ?>
    <div class="cb-wrapper">

        <div class="cb-header">
            <span class="cb-status-dot"></span>
            Asistente IA
            <button type="button" class="cb-float-close" id="cb-float-close" aria-label="Cerrar chat">&times;</button>
        </div>

        <div class="cb-messages" id="cb-messages">
            <div class="cb-bubble cb-bubble--bot">
                Hola, ¿en qué puedo ayudarte hoy?
            </div>
        </div>

        <div class="cb-input-area">
            <textarea
                id="cb-input"
                class="cb-input"
                placeholder="Escribe un mensaje..."
                rows="1"
                maxlength="<?php echo CB_MAX_CHARS; ?>"
            ></textarea>
            <button id="cb-send" class="cb-send-btn">Enviar</button>
        </div>
        <div class="cb-counter">
            <span id="cb-char-count">0</span>/<?php echo CB_MAX_CHARS; ?>
        </div>

    </div>
    <?php
    return ob_get_clean();
}

/* ──────────────────────────────────────────────────────────────
   3. Shortcode [chatbot_gpt]
────────────────────────────────────────────────────────────── */
add_shortcode('chatbot_gpt', 'cb_render_shortcode');
function cb_render_shortcode() {
    wp_enqueue_style('chatbot-gpt');
    wp_enqueue_script('chatbot-gpt');

    return cb_chat_widget_markup();
}

/* ──────────────────────────────────────────────────────────────
   4. Widget flotante en la página principal
────────────────────────────────────────────────────────────── */
add_action('wp_footer', 'cb_render_floating_widget');
function cb_render_floating_widget() {
    if (!is_front_page()) {
        return;
    }

    wp_enqueue_style('chatbot-gpt');
    wp_enqueue_script('chatbot-gpt');
    ?>
    <div id="cb-float-launcher" class="cb-float-launcher" role="button" tabindex="0" aria-label="Abrir chat">💬</div>
    <div id="cb-float-panel" class="cb-float-panel">
        <?php echo cb_chat_widget_markup(); ?>
    </div>
    <?php
}

/* ──────────────────────────────────────────────────────────────
   5. AJAX — enviar mensaje a OpenAI y devolver respuesta
────────────────────────────────────────────────────────────── */
add_action('wp_ajax_cb_send',        'cb_ajax_send');
add_action('wp_ajax_nopriv_cb_send', 'cb_ajax_send');
function cb_ajax_send() {
    check_ajax_referer('cb_nonce', 'nonce');

    // Verificar que la API key esté definida en wp-config.php
    if (!defined('OPENAI_API_KEY') || empty(OPENAI_API_KEY)) {
        wp_send_json_error(['msg' => 'API key no configurada.']);
    }

    // Validar y sanitizar el mensaje del usuario
    $user_message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
    if (empty($user_message)) {
        wp_send_json_error(['msg' => 'El mensaje no puede estar vacío.']);
    }
    if (mb_strlen($user_message) > CB_MAX_CHARS) {
        wp_send_json_error(['msg' => 'Mensaje demasiado largo.']);
    }

    // Recibir historial de conversación desde el frontend
    $raw_history = isset($_POST['history']) ? $_POST['history'] : '[]';
    $history     = json_decode(stripslashes($raw_history), true);
    if (!is_array($history)) {
        $history = [];
    }

    // Construir el array de mensajes para OpenAI
    $messages = [
        [
            'role'    => 'system',
            'content' => 'Eres un asistente útil y amigable. Responde de forma concisa. Responde en el idioma que use el usuario.',
        ]
    ];

    // Agregar historial (máximo los últimos 20 mensajes para no exceder tokens)
    $history = array_slice($history, -20);
    foreach ($history as $entry) {
        if (isset($entry['role'], $entry['content'])) {
            $messages[] = [
                'role'    => sanitize_text_field($entry['role']),
                'content' => sanitize_textarea_field($entry['content']),
            ];
        }
    }

    // Agregar el mensaje actual del usuario
    $messages[] = [
        'role'    => 'user',
        'content' => $user_message,
    ];

    // Llamar a la API de OpenAI con wp_remote_post()
    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'timeout' => 30,
        'headers' => [
            'Authorization' => 'Bearer ' . OPENAI_API_KEY,
            'Content-Type'  => 'application/json',
        ],
        'body' => wp_json_encode([
            'model'       => 'gpt-4o-mini',
            'messages'    => $messages,
            'max_tokens'  => 500,
            'temperature' => 0.7,
        ]),
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['msg' => 'No se pudo conectar con OpenAI.']);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (empty($body['choices'][0]['message']['content'])) {
        wp_send_json_error(['msg' => 'Respuesta inválida de OpenAI.']);
    }

    $reply = $body['choices'][0]['message']['content'];

    wp_send_json_success(['reply' => $reply]);
}
