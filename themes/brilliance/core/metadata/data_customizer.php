<?php 

//Define customizer sections
if(!function_exists('cpotheme_metadata_panels')){
	function cpotheme_metadata_panels(){
		$data = array();
		
		$data['cpotheme_layout'] = array(
		'title' => __('Layout', 'brilliance'),
		'description' => __('Here you can find settings that control the structure and positioning of specific elements within your website.', 'brilliance'),
		'priority' => 25);
		
		return apply_filters('cpotheme_customizer_panels', $data);
	}
}


//Define customizer sections
if(!function_exists('cpotheme_metadata_sections')){
	function cpotheme_metadata_sections(){
		$data = array();
		
		$data['epsilon-section-pro'] = array(
		'type' => 'epsilon-section-pro',
		'title'       => esc_html__( 'LITE vs PRO comparison', 'brilliance' ),
		'button_text' => esc_html__( 'Learn more', 'brilliance' ),
		'button_url'  => esc_url_raw( admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
		'priority'    => 0
		);

		$data['cpotheme_management'] = array(
		'title' => __('General Theme Options', 'brilliance'),
		'description' => __('Options that help you manage your theme better.', 'brilliance'),
		'capability' => 'edit_theme_options',
		'priority' => 15);
		
		$data['cpotheme_layout_general'] = array(
		'title' => __('Site Wide Structure', 'brilliance'),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 25);
		
		$data['cpotheme_layout_home'] = array(
		'title' => __('Homepage', 'brilliance'),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 50);
		
		if(defined('CPOTHEME_USE_SLIDES') && CPOTHEME_USE_SLIDES == true){
			$data['cpotheme_layout_slider'] = array(
			'title' => __('Slider', 'brilliance'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_FEATURES') && CPOTHEME_USE_FEATURES == true){
			$data['cpotheme_layout_features'] = array(
			'title' => __('Features', 'brilliance'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true){
			$data['cpotheme_layout_portfolio'] = array(
			'title' => __('Portfolio', 'brilliance'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true){
			$data['cpotheme_layout_services'] = array(
			'title' => __('Services', 'brilliance'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true){
			$data['cpotheme_layout_team'] = array(
			'title' => __('Team Members', 'brilliance'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_TESTIMONIALS') && CPOTHEME_USE_TESTIMONIALS == true){
			$data['cpotheme_layout_testimonials'] = array(
			'title' => __('Testimonials', 'brilliance'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		if(defined('CPOTHEME_USE_CLIENTS') && CPOTHEME_USE_CLIENTS == true){
			$data['cpotheme_layout_clients'] = array(
			'title' => __('Clients', 'brilliance'),
			'capability' => 'edit_theme_options',
			'panel' => 'cpotheme_layout',
			'priority' => 50);
		}
		
		$data['cpotheme_typography'] = array(
		'title' => __('Typography', 'brilliance'),
		'description' => __('Custom typefaces for the entire site.', 'brilliance'),
		'capability' => 'edit_theme_options',
		'priority' => 45);

		$data['cpotheme_layout_posts'] = array(
		'title' => __('Blog Posts', 'brilliance'),
		'capability' => 'edit_theme_options',
		'panel' => 'cpotheme_layout',
		'priority' => 50);
		
		$data['cpotheme_typography'] = array(
		'title' => __('Typography', 'brilliance'),
		'capability' => 'edit_theme_options',
		'priority' => 45);
		
		return apply_filters('cpotheme_customizer_sections', $data);
	}
}


if(!function_exists('cpotheme_metadata_customizer')){
	function cpotheme_metadata_customizer($std = null){
		$data = array();

		$data['general_upsell'] = array(
		'section'      => 'cpotheme_upsell',
		'type'		   => 'epsilon-upsell',
        'options'      => array(
            esc_html__( 'Slider options', 'brilliance' ),
            esc_html__( 'WooCommerce Integration', 'brilliance' ),
            esc_html__( 'Reorder Sections', 'brilliance' ),
            esc_html__( 'Section Description', 'brilliance' ),
            esc_html__( 'Custom Colors', 'brilliance' ),
            esc_html__( 'Custom Typography', 'brilliance' ),
		    esc_html__( 'Dedicated Support Team', 'brilliance'),
		    esc_html__( 'Updates + Feature releases for 1 year', 'brilliance'),
        ),
        'requirements' => array(
            esc_html__( 'You can set the slider height. Also you can control the speed and the duration of a slide.', 'brilliance' ),
            esc_html__( 'Now you can add your shop products on Homepage.', 'brilliance' ),
            esc_html__( 'You can order Homepage sections anyway you want', 'brilliance' ),
            esc_html__( 'For each section, apart from title one you can also add a description for users to better understand your sections content', 'brilliance' ),
            esc_html__( 'You can change your site\'s colors directly from Customizer. Changes happen in real time.', 'brilliance' ),
            esc_html__( 'You can change your site\'s typography directly from Customizer. Changes happen in real time.', 'brilliance' ),
	   		esc_html__( 'Theme updates and support for 1 year - included with purchase', 'brilliance'),
	   		esc_html__( 'Theme updates and support for 1 year - included with purchase', 'brilliance'),
        ),

        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
        'button_text'  => esc_html__( 'See PRO vs Lite', 'brilliance' ),
        'second_button_url'  => cpotheme_upgrade_link(),
        'second_button_text' => esc_html__( 'Get the PRO version!', 'brilliance' ),
        'separator' => '- or -'

		);
		
		$data['general_logo'] = array(
		'label' => __('Custom Logo', 'brilliance'),
		'description' => __('Insert the URL of an image to be used as a custom logo.', 'brilliance'),
		'section' => 'title_tagline',
		'sanitize' => 'esc_url',
		'type' => 'image');
		
		$data['general_logo_width'] = array(
		'label' => __('Logo Width (px)', 'brilliance'),
		'description' => __('Forces the logo to have a specified width.', 'brilliance'),
		'section' => 'title_tagline',
		'type' => 'text',
		'placeholder' => '(none)',
		'sanitize' => 'absint',
		'width' => '100px');
		
		$data['general_texttitle'] = array(
		'label' => __('Enable Text Title?', 'brilliance'),
		'description' => __('Activate this to display the site title as text.', 'brilliance'),
		'section' => 'title_tagline',
		'type' => 'checkbox',
		'sanitize' => 'cpotheme_sanitize_bool',
		'std' => false);
		
		$data['general_editlinks'] = array(
		'label' => __('Show Edit Links', 'brilliance'),
		'description' => __('Display edit links on the site layout for logged in users.', 'brilliance'),
		'section' => 'cpotheme_management',
		'type' => 'checkbox',
		'sanitize' => 'cpotheme_sanitize_bool',
		'std' => '1');
		
		$data['homepage_upsell'] = array(
		'section'      => 'cpotheme_layout_home',
		'type'		   => 'epsilon-upsell',
        'options'      => array(
            esc_html__( 'Reorder Sections', 'brilliance' ),
        ),
        'requirements' => array(
            esc_html__( 'You can order Homepage sections anyway you want', 'brilliance' ),
        ),
        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
        'button_text'  => esc_html__( 'See PRO vs Lite', 'brilliance' ),
        'second_button_url'  => cpotheme_upgrade_link(),
        'second_button_text' => esc_html__( 'Get the PRO version!', 'brilliance' ),
        'separator' => '- or -'
        );

		$data['home_tagline'] = array(
		'label' => __('Tagline Title', 'brilliance'),
		'section' => 'cpotheme_layout_home',
		'empty' => true,
		'multilingual' => true,
		'default' => __('Add your custom tagline here.', 'brilliance'),
		'sanitize' => 'wp_kses_post',
		'type' => 'textarea');

		// Site Wide Structure
		$data['general_upsell'] = array(
		'section'      => 'cpotheme_layout_general',
		'type'		   => 'epsilon-upsell',
        'options'      => array(
            esc_html__( 'Sidebar Position', 'brilliance' ),
            esc_html__( 'Footer Widgets', 'brilliance' ),
            esc_html__( 'Breadcrumbs', 'brilliance' ),
            esc_html__( 'Language switcher', 'brilliance' ),
            esc_html__( 'Shopping cart', 'brilliance' ),
		    esc_html__( 'Social Links', 'brilliance'),
		    esc_html__( 'Copyright text', 'brilliance'),
        ),
        'requirements' => array(
            esc_html__( 'In the PRO version you can change the position of the sidebar', 'brilliance' ),
            esc_html__( 'In the PRO version you have the option to add footer widgets. You can organize these widgets in 2,3,4 or 5 columns.', 'brilliance' ),
            esc_html__( 'In the PRO version you can show/hide the breadcrumbs', 'brilliance' ),
            esc_html__( 'In the PRO version you can show/hide the language switcher', 'brilliance' ),
            esc_html__( 'In the PRO version you can show/hide the shopping cart.', 'brilliance' ),
	   		esc_html__( 'In the PRO version you can add social icons in the footer.', 'brilliance'),
	   		esc_html__( 'In the PRO version you can change the copyright text.', 'brilliance'),
        ),

        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
        'button_text'  => esc_html__( 'See PRO vs Lite', 'brilliance' ),
        'second_button_url'  => cpotheme_upgrade_link(),
        'second_button_text' => esc_html__( 'Get the PRO version!', 'brilliance' ),
        'separator' => '- or -'

		);

		$data['header_opaque'] = array(
		'label' => __('Enable Opaque Header', 'brilliance'),
		'section' => 'cpotheme_layout_general',
		'type' => 'checkbox',
		'default' => '0');
		
		//Homepage Slider
		if(defined('CPOTHEME_USE_SLIDES') && CPOTHEME_USE_SLIDES == true){
			$data['slider_settings'] = array(
			'label' => __('Slider Options', 'brilliance'),
			'description' => __('Customize the speed, timeout and effects of the homepage slider.', 'brilliance'),
			'section' => 'cpotheme_layout_slider',
			'type' => 'label');
		}
		
		//Homepage Features
		if(defined('CPOTHEME_USE_FEATURES') && CPOTHEME_USE_FEATURES == true){
			$data['home_features'] = array(
			'label' => __('Features Description', 'brilliance'),
			'section' => 'cpotheme_layout_features',
			'empty' => true,
			'multilingual' => true,
			'default' => __('Our core features', 'brilliance'),
			'sanitize' => 'wp_kses_post',
			'type' => 'textarea');
		}
		
		//Portfolio layout
		if(defined('CPOTHEME_USE_PORTFOLIO') && CPOTHEME_USE_PORTFOLIO == true){			
			$data['home_portfolio'] = array(
			'label' => __('Portfolio Description', 'brilliance'),
			'section' => 'cpotheme_layout_portfolio',
			'empty' => true,
			'multilingual' => true,
			'default' => __('Take a look at our work', 'brilliance'),
			'sanitize' => 'wp_kses_post',
			'type' => 'textarea');
		}
		
		//Services layout
		if(defined('CPOTHEME_USE_SERVICES') && CPOTHEME_USE_SERVICES == true){
			$data['services_upsell'] = array(
			'section'      => 'cpotheme_layout_services',
			'type'		   => 'epsilon-upsell',
	        'options'      => array(
	            esc_html__( 'Services Columns', 'brilliance' ),
	            esc_html__( 'Always display section', 'brilliance' ),
	        ),
	        'requirements' => array(
	            esc_html__( 'You can select on how many Columns you want to show your services.', 'brilliance' ),
	            esc_html__( 'In the PRO version you can show the homepage services in all pages.', 'brilliance' ),
	        ),
	        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
	        'button_text'  => esc_html__( 'See PRO vs Lite', 'brilliance' ),
	        'second_button_url'  => cpotheme_upgrade_link(),
	        'second_button_text' => esc_html__( 'Get the PRO version!', 'brilliance' ),
	        'separator' => '- or -'
			);

			$data['home_services'] = array(
			'label' => __('Services Description', 'brilliance'),
			'section' => 'cpotheme_layout_services',
			'empty' => true,
			'multilingual' => true,
			'default' => __('What we can offer you', 'brilliance'),
			'sanitize' => 'wp_kses_post',
			'type' => 'textarea');
		}
		
		//Services layout
		if(defined('CPOTHEME_USE_TEAM') && CPOTHEME_USE_TEAM == true){
			$data['team_upsell'] = array(
			'section'      => 'cpotheme_layout_team',
			'type'		   => 'epsilon-upsell',
	        'options'      => array(
	            esc_html__( 'Services Columns', 'brilliance' ),
	            esc_html__( 'Always display section', 'brilliance' ),
	        ),
	        'requirements' => array(
	            esc_html__( 'You can select on how many Columns you want to show your services.', 'brilliance' ),
	            esc_html__( 'In the PRO version you can show the homepage services in all pages.', 'brilliance' ),
	        ),
	        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
	        'button_text'  => esc_html__( 'See PRO vs Lite', 'brilliance' ),
	        'second_button_url'  => cpotheme_upgrade_link(),
	        'second_button_text' => esc_html__( 'Get the PRO version!', 'brilliance' ),
	        'separator' => '- or -'
			);

			$data['home_team'] = array(
			'label' => __('Team Members Description', 'brilliance'),
			'section' => 'cpotheme_layout_team',
			'empty' => true,
			'multilingual' => true,
			'default' => __('Meet our team', 'brilliance'),
			'sanitize' => 'wp_kses_post',
			'type' => 'textarea');
		}
		
		//Testimonials
		if(defined('CPOTHEME_USE_TESTIMONIALS') && CPOTHEME_USE_TESTIMONIALS == true){
			$data['testimonials_upsell'] = array(
			'section'      => 'cpotheme_layout_testimonials',
			'type'		   => 'epsilon-upsell',
	        'options'      => array(
	            esc_html__( 'Always display section', 'brilliance' ),
	        ),
	        'requirements' => array(
	            esc_html__( 'In the PRO version you can show the homepage testimonials in all pages.', 'brilliance' ),
	        ),
	        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
	        'button_text'  => esc_html__( 'See PRO vs Lite', 'brilliance' ),
	        'second_button_url'  => cpotheme_upgrade_link(),
	        'second_button_text' => esc_html__( 'Get the PRO version!', 'brilliance' ),
	        'separator' => '- or -'
			);

			$data['home_testimonials'] = array(
			'label' => __('Testimonials Description', 'brilliance'),
			'section' => 'cpotheme_layout_testimonials',
			'empty' => true,
			'multilingual' => true,
			'default' => __('What they say about us', 'brilliance'),
			'sanitize' => 'wp_kses_post',
			'type' => 'textarea');
		}
		
		//Clients
		if(defined('CPOTHEME_USE_CLIENTS') && CPOTHEME_USE_CLIENTS == true){
			$data['clients_upsell'] = array(
			'section'      => 'cpotheme_layout_clients',
			'type'		   => 'epsilon-upsell',
	        'options'      => array(
	            esc_html__( 'Clients Columns', 'brilliance' ),
	            esc_html__( 'Always display section', 'brilliance' ),
	        ),
	        'requirements' => array(
	            esc_html__( 'You can select on how many Columns you want to show your clients.', 'brilliance' ),
	            esc_html__( 'In the PRO version you can show the homepage clients in all pages.', 'brilliance' ),
	        ),
	        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
	        'button_text'  => esc_html__( 'See PRO vs Lite', 'brilliance' ),
	        'second_button_url'  => cpotheme_upgrade_link(),
	        'second_button_text' => esc_html__( 'Get the PRO version!', 'brilliance' ),
	        'separator' => '- or -'
			);
		}
		
		//Blog Posts
		$data['blog_upsell'] = array(
		'section'      => 'cpotheme_layout_posts',
		'type'		   => 'epsilon-upsell',
        'options'      => array(
            esc_html__( 'Blog Columns', 'brilliance' ),
            esc_html__( 'Hide elements', 'brilliance' ),
        ),
        'requirements' => array(
            esc_html__( 'You can select on how many columns you want to show your blog posts.', 'brilliance' ),
            esc_html__( 'You can modify the appearance and behavior of your blog posts by enabling or disabling specific elements.', 'brilliance' ),
        ),
        'button_url'   => esc_url_raw( get_admin_url() . 'themes.php?page=brilliance-welcome&tab=features' ),
        'button_text'  => esc_html__( 'See PRO vs Lite', 'brilliance' ),
        'second_button_url'  => cpotheme_upgrade_link(),
        'second_button_text' => esc_html__( 'Get the PRO version!', 'brilliance' ),
        'separator' => '- or -'
		);

		$data['home_posts'] = array(
		'label' => __('Enable Posts On Homepage', 'brilliance'),
		'section' => 'cpotheme_layout_posts',
		'type' => 'checkbox',
		'sanitize' => 'cpotheme_sanitize_bool',
		'default' => false);
		
		//Typography
		$data['type_settings'] = array(
		'label' => __('Typography Options', 'brilliance'),
		'description' => __('Select custom fonts for the headings, navigation, and body text of your site.', 'brilliance'),
		'section' => 'cpotheme_typography',
		'type' => 'label');
		
		//Colors		
		$data['color_settings'] = array(
		'label' => __('Color Options', 'brilliance'),
		'description' => __('Customize the colors of primary and secondary elements, as well as headings, navigation, and text.', 'brilliance'),
		'section' => 'colors',
		'type' => 'label');
		
		return apply_filters('cpotheme_customizer_controls', $data);
	}
}