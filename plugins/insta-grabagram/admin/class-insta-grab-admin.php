<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://www.squareonemd.co.uk
 * @since      1.1.9
 *
 * @package    Insta_Grab
 * @subpackage Insta_Grab/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Insta_Grab
 * @subpackage Insta_Grab/admin
 * @author     Elliott Richmond <elliott@squareonemd.co.uk>
 */
class Insta_Grab_Admin {

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
	 * @var      string    $insta_grab       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $insta_grab, $version ) {

		$this->insta_grab = $insta_grab;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
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

		wp_enqueue_style( $this->insta_grab, plugin_dir_url( __FILE__ ) . 'css/insta-grab-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->insta_grab.'-prism', plugin_dir_url( __FILE__ ) . 'css/prism.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
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

		wp_enqueue_script( $this->insta_grab, plugin_dir_url( __FILE__ ) . 'js/insta-grab-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->insta_grab .'-prism', plugin_dir_url( __FILE__ ) . 'js/prism.js', array(), $this->version, false );

	}

    /**
     * Add options page
     */
    public function add_plugin_page()
    {

        // This page will sit under "Settings"
        add_options_page(
            'Settings Instagrabagram', 
            'Instagrabagram', 
            'manage_options', 
            'instagrabagram-setting-admin', 
            array( $this, 'instagrabagram_admin_page' )
        );
        
        
    }

    /**
     * Options page callback
     */
    public function instagrabagram_admin_page()
    {
        // Set class property
        $this->options = get_option( 'instagrabagram_option_name' );
        $this->settings = get_option( 'instagrabagram_settings_name' );
                
        ?>
			   
			<div class="wrap">
				
				<div id="icon-options-general" class="icon32"></div>
				<h2>Instagrabagram</h2>
				
				<?php if (empty($this->options['insta_apiKey']) || empty($this->options['insta_apiSecret']) || empty($this->options['insta_apiCallback'])) { ?>
					<div style="width:99%; padding: 5px;" class="error below-h2"><p>It doesn't look like there are any Instagram Client details saved yet, make sure to create a new Client in your Instagram account, <a href="http://instagram.com/developer/" target="_blank">do you want to create that now?</a></p></div>
				<?php } ?>

		
				
				<div id="poststuff">
				
					<div id="post-body" class="metabox-holder columns-2">
					
						<!-- main content -->
						<div id="post-body-content">
							
<div class="tabs">
	<h2 class="insta-nav-tab-wrapper">
		<a href="#instatab1" class="nav-tab nav-tab-active">API Settings</a>
		<a href="#instatab2" class="nav-tab">Instagrabagram Settings</a>
		<a href="#instatab3" class="nav-tab">Hooks and Filters</a>
	</h2>
	
	<div id="instatab1" class="tabs nav-tab-active">

		<div class="meta-box-sortables ui-sortable">
			
			<div class="postbox">
			
				<div class="inside">
		            <form method="post" action="options.php">
		            <?php
		                // This prints out all hidden setting fields
		                settings_fields( 'instagrabagram_option_group' );   
		                do_settings_sections( 'instagrabagram-setting-admin' );
		                submit_button(); 
		            ?>
		            </form>
				</div> <!-- .inside -->
				<?php
					$api_settings = array();
					$api_settings[] = $this->options['insta_apiKey'];
					$api_settings[] = $this->options['insta_apiSecret'];
					$api_settings[] = $this->options['insta_apiCallback'];
					$filtered = array_filter($api_settings);
					if (!empty($filtered) && count($filtered) == 3) {
					    $getLoginUrl = 'https://api.instagram.com/oauth/authorize?client_id='.$this->options['insta_apiKey'].'&redirect_uri='.$this->options['insta_apiCallback'].'&response_type=code&scope=public_content';
					?>
					
					<?php if (empty($this->options['insta_access_token']) ) { ?>
					
						<div class="inside">
							<a href="<?php echo esc_attr($getLoginUrl); ?>">
				            <img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/authorise-instagram.png';?>" alt="authorise-instagram" width="256" height="128" />
				            </a>
						</div>
					
					<?php } ?>
					
				<?php } ?>
				
				
				<?php if (!is_null($this->options['insta_access_token'])) { ?>
					<div class="inside">
						<h3>Simply copy and paste this shortcode anywhere in your content....</h3>
						<code>[instagrabagram]</code>

						<h3>...Or copy and paste this action hook anywhere in your theme templates....</h3>
						<code>&lt;?php do_action('insta_grab_a_gram'); ?&gt;</code>
					</div>

				<?php } ?>

			</div> <!-- .postbox -->
			
		</div> <!-- .meta-box-sortables .ui-sortable -->
		
	</div>

	<div id="instatab2" class="tabs" style="display: none;">

		<div class="meta-box-sortables ui-sortable">
			
			<div class="postbox">
			
				<div class="inside">
		            <form method="post" action="options.php">
		            <?php
		                // This prints out all hidden setting fields
		                settings_fields( 'instagrabagram_settings_group' );   
		                do_settings_sections( 'igag-setting-admin' );
		                submit_button(); 
		            ?>
		            </form>
				</div> <!-- .inside -->
			
			</div> <!-- .postbox -->
			
		</div> <!-- .meta-box-sortables .ui-sortable -->
		
	</div>

	<div id="instatab3" class="tabs" style="display: none;">

		<div class="meta-box-sortables ui-sortable">
			
			<div class="postbox">
			
				<div class="inside">
					<h3><span>Hooks and Filters</span></h3>
					<div class="inside">
					<p>Adding the following hooks and filters to you own plugins or functions.php file will allow you to customise your own feed.</p>
						
					<p>To filter the instagrabagram article markup id</p>
<pre><code class="language-php">
function example_igag_container_id( $article_id ) {
	$article_id = 'instagrab';
    return $article_id;
}
add_filter( 'igag_article_id', 'example_igag_container_id' );
</code></pre>


<p>To filter the instagrabagram ul markup id</p>
<pre><code class="language-php">
function example_igag_ul_id( $ul_id ) {
	$ul_id = 'igag-ul';
    return $ul_id;
}
add_filter( 'igag_ul_id', 'example_igag_ul_id' );
</code></pre>

<p>An add action before images feed</p>
<pre><code class="language-php">
function example_igag_before_images() {
	echo '<header class="entry-header"><h2 class="entry-title">My Instagrabagram Feed!</h2></header>';
}
add_action('igag_before_ul_list_images', 'example_igag_before_images');
</code></pre>

<p>An add action after images feed</p>
<pre><code class="language-php">
function example_igag_after_images() {
	echo '<div class="entry-content"><p>Thanks for taking a peek, you can hashtag us too! By using #instagrabagram</p></div>';
}
add_action('igag_after_ul_list_images', 'example_igag_after_images');
</code></pre>

					</div> <!-- .inside -->
				</div> <!-- .inside -->
			
			</div> <!-- .postbox -->
			
		</div> <!-- .meta-box-sortables .ui-sortable -->
		
	</div>

</div>

							
						</div> <!-- post-body-content -->
						
						<!-- sidebar -->
						<div id="postbox-container-1" class="postbox-container">
							
							<div class="meta-box-sortables">
								
								<div class="postbox">
								
									<h3><span>What next?</span></h3>
									<div class="inside">

										<p>Once installed there are a couple of things you need to do to get things working.</p> 
										<ol>
											<li>Go to <a href="http://Instagram.com/developer/">http://Instagram.com/developer/</a> and click the button that says "Register Your Application"</li>
											<li>Fill in the details requested by Instagram, these will be thing like Application Name, Description, Website and redirect_uri (same as website will do).</li>
											<li>Once complete you will be given a CLIENT ID and a CLIENT SECRET.</li>
											<li>Now simply copy and paste the CLIENT ID, CLIENT SECRET and WEBSITE URI to this settings page which can be found from Dashboard > Settings > Instagrabagram.</li>
											<li>Save your settings then place <br>&lt;?php do_action('insta_grab_a_gram'); ?&gt;<br> where you want your feed to appear in one of your theme templates files. Alternatively you can simply drop this shortcode in to an post, page or custom post type's content editor:<br>[instagrabagram]</li>
											<li>Take some instagrams and hashtag them with the hashtag you setup in the settings and your feed will auto populate</li>
										</ol>

									</div> <!-- .inside -->
									
								</div> <!-- .postbox -->
								
							</div> <!-- .meta-box-sortables -->
							
						</div> <!-- #postbox-container-1 .postbox-container -->
						
					</div> <!-- #post-body .metabox-holder .columns-2 -->
					
					<br class="clear">
				</div> <!-- #poststuff -->
				
			</div> <!-- .wrap -->
        <?php }
	
    /**
     * Register and add settings
     * @TODO clean up comments
     * @TODO add a title option
     * @TODO add a css overide option,
     *   check priorty loading and css specificity 
     *   the theme might override naturally 
     */
    public function page_init() {        
        register_setting(
            'instagrabagram_option_group', // Option group
            'instagrabagram_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'API settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'instagrabagram-setting-admin' // Page
        );  

        add_settings_field(
            'insta_apiKey', // ID
            'CLIENT ID', // Title 
            array( $this, 'insta_apiKey_callback' ), // Callback
            'instagrabagram-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'insta_apiSecret', // ID
            'CLIENT SECRET', // Title 
            array( $this, 'insta_apiSecret_callback' ), // Callback
            'instagrabagram-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'insta_apiCallback', // ID
            'WEBSITE URL', // Title 
            array( $this, 'insta_apiCallback_callback' ), // Callback
            'instagrabagram-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'insta_access_token', // ID
            'Access Token', // Title 
            array( $this, 'insta_access_token_callback' ), // Callback
            'instagrabagram-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        register_setting(
            'instagrabagram_settings_group', // Option group
            'instagrabagram_settings_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'igag_setting_section_id', // ID
            'Instagrabagram settings', // Title
            array( $this, 'igag_setting_info' ), // Callback
            'igag-setting-admin' // Page
        );  

        add_settings_field(
            'insta_apitag', // ID
            'Inastagram Hashtag', // Title 
            array( $this, 'insta_apitag_callback' ), // Callback
            'igag-setting-admin', // Page
            'igag_setting_section_id' // Section           
        );      

        add_settings_field(
            'insta_count', // ID
            'How Many Images to pull (numeric)', // Title 
            array( $this, 'insta_count_callback' ), // Callback
            'igag-setting-admin', // Page
            'igag_setting_section_id' // Section           
        );      

        add_settings_field(
            'insta_link', // ID
            'Clickable image links to Instagram', // Title 
            array( $this, 'insta_link_callback' ), // Callback
            'igag-setting-admin', // Page
            'igag_setting_section_id' // Section           
        );      

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['insta_apiKey'] ) )
            $new_input['insta_apiKey'] = strip_tags( $input['insta_apiKey'] );

        if( isset( $input['insta_apiSecret'] ) )
            $new_input['insta_apiSecret'] = strip_tags( $input['insta_apiSecret'] );

        if( isset( $input['insta_apiCallback'] ) )
            $new_input['insta_apiCallback'] = esc_url_raw( $input['insta_apiCallback'] );

         if( isset( $input['insta_access_token'] ) )
            $new_input['insta_access_token'] = strip_tags( $input['insta_access_token'] );

       if( isset( $input['insta_apitag'] ) )
       		$hashtag = preg_replace('/[^A-Za-z0-9\-]/', '', $input['insta_apitag']);
            $new_input['insta_apitag'] = strip_tags( $hashtag );

        if( isset( $input['insta_count'] ) )
            $new_input['insta_count'] = absint( $input['insta_count'] );

        if( isset( $input['insta_link'] ) )
            $new_input['insta_link'] = strip_tags( $input['insta_link'] );


        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_instagram_section_info()
    {
        print 'Enter the Instagram Hashtag and number of images:';
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter all your Instagram Client settings here inorder to make requests to the Instagram API:';
    }

    public function igag_setting_info()
    {
        print 'Enter all your Instagram settings here:';
    }

    /** 
     * Print the Section text
     */
    public function print_other_section_info()
    {
        print 'Miscellaneous settings:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function insta_apiKey_callback()
    {
        printf(
            '<input type="text" id="insta_apiKey" name="instagrabagram_option_name[insta_apiKey]" value="%s" />',
            isset( $this->options['insta_apiKey'] ) ? esc_attr( $this->options['insta_apiKey']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function insta_apiSecret_callback()
    {
        printf(
            '<input type="text" id="insta_apiSecret" name="instagrabagram_option_name[insta_apiSecret]" value="%s" />',
            isset( $this->options['insta_apiSecret'] ) ? esc_attr( $this->options['insta_apiSecret']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function insta_apiCallback_callback()
    {
        printf(
            '<input type="text" id="insta_apiCallback" name="instagrabagram_option_name[insta_apiCallback]" value="%s" />',
            isset( $this->options['insta_apiCallback'] ) ? esc_attr( $this->options['insta_apiCallback']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function insta_access_token_callback()
    {
        printf(esc_attr( $this->options['insta_access_token']));
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function insta_apitag_callback()
    {
        printf(
            '<input type="text" id="insta_apitag" name="instagrabagram_settings_name[insta_apitag]" value="%s" />',
            isset( $this->settings['insta_apitag'] ) ? esc_attr( $this->settings['insta_apitag']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function insta_count_callback()
    {
        printf(
            '<input type="text" id="insta_count" name="instagrabagram_settings_name[insta_count]" value="%s" />',
            isset( $this->settings['insta_count'] ) ? esc_attr( $this->settings['insta_count']) : ''
        );
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function insta_link_callback()
    {
		$linked = isset( $this->settings['insta_link'] ) ? esc_attr( $this->settings['insta_link']) : ''
		?>
		<input type="checkbox" id="insta_link" name="instagrabagram_settings_name[insta_link]" value="1" <?php checked( $linked, 1 ); ?> />
		<?php
    }


}
