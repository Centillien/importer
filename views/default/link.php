<?php
/**
 *  @package importer plugin
 *  Licence : GPLV2
 */

<<<<<<< HEAD
$img = elgg_view('output/img', array( 'src' => 'mod/importer/graphics/import-address-book.jpg', 'width' => 230, 'height'=> 34, 'alt' => elgg_echo('fbnotify:invite')));					
 $html = '<style type="text/css">#fb-root {position:fixed; top:0; right: 50; left: 50px;}</style><div id="fb-root"></div>';
 echo $html .= elgg_view('output/url', array( 'href' => '/import',  'text' => $img,  'class' => 'invite-link')); 
=======
 $img = elgg_view('output/img', array('class' => 'email-provider-icon', 'src' => 'mod/importer/graphics/hotmail.jpg', 'width' => 64, 'height'=> 64, 'alt' => elgg_echo('importer:hotmail')));                 
 $img .= elgg_view('output/img', array('class' => 'email-provider-icon',  'src' => 'mod/importer/graphics/gmail.jpg', 'width' => 64, 'height'=> 64, 'alt' => elgg_echo('importer:gmail')));
 $img .= elgg_view('output/img', array('class' => 'email-provider-icon',  'src' => 'mod/importer/graphics/yahoo.jpg', 'width' => 64, 'height'=> 64, 'alt' => elgg_echo('importer:yahoo')));
$html = '<div class="email-provider-box">';
$html .= elgg_view('output/url', array( 'href' => '/import',  'text' => $img,  'class' => 'invite-link'));
$html .=  '<span class="email-provider-link-text">' . elgg_echo ('importer:email-provider-link') . '</span></div>';
echo $html; 

>>>>>>> 565f5ff4d69ae7672f01094825234117ec0a259c
