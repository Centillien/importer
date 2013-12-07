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
        $pages = dirname(__FILE__) . '/pages/importer';

         if (!isset($page[0])) {
                $page[0] = 'all';
        }

        switch ($page[0]) {
                case "gmail":
                        include "$pages/gmail.php";
                        break;
               case "linkedin":
                        include "$pages/linkedin.php";
                        break;
               case "all":
                        include "$pages/all.php";
                        break;
        }
        return true;
}
