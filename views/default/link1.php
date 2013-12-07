<?php
/**
 *  @package importer plugin
 *  Licence : GPLV2
 */

 $img = elgg_view('output/img', array('class' => 'email-provider-icon', 'src' => 'mod/importer/graphics/hotmail.jpg', 'width' => 64, 'height'=> 64, 'alt' => elgg_echo('importer:hotmail')));                 
 $img .= elgg_view('output/img', array('class' => 'email-provider-icon',  'src' => 'mod/importer/graphics/gmail.jpg', 'width' => 64, 'height'=> 64, 'alt' => elgg_echo('importer:gmail')));
 $img .= elgg_view('output/img', array('class' => 'email-provider-icon',  'src' => 'mod/importer/graphics/yahoo.jpg', 'width' => 64, 'height'=> 64, 'alt' => elgg_echo('importer:yahoo')));
$html = '<div class="email-provider-box">';
$html .= elgg_view('output/url', array( 'href' => '/import',  'text' => $img,  'class' => 'invite-link'));
$html .=  '<span class="email-provider-link-text">' . elgg_echo ('importer:email-provider-link') . '</span></div>';
echo $html; 

