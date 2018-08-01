<?php
print_r($_GET);
require '../facebook-php-sdk/src/facebook.php';
 
$config = array();
$config['appId'] = '168770143753798';
$config['secret'] = '06f4b66fbf6388785131d631cdeb9d4b';
$config['fileUpload'] = false; // optional
$fb = new Facebook($config);
 
$params = array(
  // this is the main access token (facebook profile)
  "access_token" => "EAACZAftx3lkYBAKowfL0NV00yZAFnXlzX2xY3aFilApR2L7d5zYarjVikQNVJ1ZAcVVFSOgqtZCdeJXZC09ZBZAHf9IAt3MW3VgRu7gjex3EWlSwQoFXVEwB55psIsZCZCSUWbwC2c1nlFrcfAkiRTCGTisrMgT2zve41rt4Q0he5xaZBX8PfZCBnyI",
  "message" => "Here is a blog post about auto posting on Facebook using PHP #php #facebook",
  "link" => "http://www.pontikis.net/blog/auto_post_on_facebook_with_php",
  "picture" => "http://i.imgur.com/lHkOsiH.png",
  "name" => "How to Auto Post on Facebook with PHP",
  "caption" => "www.pontikis.net",
  "description" => "Automatically post on Facebook with PHP using Facebook PHP SDK. How to create a Facebook app. Obtain and extend Facebook access tokens. Cron automation."
);
 
try {
  $ret = $fb->api('/me/feed', 'POST', $params);
  echo 'Successfully posted to Facebook Personal Profile';
} catch(Exception $e) {
  echo $e->getMessage();
}
?>