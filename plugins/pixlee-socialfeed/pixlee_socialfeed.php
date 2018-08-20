<?php 
    /*
    Plugin Name: Pixlee Social Feed
    Plugin URI: http://www.pixlee.com/social-feed
    Description: Free Instagram displays for your website powered by Pixlee
    Author: Pixlee
    Version: 1.0
    Author URI: http://www.pixlee.com
    License: GPL2

    Pixlee Social Feed is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.
 
	Pixlee Social Feed is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
 
	You should have received a copy of the GNU General Public License
	along with Pixlee Social Feed. If not, see https://www.gnu.org/licenses/gpl-2.0.en.html.
    */

add_action('admin_menu', 'pixlee_socialfeed_setup_menu');
add_action('admin_init', 'pixlee_register_widget_settings' );
add_action( 'widgets_init', 'pixlee_socialfeed_init_widget' );

function pixlee_register_scripts()
{
    wp_register_script( 'admin_page', plugins_url( '/js/pixlee_socialfeed.js', __FILE__ ), array( 'jquery' ) );
    wp_enqueue_script( 'admin_page' );
}

function pixlee_register_widget_settings() {
	register_setting( 'pixlee-socialfeed-settings-group', 'widget_id' );
	register_setting( 'pixlee-socialfeed-settings-group', 'api_key' );
}
 
function pixlee_socialfeed_setup_menu(){
    add_menu_page( 'Pixlee Social Feed Settings', 'Pixlee Social Feed', 'manage_options', 'pixlee_socialfeed', 'pixlee_socialfeed_init', plugins_url( '/assets/pixlee-logo-16px-16px.png', __FILE__ ) );
	add_action( 'admin_enqueue_scripts', 'pixlee_register_scripts' );
	add_option('widget_id');
	add_option('api_key');
}

function pixlee_socialfeed_init(){
?>
        <div class="wrap">
        <h1>Pixlee Social Feed</h1>
        <p>Click the <b>Generate Social Feed</b> button to sign up for your free Instagram gallery.</p>

        <?php 
        	$loginButtonAttributes = array('onclick' => 'socialFeedWindow("generate")');
        	submit_button('Generate Social Feed', 'primary', '', true, $loginButtonAttributes); 
        ?>

        <p>Once you authenticate with Instagram, a unique Id and API key will generated for your gallery below</p>

		<form method="post" action="options.php" id="options_form">
		    <table class="form-table">
		    <?php settings_fields( 'pixlee-socialfeed-settings-group' ); ?>
		    <?php do_settings_sections( 'pixlee-socialfeed-settings-group' ); ?>
		        <tr valign="top">
		        <th scope="row">Gallery Id</th>
		        <td><input type="text" name="widget_id" id="widget_id" value="<?php echo esc_attr( get_option('widget_id') ); ?>" readonly/></td>
		        </tr>

		        <tr valign="top">
		        <th scope="row">API Key</th>
		        <td><input type="text" name="api_key" id="api_key" value="<?php echo esc_attr( get_option('api_key') ); ?>" readonly/></td>
		        </tr>
		         
		    </table>
		    
		     <div class="wrap">
		    <?php 
		    	$logoutButtonAttributes = array('onclick' => 'socialFeedWindow("settings")', 'style' => 'margin-left:1em');
		    	
		    	submit_button('Save Changes', 'primary', '', false); 
        		submit_button('Account Settings', 'secondary', '', false, $logoutButtonAttributes); 
		    ?>
		</div>

		</form>
		<br>
		<h1>Publishing your Social Feed</h1>
		<p>There are two ways to publish your social feed on your website: </p>
		<p><b>(1)</b> Navigate to <b>Appearance > Widgets</b> to see the list of available widgets and select <b>Pixlee Social Feed</b>, or</p>
		<p><b>(2)</b> Use the shortcode <b>[pixlee_widget]</b> on any page, blog post or custom widget </b>
		<br>
		</div>
<?php
}

add_shortcode('pixlee_widget', 'pixlee_widget_shortcode');
 
function pixlee_widget_shortcode(){
	$apiKey = esc_attr( get_option('api_key') );
	$widgetId = esc_attr( get_option('widget_id') );

	if ($apiKey && $widgetId){	
    	if(strpos($_SERVER['REQUEST_URI'], "customize_changeset_uuid") == false){
    		$widgetEmbed = '<div id="pixlee_container"></div><script type="text/javascript">window.PixleeAsyncInit = function() {Pixlee.init({apiKey:\''.$apiKey.'\'});Pixlee.addSimpleWidget({widgetId:\''.$widgetId.'\'});};</script><script src="//instafeed.assets.pixlee.com/assets/pixlee_widget_1_0_0.js"></script>';
    	}else{
    		$widgetEmbed = '<i>Preview not currently available</i>';
    	}
	} else {
		$widgetEmbed = '';
	}
    return $widgetEmbed;
}

// Register and load the widget
function pixlee_socialfeed_init_widget() {
    register_widget( 'pixlee_socialfeed_widget' );
}
 
// Creating the widget
class pixlee_socialfeed_widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'pixlee_socialfeed',
			'description' => ' Free Instagram displays for your website powered by Pixlee',
		);
		parent::__construct( 'pixlee_socialfeed', 'Pixlee Social Feed', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
	
		echo pixlee_widget_shortcode();
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}
?>