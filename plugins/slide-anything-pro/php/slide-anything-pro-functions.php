<?php
// #################################################
// ### SLIDE ANYTHING PRO PLUGIN - PHP FUNCTIONS ###
// #################################################

// ##### PLUGIN REGISTRATION HOOK - RUN WHEN THE PLUGIN IS ACTIVATED #####
function sa_pro_plugin_activation() {
	// plugin activation code to go here
}

// ##### SETTINGS PAGE - REGISTER OPTIONS PAGE #####
function sapro_register_options_page() {
	add_options_page('Slide Anything PRO', 'Slide Anything PRO', 'manage_options', 'slide-anything-pro', 'sapro_settings_page');
}

// ##### SETTINGS PAGE - REGISTER SETTINGS GROUP #####
function sapro_register_settings_group() {
	register_setting('sapro-plugin-settings', 'sap_license_key');
	register_setting('sapro-plugin-settings', 'sap_valid_license');
	register_setting('sapro-plugin-settings', 'sap_activated_timestamp');
}

// ##### SETTINGS PAGE - HTML CODE FOR SETTINGS FORM #####
function sapro_settings_page() {
	echo "<div class='wrap'>\n";
	echo "<h2 style='margin:10px 0px 20px; padding:0px;'>Slide Anything PRO Settings</h2>\n";
	// GET CURRENT SETTINGS FOR PLUGIN
	$license_key = esc_attr(get_option('sap_license_key'));
	$valid_license = esc_attr(get_option('sap_valid_license'));
	$activated_timestamp = esc_attr(get_option('sap_activated_timestamp'));
	if ($activated_timestamp == '') {
		$activated_timestamp = 0;
	}
	$error_message = '';

	// SETTINGS HAVE BEEN UPDATED - ATTEMPT TO ACTIVATE LICENSE KEY
	if (isset( $_GET['settings-updated'])) {
		// URL where the WooCommerce Software License plugin is installed
		define('SL_APP_API_URL', 'http://edgewebpages.com/index.php');
		// Software Unique ID as defined within product admin page
		define('SL_PRODUCT_ID', 'SAPRO');
		// Get domain URL of this WordPress install (minus protocol prefix)
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		define('SL_INSTANCE', str_replace($protocol, "", get_bloginfo('wpurl')));
		// PERFORM LICENSE ACTIVATION CALL (WOOCOMMERCE SOFTWARE LICENSE PLUGIN)
		$args = array(
			'woo_sl_action'		=> 'activate',
			'licence_key'			=> $license_key,
			'product_unique_id'	=> SL_PRODUCT_ID,
			'domain'					=> SL_INSTANCE
		);
		$request_uri = SL_APP_API_URL.'?'.http_build_query($args);
		$data = wp_remote_get($request_uri);
		// CHECK DATA RETURNED FROM LICENSE ACTIVATION CALL (WOOCOMMERCE SOFTWARE LICENSE PLUGIN)
		if (is_wp_error($data) || $data['response']['code'] != 200) {
			// problem establishing connection to API server
			$error_message = 'There was a problem connecting to the License Server - please try again later.';
		} else {
			$data_body_arr = json_decode($data['body']);
			$data_body = $data_body_arr[0];
			$data_status	= $data_body->status;
			$data_code		= $data_body->status_code;
			$data_message	= $data_body->message;
			if (isset($data_status)) {
				if (($data_status == 'success') && ($data_code == 's100')) {
					// the license key has been successfully activated - set plugin license to valid
					$valid_license = '1';
					$activated_timestamp = time();
					update_option('sap_valid_license', $valid_license);
					update_option('sap_activated_timestamp', $activated_timestamp);
				} elseif (($data_status == 'error') && ($data_code == 'e113')) {
					// license key is already activated for this domain - set plugin license to valid
					$valid_license = '1';
					$activated_timestamp = time();
					update_option('sap_valid_license', $valid_license);
					update_option('sap_activated_timestamp', $activated_timestamp);
				} else {
					// the license key cannot be activated - set plugin license to invalid and deactivate license
					$error_message = 'The license key provided cannot be activated. Please check that you have entered the correct license key.';
					$valid_license = '0';
					$activated_timestamp = 0;
					update_option('sap_valid_license', $valid_license);
					update_option('sap_activated_timestamp', $activated_timestamp);
				}
			} else {
				// problem establishing connection to API server
				$error_message = 'There was a problem connecting to the License Server - please try again later.';
			}
		}
	}

	// SETTINGS OPTION FORM
	echo "<form action='options.php' method='post'>\n";
	settings_fields('sapro-plugin-settings');
	do_settings_sections('sapro-plugin-settings');
	if ($error_message != '') {
		echo "<h4 style='margin:0px; padding:0px; color:crimson;'>".$error_message."</h4>";
	}
	echo "<table style='margin:30px 0px 0px'>\n";
	echo "<tr>\n";
	echo "<th align='left' style='min-width:80px;'>License Key</th>\n";
	echo "<td><input type='text' placeholder='Enter Slide Anything PRO License Key' name='sap_license_key' ";
	echo "value='".$license_key."' size='40'/></td>\n";
	echo "</tr><tr>\n";
	echo "<td colspan='2'>".get_submit_button()."</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "<input type='hidden' name='sap_valid_license' value='".$valid_license."'/>\n";
	echo "<input type='hidden' name='sap_activated_timestamp' value='".$activated_timestamp."'/>\n";
	echo "</form>\n";

	// DISPLAY ACTIVATION STATUS
	echo "<h3 style='margin:0px; padding:30px 0px 10px;'>Activation Status</h3>";
	if ($valid_license == '1') {
		echo "<h4 style='display:inline-block; margin:0px; padding:10px 15px; background:green; color:white; border-radius:5px;'>ACTIVE</h4>\n";
	} else {
		echo "<h4 style='display:inline-block; margin:0px; padding:10px 15px; background:crimson; color:white; border-radius:5px;'>INACTIVE</h4>\n";
		echo "<h3 style='margin:0px; padding:30px 0px 10px;'>Activation Instructions </h3>\n";
		echo "<ul style='list-style-type: disc; margin:0px 0px 0px 20px;'>\n";
		echo "<li>Get a your Slide Anything PRO License Key from the '<strong>Order Complete</strong>' email sent to you</li>\n";
		echo "<li>Alternatively, get a your License Key from your '<strong>My Account -> Orders</strong>' page on the '<em>EdgeWebPages</em>' ";
		echo "website by clicking <strong><a href='http://edgewebpages.com/my-account/orders/' target='_blank'>HERE</a></strong>, ";
		echo "and then clicking  the '<strong>License Manage</strong>' button for your order.</li>\n";
		echo "<li>Copy/Paste this License Key into the '<strong>License Key</strong>' text box above and click the ";
		echo "'<strong>Save Changes</strong>' button.</li>\n";
		echo "</ul>\n";
	}
	echo "</div>\n"; // .wrap
}



