<?php

// Call set_include_path() as needed to point to your client library.

    include_once(elgg_get_plugins_path() . "importer/vendors/google/Google_Client.php");
    include_once(elgg_get_plugins_path() . "importer/vendors/google/contrib/Google_Oauth2Service.php");

$user = elgg_get_logged_in_user_entity();
$site_name =  elgg_get_site_entity()->name;

$OAUTH2_CLIENT_ID = '';
$OAUTH2_CLIENT_SECRET = '';

$client = new Google_Client();
$client->setClientId($OAUTH2_CLIENT_ID);
$client->setClientSecret($OAUTH2_CLIENT_SECRET);
$redirect = elgg_get_site_url() . 'import/gmail';
$client->setRedirectUri($redirect);

$youtube = new  Google_Oauth2Service($client);

if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
  }

  $client->authenticate();
  $_SESSION['token'] = $client->getAccessToken();
  header('Location: ' . $redirect);
}

if (isset($_SESSION['token'])) {
  $client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
  $htmlBody = '';
		//Retrieve contact address book, to be done URA ?
		//get https://www.google.com/m8/feeds/contacts/{userEmail}/full
		foreach ($addresses as $address)
			$contents.="{$address}<br >";
		$contents.="</td></tr></table><br >";
       $htmlBody .= "<h3>Your Gmail addresses usable on $site_name for user: <strong>$user->name </strong></h3><ul><br><br>";
	 
      foreach ($playlistItemsResponse['items'] as $playlistItem) {
	
	elgg_load_js('addthis_widget');
	$title = $playlistItem['snippet']['title'];	
	$desc =  urldecode(html_entity_decode(strip_tags($playlistItem['snippet']['description'])));
	$video_url = "http://www.youtube.com/watch?v=" . $playlistItem['snippet']['resourceId']['videoId'];	
	$tags = $playlistItem['snippet']['tags'];

	$htmlBody .= 'You are logged in as <strong>'. $user->name .'</strong>, choose a video and click on "Add this video" to share on Centillien<br><br>';
	$htmlBody .= "<strong>Title: </strong>";
	$htmlBody .= $playlistItem['snippet']['title'];	
	$htmlBody .= "</br><strong>Add this video: </strong><a href='/videos/add/$user->guid?title=$title&description=$desc&video_url=$video_url'>";
	$htmlBody .= "http://www.youtube.com/watch?v=";
	$htmlBody .= $playlistItem['snippet']['resourceId']['videoId'];	
	$htmlBody .= "</a></br><br><strong>Description: </strong>";
	$htmlBody .= $playlistItem['snippet']['description'];	
	$htmlBody .= "</br><br>";
	$htmlBody .= "<iframe width='425' height='350' src='http://www.youtube.com/embed/";
	$htmlBody .= $playlistItem['snippet']['resourceId']['videoId'];
	$htmlBody .= "' frameborder='0'></iframe>";
	$htmlBody .= "</br>";
    $htmlBody .= '</ul>';
 
  }

  $_SESSION['token'] = $client->getAccessToken();
 } else {
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();

   $htmlBody .= '<br><br><h3><strong>Authorization required</strong></h3>';
   $htmlBody .= '<br><p><a href="'. $authUrl .'" title="Click to retrieve access your Gmail"><img alt="Authenticate to Gmail" src="/mod/importer/graphics/gmail.jpg" width="32" style="border-width: 0px"></a> <a href="'. $authUrl .'" title="Click to retrieve your gmail address book">Click</a> on the button to allow '. $site_name .' to access to your address book from Gmail .<p>';
   $htmlBody .= '<strong>Privacy:</strong> We will not sent out messages, only to retrieve the address book. You can then select which people you want to invite.<br><br>';
    
}

$title = elgg_echo('importer:gmail');


$body = elgg_view_layout('content', array(
        'content' => $htmlBody,
        'title' => $title,
        'sidebar' => elgg_view('sidebar'),
	'filter_override' => elgg_view('nav', array('selected' => $vars['page'])),
));

echo elgg_view_page($title, $body);

?>

