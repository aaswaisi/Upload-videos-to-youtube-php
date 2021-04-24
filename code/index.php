<?php
/*
- The function of this program is to Upload videos to youtube using php.
- Islamic University Of Gaza.
- Developed by: Abd Alaziz M. Alswasis.
- @2021-2022
*/
require_once 'config.php';
$htmlBody = "<h2 style='background-color:green;'>Good >> The session is connected.</h2";
// Check if an auth token exists for the required scopes
$tokenSessionKey = 'token-' . $client->prepareScopes();

if (isset($_GET['code'])) {
  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
    die('The session state did not match.');
  }

  $client->authenticate($_GET['code']);
  $_SESSION[$tokenSessionKey] = $client->getAccessToken();
  header('Location: ' . REDIRECT_URL);
}

if (isset($_SESSION[$tokenSessionKey])) {
  $client->setAccessToken($_SESSION[$tokenSessionKey]);
}
///////////////////////////////////////////////////////////////////////////////////////////////////
if ($client->getAccessToken()) {
try{	
// If you want to make other calls after the file upload, set setDefer back to false
$client->setDefer(false);
} catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
    $htmlBody .= 'Please reset session <a href="logout.php">Logout</a>';
  }

 $_SESSION[$tokenSessionKey] = $client->getAccessToken();
 //echo '<pre>'; print_r($_SESSION[$tokenSessionKey]); echo '</pre>';
 
} elseif (OAUTH2_CLIENT_ID == 'REPLACE_ME') {
  $htmlBody = <<<END
  <h3>Client Credentials Required</h3>
  <p>
    You need to set <code>\$OAUTH2_CLIENT_ID</code> and
    <code>\$OAUTH2_CLIENT_ID</code> before proceeding.
  <p>
END;
} else {
  // If the user hasn't authorized the app, initiate the OAuth flow
  $state = mt_rand();
  $client->setState($state);
  $_SESSION['state'] = $state;

  $authUrl = $client->createAuthUrl();
  $htmlBody = <<<END
  <h1 style='background-color:red;'>Authorization Required</h1>
  <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload video to YouTube</title>
</head>
<body>
<div id="videostart" class="video-box">
    <h1>Upload video to YouTube</h1>
		<?php echo $htmlBody; ?>
		<form method="post" enctype="multipart/form-data" action="selectfiles.php">
            <?php echo (!empty($errorMsg))?'<p class="err-msg">'.$errorMsg.'</p>':''; ?>
			<input name="videoSubmit" type="submit" value="Start Upload Files">
        </form>
    </div>
</div>
</div>
</body>
</html>
