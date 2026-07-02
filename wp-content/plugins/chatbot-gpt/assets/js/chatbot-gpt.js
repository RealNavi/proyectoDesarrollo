/* Chatbot GPT — lógica de frontend */
(function ($) {
    'use strict';

    var $messages  = $('#cb-messages');
    var $input     = $('#cb-input');
    var $sendBtn   = $('#cb-send');
    var $counter   = $('#cb-char-count');
    var maxChars   = cbConfig.maxChars;

    // Historial de conversación (se envía al backend con cada mensaje)
    var history = [];

    /* ── Contador de caracteres ── */
    $input.on('input', function () {
        var len = $(this).val().length;
        $counter.text(len);
        $counter.closest('.cb-counter').toggleClass('cb-counter--warn', len >= maxChars * 0.9);

        // Auto-crecer el textarea según contenido
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });

    /* ── Enviar con Enter (Shift+Enter = salto de línea) ── */
    $input.on('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    $sendBtn.on('click', function () {
        sendMessage();
    });

    /* ── Widget flotante: abrir/cerrar panel ── */
    $('#cb-float-launcher').on('click', function () {
        $('#cb-float-panel').toggleClass('cb-float-panel--open');
        if ($('#cb-float-panel').hasClass('cb-float-panel--open')) {
            $input.trigger('focus');
        }
    });

    $('#cb-float-close').on('click', function (e) {
        e.stopPropagation();
        $('#cb-float-panel').removeClass('cb-float-panel--open');
    });

    /* ── Agregar burbuja al chat ── */
    function addBubble(text, type) {
        var $bubble = $('<div class="cb-bubble cb-bubble--' + type + '">').text(text);
        $messages.append($bubble);
        scrollToBottom();
        return $bubble;
    }

    /* ── Mostrar indicador de escritura (3 puntos) ── */
    function showTyping() {
        var $typing = $('<div class="cb-typing" id="cb-typing">')
            .html('<span></span><span></span><span></span>');
        $messages.append($typing);
        scrollToBottom();
    }

    function hideTyping() {
        $('#cb-typing').remove();
    }

    /* ── Scroll al fondo del chat ── */
    function scrollToBottom() {
        $messages.scrollTop($messages[0].scrollHeight);
    }

    /* ── Enviar mensaje ── */
    function sendMessage() {
        var text = $.trim($input.val());
        if (!text || text.length > maxChars) return;

        // Mostrar burbuja del usuario
        addBubble(text, 'user');

        // Guardar en historial y limpiar input
        history.push({ role: 'user', content: text });
        $input.val('').trigger('input');
        $counter.text(0);

        // Deshabilitar controles mientras espera
        $input.prop('disabled', true);
        $sendBtn.prop('disabled', true);

        // Mostrar indicador de escritura
        showTyping();

        // Llamada AJAX al backend
        $.post(cbConfig.ajaxUrl, {
            action:  'cb_send',
            nonce:   cbConfig.nonce,
            message: text,
            history: JSON.stringify(history.slice(0, -1)) // historial sin el mensaje actual
        })
        .done(function (res) {
            hideTyping();
            if (!res.success) {
                addBubble('Error: ' + (res.data.msg || 'algo salió mal.'), 'bot');
                return;
            }
            var reply = res.data.reply;
            addBubble(reply, 'bot');
            history.push({ role: 'assistant', content: reply });
        })
        .fail(function () {
            hideTyping();
            addBubble('Error de conexión. Intenta de nuevo.', 'bot');
        })
        .always(function () {
            $input.prop('disabled', false);
            $sendBtn.prop('disabled', false);
            $input.trigger('focus');
        });
    }

}(jQuery));
