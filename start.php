<?php
	/**
	 * Elgg Contacts importer plugin GPL VERSION
	 * This plugin allows users to import contacts from email providers and social networks
	 * @license GNU2
	 */
elgg_register_event_handler('init', 'system', 'importer_init');

function importer_init() {


	elgg_register_page_handler('import', 'importer_page_handler');

	$importer_inline_icons = elgg_get_plugin_setting("importer_inline_icons","importer");
	if($importer_inline_icons == "yes") {
	elgg_extend_view('invitefriends/form', 'link', 2);
	}else{
	elgg_extend_view('invitefriends/form', 'link1', 1);
	}
	elgg_extend_view('css/elgg', 'importer/css');
	if (elgg_is_logged_in()) {
		$params = array(
			'name' => 'import',
			'text' => elgg_echo('import:contacts'),
			'href' => "import",
			'contexts' => array('friends'),
		);
		elgg_register_menu_item('page', $params);
	}
}
function importer_page_handler($page) {
	gatekeeper();
	elgg_set_context('friends');
	elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
        $pages = dirname(__FILE__) . '/pages/importer';
        switch ($page[0]) {
                case "gmail":
                        gatekeeper();
                        include "$pages/gmail.php";
                        break;
                case "default":
		        $title = elgg_echo('import:contacts');
		        $body = elgg_view('importer/form');
        		$params = array(
                		'content' => $body,
                		'title' => $title,
       			 );
        		$body = elgg_view_layout('one_sidebar', $params);
        		echo elgg_view_page($title, $body);
        }
        return true;
}
