<?php

// Traer los estilos
function enqueue_styles(){
    wp_enqueue_style('parent_styles', get_template_directory_uri().'/style.css');

    $child_css = get_stylesheet_directory().'/assets/css/styles.css';
    wp_enqueue_style('astra_child_styles', get_stylesheet_directory_uri().'/assets/css/styles.css', [], filemtime($child_css));
}

add_action('wp_enqueue_scripts','enqueue_styles');


function enqueue_scripts(){
    wp_enqueue_script('menu-toggle', get_stylesheet_directory_uri().'/assets/js/menu-toggle.js',[],'1.0.0');

    $equal_height_js = get_stylesheet_directory().'/assets/js/pipa-equal-height.js';
    wp_enqueue_script('pipa-equal-height', get_stylesheet_directory_uri().'/assets/js/pipa-equal-height.js', [], filemtime($equal_height_js));
}

add_action('wp_enqueue_scripts','enqueue_scripts');

// Registrar ubicación del menú
function register_menus() {
    register_nav_menus([
        'header-menu-hijo' => __( 'Header Menu Hijo', 'astra-child' ),
    ]);
}
add_action( 'init', 'register_menus' );

function pipa_render_card( $imagen, $descripcion, $meta = '' ) {
    $titulo = get_field('titulo') ?: get_the_title();
    ob_start();
    ?>
    <div class="pipa-card">
        <?php if ( $imagen ) : ?>
            <div class="pipa-card-image-wrap">
                <img src="<?php echo esc_url( $imagen['url'] ); ?>" alt="<?php echo esc_attr( $titulo ); ?>" class="pipa-card-img">
            </div>
        <?php else : ?>
            <div class="pipa-card-image-wrap pipa-card-image-wrap--placeholder">&#127860;</div>
        <?php endif; ?>
        <div class="pipa-card-body">
            <h3 class="pipa-card-title"><?php echo esc_html( $titulo ); ?></h3>
            <?php if ( $descripcion ) : ?>
                <p class="pipa-card-desc"><?php echo esc_html( $descripcion ); ?></p>
            <?php endif; ?>
            <?php if ( $meta ) : ?>
                <div class="pipa-card-meta"><?php echo $meta; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function pipa_productos_grid() {
    $query = new WP_Query([ 'post_type' => 'producto', 'posts_per_page' => -1 ]);
    $output = '';

    if ( $query->have_posts() ) {
        $output .= '<div class="pipa-grid pipa-grid--productos">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $descripcion = get_field('descripcion');
            $precio = get_field('precio');
            $meta = $precio ? '<span class="pipa-precio">$' . esc_html($precio) . '</span>' : '';
            $output .= pipa_render_card( get_field('imagen'), $descripcion, $meta );
        }
        $output .= '</div>';
    }
    wp_reset_postdata();

    return $output;
}
add_shortcode('productos_grid', 'pipa_productos_grid');

function pipa_recetas_grid() {
    $query = new WP_Query([ 'post_type' => 'receta', 'posts_per_page' => -1 ]);
    $output = '';

    if ( $query->have_posts() ) {
        $output .= '<div class="pipa-grid pipa-grid--recetas">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $titulo = get_field('titulo') ?: get_the_title();
            $descripcion = get_field('descripcion');
            $tiempo = get_field('tiempo_de_preparacion');
            $porciones = get_field('porciones');
            $preparacion = get_field('preparacion');

            ob_start();
            ?>
            <div class="pipa-card">
                <div class="pipa-card-body">
                    <h3 class="pipa-card-title"><?php echo esc_html( $titulo ); ?></h3>
                    <?php if ( $descripcion ) : ?>
                        <p class="pipa-card-desc"><?php echo esc_html( $descripcion ); ?></p>
                    <?php endif; ?>
                    <?php if ( $tiempo || $porciones ) : ?>
                        <div class="pipa-card-meta">
                            <?php if ( $tiempo ) : ?>
                                <span>&#9201; <?php echo esc_html( $tiempo ); ?></span>
                            <?php endif; ?>
                            <?php if ( $porciones ) : ?>
                                <span>&#127860; <?php echo esc_html( $porciones ); ?> porciones</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ( $preparacion ) : ?>
                        <h4 class="pipa-receta-subtitulo">Preparación</h4>
                        <div class="pipa-receta-pasos"><?php echo wpautop( wp_kses_post( $preparacion ) ); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $output .= ob_get_clean();
        }
        $output .= '</div>';
    }
    wp_reset_postdata();

    return $output;
}
add_shortcode('recetas_grid', 'pipa_recetas_grid');

function pipa_preguntas_lista() {
    $query = new WP_Query([ 'post_type' => 'pregunta', 'posts_per_page' => -1 ]);
    $output = '';

    if ( $query->have_posts() ) {
        $output .= '<div class="pipa-faq">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $pregunta = get_field('pregunta') ?: get_the_title();
            $respuesta = get_field('respuesta');

            $output .= '<details class="pipa-faq-item">';
            $output .= '<summary class="pipa-faq-question">' . esc_html( $pregunta ) . '</summary>';
            $output .= '<div class="pipa-faq-answer">' . wp_kses_post( $respuesta ) . '</div>';
            $output .= '</details>';
        }
        $output .= '</div>';
    }
    wp_reset_postdata();

    return $output;
}
add_shortcode('preguntas_lista', 'pipa_preguntas_lista');

function pipa_blog_grid() {
    $query = new WP_Query([ 'post_type' => 'post', 'post_status' => 'publish', 'posts_per_page' => -1 ]);
    $output = '';

    if ( $query->have_posts() ) {
        $output .= '<div class="pipa-grid pipa-grid--blog">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $descripcion = get_the_excerpt();

            ob_start();
            ?>
            <div class="pipa-card pipa-card--text-only">
                <div class="pipa-card-body">
                    <h3 class="pipa-card-title"><?php the_title(); ?></h3>
                    <?php if ( $descripcion ) : ?>
                        <p class="pipa-card-desc"><?php echo esc_html( $descripcion ); ?></p>
                    <?php endif; ?>
                    <div class="pipa-card-meta">
                        <span><?php echo esc_html( get_the_date() ); ?></span>
                        <a class="pipa-card-link" href="<?php the_permalink(); ?>">Leer más &rarr;</a>
                    </div>
                </div>
            </div>
            <?php
            $output .= ob_get_clean();
        }
        $output .= '</div>';
    }
    wp_reset_postdata();

    return $output;
}
add_shortcode('blog_grid', 'pipa_blog_grid');
