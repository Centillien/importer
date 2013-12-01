<?php
	/**
	 * Elgg Contacts importer plugin GPL VERSION
	 * This plugin allows users to import contacts from email providers and social networks
	 * @license GNU2
	 */
if (elgg_get_config('allow_registration')) {
	$message = elgg_echo('invitefriends:message:default', array(elgg_get_logged_in_user_entity()->username, $site->name,elgg_get_logged_in_user_entity()->name, elgg_get_logged_in_user_entity()->briefdescription));
	echo elgg_echo('import:helptext');
	include(elgg_get_plugins_path()  . "importer/vendors/OpenInviter/example.php"); 
	echo elgg_echo('import:remark', array(elgg_get_site_entity()->name));
}else {
	echo elgg_echo('invitefriends:registration_disabled');
}
?>
