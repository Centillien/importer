<?php
//	if (!elgg_is_active_plugin('social_login')){
//		 register_error(elgg_echo("social_login_required"));
//		 return $return;
//	}
	// load hybridauth
	$assets_base_url  = elgg_get_site_url() . "mod/elgg_social_login/";
	$assets_base_path  = elgg_get_plugins_path() . "elgg_social_login/";
    	require_once( $assets_base_path . "/vendors/hybridauth/Hybrid/Auth.php");
    	require_once( $assets_base_path . "vendors/hybridauth/Hybrid/User_Contact.php");
	
	// Restore exception handlers
	restore_error_handler();
	restore_exception_handler();


	global $CONFIG; 
	
	$provider = "LinkedIn";
	
			$config = array();
			$config["base_url"]  = $assets_base_url . 'vendors/hybridauth/';
			$config["providers"] = array();
			$config["providers"][$provider] = array();
			$config["providers"][$provider]["enabled"] = true;

			// provider application id ?
			if( elgg_get_plugin_setting( 'ha_settings_' . $provider . '_app_id', 'elgg_social_login' ) ){
				$config["providers"][$provider]["keys"]["id"] = elgg_get_plugin_setting( 'ha_settings_' . $provider . '_app_id', 'elgg_social_login' );
			}

			// provider application key ?
			if( elgg_get_plugin_setting( 'ha_settings_' . $provider . '_app_key', 'elgg_social_login' ) ){
				$config["providers"][$provider]["keys"]["key"] = elgg_get_plugin_setting( 'ha_settings_' . $provider . '_app_key', 'elgg_social_login' );
			}

			// provider application secret ?
			if( elgg_get_plugin_setting( 'ha_settings_' . $provider . '_app_secret', 'elgg_social_login' ) ){
				$config["providers"][$provider]["keys"]["secret"] = elgg_get_plugin_setting( 'ha_settings_' . $provider . '_app_secret', 'elgg_social_login' );
			}

			// if facebook
			if( strtolower( $provider ) == "facebook" ){
				$config["providers"][$provider]["display"] = "popup";
			}

  // init hybridauth
  $hybridauth = new Hybrid_Auth( $config );
 
  // try to authenticate with provider
  $adapter = $hybridauth->authenticate( "$provider" );

  // grab the user's friends list
  $user_contacts = $adapter->getUserContacts();

  //Post the page to linkedin
   $adapter->setUserStatus("I just visited this page on Centillien, A social network for business users, it shows your LinkedIn contacts http://www.centillien.com/import/linkedin");

 
  // iterate over the user friends list
  foreach( $user_contacts as $contact ){
	$body .= elgg_view('output/img', array(
                'src' => $contact->photoURL
			));
     $body .= " " . $contact->displayName . "<br />" . $contact->description . "<hr />";
  }

$title = elgg_echo('importer:linkedin');
$params = array(
         'content' => $body,
         'title' => $title,
          );
$body = elgg_view_layout('one_sidebar', $params);
echo elgg_view_page($title, $body);

  
