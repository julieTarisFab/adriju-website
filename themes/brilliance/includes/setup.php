<?php 

//Add layout pieces
add_action('wp_head', 'cpotheme_theme_layout');
function cpotheme_theme_layout($data){ 
	add_action('cpotheme_top', 'cpotheme_top_menu');
	add_action('cpotheme_header', 'cpotheme_logo');
	add_action('cpotheme_header', 'cpotheme_menu_toggle');
	add_action('cpotheme_header', 'cpotheme_menu');
	add_action('cpotheme_before_main', 'cpotheme_home_slider', 100);
	add_action('cpotheme_before_main', 'cpotheme_home_tagline', 200);
	add_action('cpotheme_before_main', 'cpotheme_home_features', 300);
	add_action('cpotheme_before_main', 'cpotheme_home_portfolio', 400);
	add_action('cpotheme_before_main', 'cpotheme_home_services', 500);
	add_action('cpotheme_before_main', 'cpotheme_home_clients', 600);
	add_action('cpotheme_before_main', 'cpotheme_home_testimonials', 700);
	add_action('cpotheme_title', 'cpotheme_page_title');
	add_action('cpotheme_title', 'cpotheme_breadcrumb');
	add_action('cpotheme_subfooter', 'cpotheme_subfooter');
	add_action('cpotheme_footer', 'cpotheme_footer_menu');
	add_action('cpotheme_footer', 'cpotheme_footer');
}

//Add homepage slider
function cpotheme_home_slider(){ 
	if(is_front_page()) get_template_part('template-parts/homepage', 'slider'); 
}

//Add homepage tagline
function cpotheme_home_tagline(){ 
	if(is_front_page()) cpotheme_block('home_tagline', 'tagline', 'container');
}

//Add homepage features
function cpotheme_home_features(){ 
	if(is_front_page()) get_template_part('template-parts/homepage', 'features'); 
}

//Add homepage portfolio
function cpotheme_home_portfolio(){ 
	if(is_front_page()) get_template_part('template-parts/homepage', 'portfolio'); 
}

//Add homepage services
function cpotheme_home_services(){ 
	if(is_front_page()) get_template_part('template-parts/homepage', 'services'); 
}

//Add homepage testimonials
function cpotheme_home_testimonials(){ 
	if(is_front_page()) get_template_part('template-parts/homepage', 'testimonials'); 
}

//Add homepage clients
function cpotheme_home_clients(){ 
	if(is_front_page()) get_template_part('template-parts/homepage', 'clients'); 
}

add_filter('cpotheme_font_headings', 'cpotheme_theme_fonts');
add_filter('cpotheme_font_menu', 'cpotheme_theme_fonts');
function cpotheme_theme_fonts($data){ 
	return 'Open+Sans:700';
}

add_filter('cpotheme_font_body', 'cpotheme_theme_fonts_body');
function cpotheme_theme_fonts_body($data){ 
	return 'Open+Sans';
}

add_filter('cpotheme_customizer_controls', 'cpotheme_theme_settings');
function cpotheme_theme_settings($data){ 
	
	unset($data['home_portfolio']);
	unset($data['home_features']);
	$data['home_tagline']['default'] = false;
	$data['home_posts']['default'] = true;
	
	
	return $data;
}