<?php
elgg_set_context('friends');
elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

$title = elgg_echo('import:contacts');
$body = elgg_view('importer/form');
$params = array(
         'content' => $body,
         'title' => $title,
          );
$body = elgg_view_layout('one_sidebar', $params);
echo elgg_view_page($title, $body);
