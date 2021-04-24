<?php
/*
- The function of this program is to Upload videos to youtube using php.
- Islamic University Of Gaza.
- Developed by: Abd Alaziz M. Alswasis.
- @2021-2022
*/
$oauthClientID = 'Your ClientID';
$oauthClientSecret = 'Your ClientSecret';
$baseURL = 'http://localhost/upload_video_to_youtube_php/';
$redirectURL = 'http://localhost/upload_video_to_youtube_php/index.php';
$logoutURL = 'http://localhost/upload_video_to_youtube_php/logout.php';

define('OAUTH2_CLIENT_ID',$oauthClientID);
define('OAUTH_CLIENT_SECRET',$oauthClientSecret);
define('REDIRECT_URL',$redirectURL);
define('BASE_URL',$baseURL);
define('LOGOUT_URL',$logoutURL);

// Include google client libraries
require_once 'google-api-php-client/vendor/autoload.php';
require_once 'google-api-php-client/src/Google/Client.php';
require_once 'google-api-php-client/vendor/google/apiclient-services/src/Google/Service/YouTube.php';
session_start();

$client = new Google_Client();
$client->setClientId(OAUTH2_CLIENT_ID);
$client->setClientSecret(OAUTH_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$client->setRedirectUri(REDIRECT_URL);

//Định nghĩ 1 object sẽ được sử dụng để thực hiện tất cả API request
$youtube = new Google_Service_YouTube($client);
$htmlBody = '';
?>