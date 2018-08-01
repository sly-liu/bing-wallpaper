<?php
// require codebird
require_once('codebird/codebird.php');
 
\Codebird\Codebird::setConsumerKey("xpINv7R7D1dBfd6yODp8NyPoy", "8wWjAU4IpP4IPGb9ER1UpzCzS9brArvcbhQ1eJvbXGXaadXvu3");
$cb = \Codebird\Codebird::getInstance();
$cb->setToken("941137501539917824-x3lUX5xefgTSRoV8Ojt4ExnWVifo8iL", "zn5gXzagz2ynAq0kMg9z5coKXI2um7vPcxioGusGoQG14");
 
$params = array(
  'status' => 'A red fox on the Swiss side of the Jura Mountain range https://bing.gifposter.com/wallpaper-180-SwissFoxSnow.html #bing wallpaper',
  'media[]' => 'bingImages/SwissFoxSnow_EN-US12956141356_1920x1080.jpg'
);
$reply = $cb->statuses_updateWithMedia($params);
?>