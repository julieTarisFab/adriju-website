<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.squareonemd.co.uk
 * @since      1.1.9
 *
 * @package    Insta_Grab
 * @subpackage Insta_Grab/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Insta_Grab
 * @subpackage Insta_Grab/public
 * @author     Elliott Richmond <elliott@squareonemd.co.uk>
 */
class Insta_Grab_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $insta_grab    The ID of this plugin.
	 */
	private $insta_grab;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $insta_grab       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $insta_grab, $version ) {

		$this->insta_grab = $insta_grab;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Insta_Grab_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Insta_Grab_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->insta_grab, plugin_dir_url( __FILE__ ) . 'css/insta-grab-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Insta_Grab_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Insta_Grab_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->insta_grab, plugin_dir_url( __FILE__ ) . 'js/insta-grab-public.js', array( 'jquery' ), $this->version, false );

	}
	
	public function check_instagram_authorised() {
		if (isset($_GET) && !empty($_GET['code'])) {
			$instasetup = get_option( 'instagrabagram_option_name' );
		    $instagram = new Instagram(array(
		      'apiKey'      => $instasetup['insta_apiKey'],
		      'apiSecret'   => $instasetup['insta_apiSecret'],
		      'apiCallback' => $instasetup['insta_apiCallback']
		    ));
			
			$code = $_GET['code'];
			$data = $instagram->getOAuthToken($code);
			
			if ($data->access_token) {
				$instasetup['insta_access_token'] = $data->access_token;
				update_option('instagrabagram_option_name', $instasetup);
				echo '<div class="instagrab-notice success">';
				echo 'Congrats! Instagram has been authorised! <a href="'.get_bloginfo('url').'">Remove</a>';
				echo '</div>';
			} else {
				echo '<div class="instagrab-notice error">';
				echo 'Error! Unable to connect to your instagram account! Please try again.';
				echo '</div>';
			}

		}
	
	}
	
	/**
	 * get instagram settings
	 * @TODO clean this up
	 * @since    1.0.0
	 */
	public function render_instagram_feed () {
		
		$article_id = 'instagrab';
		$ul_id = 'igag-ul';

		$jsondata = $this->curl_instagram_request();
		
		$instasettings = $this->get_instagram_settings();
		
		$html = '';
		
		if ($jsondata){
			
			$medias = $jsondata;
			
			$instagrabagram_results = json_decode(json_encode($medias), true);
			
			$media_count = $instasettings['insta_count'];
			
			if ($instagrabagram_results['meta']['code'] == '400'){
				$html .= 'sorry couldn\'t connect to instagram';		
			} else {
				if (empty($instagrabagram_results['data'])) {
					$html .= 'Sorry, nothing found under the hashtag "'.$hashtag.'"';
				}
				
				$html .= '<article id="'.apply_filters('igag_article_id', $article_id).'" class="hentry">';
				do_action('igag_before_ul_list_images');
				$html .= '<ul id="'. apply_filters('igag_ul_id',$ul_id) .'" class="entry-content">';
						$count = 0;
					    foreach ($instagrabagram_results['data'] as $media) {
					    	if ($count == $media_count) continue;
					    	$image = $media['images']['low_resolution']['url'];					    	
					    	$html .= '<li>'.$this->imagelinkcheck($instasetup, $media).'</li>';

							$count++;
					    }
				$html .= '</ul>';
				do_action('igag_after_ul_list_images');
				$html .= '</article>';
			}
		} else {
			$settings_url = get_bloginfo('url') . '/wp-admin/options-general.php?page=instagrabagram-setting-admin';
			$html .= '<div class="row"><div class="primary alert">There seems to be a problem connecting to your Instagram App, have you input the correct <a href="' . $settings_url . '">details here</a></div></div>';
		}
		
		
		return $html;
	
	}
	
	/**
	 * Get the settings.
	 *
	 * @since    1.0.0
	 */
	public function get_instagram_settings() {
		$instasettings = get_option( 'instagrabagram_settings_name' );
		return $instasettings;
	}
	
	/**
	 * Get the setup settings.
	 *
	 * @since    1.0.0
	 */
	public function get_instagram_setup() {
		$instasetup = get_option( 'instagrabagram_option_name' );
		return $instasetup;
	}

	/**
	 * Make the curl request.
	 *
	 * @since    1.0.0
	 */
	public function curl_instagram_request() {
		
		$instasetup = $this->get_instagram_setup();
		$instasettings = $this->get_instagram_settings();
				
	    $hashtag = $instasettings['insta_apitag'];	 
	    
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.instagram.com/v1/tags/'.$hashtag.'/media/recent?access_token='.$instasetup['insta_access_token'].'&scope=basic',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"authorization: Basic Og==",
				"cache-control: no-cache",
				"postman-token: d9f6ca69-3642-eb3d-c5f6-d6cba3b0cd81"
			),
		));
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			$jsondata = json_decode($response);
		}
		
		return $jsondata;

	}
	
	public function imagelinkcheck($instasetup, $media) {

		if (isset($instasetup['insta_link'])) {
			$link = $media['link'];
			$image_output = '<a href="'.esc_html($link).'" target="_blank"><img src="'.$media['images']['low_resolution']['url'].'"></a>';
		} else {
			$image_output = '<img src="'.$media['images']['low_resolution']['url'].'">';
		}
		
		return $image_output;
		
	}
	
	/**
	 * Register shortcodes.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		
		add_shortcode( 'instagrabagram', array($this, 'instagrabagram_func') );
		
	}

	/**
	 * Fire instagrabagram_func shortcode.
	 *
	 * @since    1.0.0
	 */
	public function instagrabagram_func( $atts ){
		
		$html = $this->render_instagram_feed();
		
		return $html;
		
	}

}
