<?php
/**
 *  @package importer plugin
 *  Licence : GPLV2
 */

$img = elgg_view('output/img', array( 'src' => 'mod/importer/graphics/import-address-book.jpg', 'width' => 230, 'height'=> 34, 'alt' => elgg_echo('fbnotify:invite')));					
 $html = '<style type="text/css">#fb-root {position:fixed; top:0; right: 50; left: 50px;}</style><div id="fb-root"></div>';
 echo $html .= elgg_view('output/url', array( 'href' => '/import',  'text' => $img,  'class' => 'invite-link')); 