// ##### VALIDATE THAT LICENSE KEY FOR THIS PLUGIN EXISTS ON THE LICENSE SERVER #####
function validate_slide_anything_pro_license_key() {
	$valid = false;
	$current_timestamp = time();

	// GET CURRENT SETTINGS FOR PLUGIN
	$license_key = esc_attr(get_option('sap_license_key'));
	$valid_license = esc_attr(get_option('sap_valid_license'));
	$activated_timestamp = esc_attr(get_option('sap_activated_timestamp'));
	if ($activated_timestamp == '') {
		$activated_timestamp = 0;
	}

	// ONE DAY HAS ELAPSED SINCE LAST ACTIVATION - REACTIVATE/CHECK CURRENT LICENSE KEY
	$seconds_elapsed = $current_timestamp - $activated_timestamp;
	if ($seconds_elapsed > 86400) {
		// URL where the WooCommerce Software License plugin is installed
		define('SL_APP_API_URL', 'http://edgewebpages.com/index.php');
		// Software Unique ID as defined within product admin page
		define('SL_PRODUCT_ID', 'SAPRO');
		// Get domain URL of this WordPress install (minus protocol prefix)
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		define('SL_INSTANCE', str_replace($protocol, "", get_bloginfo('wpurl')));
		// PERFORM LICENSE ACTIVATION CALL (WOOCOMMERCE SOFTWARE LICENSE PLUGIN)
		$args = array(
			'woo_sl_action'		=> 'activate',
			'licence_key'			=> $license_key,
			'product_unique_id'	=> SL_PRODUCT_ID,
			'domain'					=> SL_INSTANCE
		);
		$request_uri = SL_APP_API_URL.'?'.http_build_query($args);
		$data = wp_remote_get($request_uri);
		// CHECK DATA RETURNED FROM LICENSE ACTIVATION CALL (WOOCOMMERCE SOFTWARE LICENSE PLUGIN)
		if (is_wp_error($data) || $data['response']['code'] != 200) {
			// problem establishing connection to API server - do nothing
		} else {
			$data_body_arr = json_decode($data['body']);
			$data_body = $data_body_arr[0];
			$data_status	= $data_body->status;
			$data_code		= $data_body->status_code;
			$data_message	= $data_body->message;
			if (isset($data_status)) {
				if (($data_status == 'success') && ($data_code == 's100')) {
					// the license key has been successfully activated - set plugin license to valid
					$valid_license = '1';
					$activated_timestamp = time();
					update_option('sap_valid_license', $valid_license);
					update_option('sap_activated_timestamp', $activated_timestamp);
				} elseif (($data_status == 'error') && ($data_code == 'e113')) {
					// license key is already activated for this domain - set plugin license to valid
					$valid_license = '1';
					$activated_timestamp = time();
					update_option('sap_valid_license', $valid_license);
					update_option('sap_activated_timestamp', $activated_timestamp);
				} else {
					// the license key cannot be activated - set plugin license to invalid and deactivate license
					$valid_license = '0';
					$activated_timestamp = 0;
					update_option('sap_valid_license', $valid_license);
					update_option('sap_activated_timestamp', $activated_timestamp);
				}
			} else {
				// problem establishing connection to API server - do nothing
			}
		}
	}

	if ($valid_license == '1') {
		$valid = true;
	}
	return $valid;
}
?>