<?php
$site_name =  elgg_get_site_entity()->name;
$message = elgg_echo('invite:message', array(elgg_get_logged_in_user_entity()->name, $site_name,elgg_get_logged_in_user_entity()->name, elgg_get_logged_in_user_entity()->briefdescription));
$importer_name = elgg_get_plugin_setting("importer_name","importer");
$importer_code = elgg_get_plugin_setting("importer_code","importer");


$openinviter_settings=array(
'username' => $importer_name, 'private_key' => $importer_code, 'cookie_path'=>"/tmp", 'message_body'=> $message, 'message_subject'=> elgg_echo('importer:email_subject', array($site_name)), 'transport'=>"curl", 'local_debug'=>"on_error", 'remote_debug'=>"", 'hosted'=>"", 'proxies'=>array(),
'stats'=>"", 'plugins_cache_time'=>"1800", 'plugins_cache_file'=>"oi_plugins.php", 'update_files'=>"1", 'stats_user'=>"", 'stats_password'=>"");
?>
