<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

/* include custom image module file*/
include( get_stylesheet_directory() . '/custom-pb-image-module.php' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10 );

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_separate', trailingslashit( get_stylesheet_directory_uri() ) . 'ctc-style.css', array( 'chld_thm_cfg_parent','divi-style','et-shortcodes-css','et-shortcodes-responsive-css','magnific-popup' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css' );


// END ENQUEUE PARENT ACTION

function ajout_assets() {
  wp_enqueue_style( 'font_awesome', trailingslashit(get_stylesheet_directory_uri()).'assets/lib/font-awesome-4.7.0/css/font-awesome.min.css',array() );
  wp_enqueue_style( 'font_awesome5', trailingslashit(get_stylesheet_directory_uri()).'assets/lib/font-awesome-5/web-fonts-with-css/css/fontawesome-all.min.css',array() );
  wp_enqueue_style( 'bootstrap_css', trailingslashit(get_stylesheet_directory_uri()).'assets/lib/bootstrap/css/bootstrap.min.css',array() );
  wp_enqueue_style( 'owl_default_css', trailingslashit(get_stylesheet_directory_uri()).'assets/lib/OwlCarousel2-2.2.1/dist/assets/owl.carousel.min.css',array() );
  wp_enqueue_style( 'owl_theme_default_css', trailingslashit(get_stylesheet_directory_uri()).'assets/lib/OwlCarousel2-2.2.1/dist/assets/owl.theme.default.min.css',array() );
  wp_enqueue_script('jquery');
  wp_enqueue_script('bootstrapBundle_js', trailingslashit(get_stylesheet_directory_uri()).'assets/lib/bootstrap/js/bootstrap.bundle.min.js', array('popper', 'jquery'), false, true );
  wp_enqueue_script('bootstrap_js', trailingslashit(get_stylesheet_directory_uri()).'assets/lib/bootstrap/js/bootstrap.min.js', array('popper', 'jquery'), false, true );
  wp_enqueue_script('owl_js', trailingslashit(get_stylesheet_directory_uri()).'assets/lib/OwlCarousel2-2.2.1/dist/owl.carousel.min.js', array('jquery'), false, true );
  wp_enqueue_script('script_perso', trailingslashit(get_stylesheet_directory_uri()).'script.js', array('jquery'), false, true );
  // wp_enqueue_style( 'style_perso', trailingslashit(get_stylesheet_directory_uri()).'style.css',array() );
  // philippetaris-photographe.com\wp-content\themes\Divi-child\assets\OwlCarousel2-2.2.1\dist\assets\owl.theme.default.min.css
  // wp_enqueue_script('popper', trailingslashit(get_stylesheet_directory_uri()).'assets/popperJs_perso/popper.js', array('jquery'), false,  true );
};

add_action( 'wp_enqueue_scripts', 'ajout_assets' );

function focus_img( $atts ){

  $focus_img = "<div id='carroussel-focus' class='owl-carousel owl-theme focus-carroussel'>";
  $my_query = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 3,  'order' => 'DESC' ) );

  while ( $my_query->have_posts() ) : $my_query->the_post();
  $focus_img .= "
      <div class='item'>
        <div class='focus_left'>
          <h3>" . get_the_title() . "</h3>
          <p>" . get_the_excerpt() . " </p>
          <div class='readMore'>
            <a href='" . esc_url( get_permalink()) . "'>Lire l'article</a>
          </div>
        </div>
        <div class='focus_right'>
          <a href='" . esc_url( get_permalink()) . "'><img src='" . get_the_post_thumbnail_url() . "'></a>
        </div>
      </div>";
   endwhile;

   $focus_img .= '</div>';
	 return $focus_img;
}
add_shortcode( 'focus', 'focus_img' );
//
function add_footer( ) {
  $footer = '<footer id="main-footer"><div id="footer-bottom">
					<div class="container clearfix">
				<div id="footer-info"><a href="/mentions-legales/">Mentions Légales</a> -
Web design et développement par julie Taris et adrien Centonze - 2018</div>					</div>	<!-- .container -->
				</div>
			</footer>';
        return $footer;
}
add_shortcode( 'the_foot', 'add_footer' );
