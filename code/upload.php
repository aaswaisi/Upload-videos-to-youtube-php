<?php
/*
- The function of this program is to Upload videos to youtube using php.
- Islamic University Of Gaza.
- Developed by: Abd Alaziz M. Alswasis.
- @2021-2022
*/
require('index.php');
//include api config file
//require_once 'config.php';
//include database class
require_once 'DB.class.php';
//echo BASE_URL;
//create an object of database class
$db = new DB;
$indexCountFiles = 0;
$count_files = 0;
$videoName = "";
//if the form is submitted
//if(isset($_POST['videoSubmit'])){
//$_FILES["file"]["error"] != 0
    //check whether file field is not empty
    if($_FILES["file"]["name"] != ''){
	   global $indexCountFiles;
	   global $count_files;
	   global $videoName;
	   $videoName = $_FILES['file']['name'];
       //$count_files = count($_FILES['file']['name']);
       $count_files = 1;
	   //echo $count_files;
	   /////////////////////////////////
	   uploadAllFiles($indexCountFiles);
	    //echo $count_files;
		// Looping all files
    //for($i=0 ; $i<$count_files; $i++){
		//$filename = $_FILES['file']['name'][$i];		
    }else{
        header('Location:'.BASE_URL.'selectfiles.php?err=bf');
        exit;
    }
//}else{
//	$htmlBody = "<h1 style='background-color:red;'>Please, select file!!!</h1>";
//}
//global $htmlBody;
//$htmlBody = "Empty value...";
function uploadAllFiles($i){
	    global $db;
		global $client;
		global $youtube;
        global $htmlBody;
		//$db = $GLOBALS['db'];
		//$client = $GLOBALS['client'];
		//$youtube = $GLOBALS['youtube'];
		//video info
        $title = basename($_FILES["file"]["name"]); //$_POST['title'];
        $desc = "IUGAuto-upload videos"; //$_POST['description'];
        $tags = "IUGAuto-upload"; //$_POST['tags'];
		
		//file upload path		
        $fileName = str_shuffle('iugyoutube').'-'.basename($_FILES["file"]["name"]);
        $filePath = "videos/".$fileName;

        //check the file type
        $allowedTypeArr = array("video/mp4", "video/avi", "video/mpeg", "video/mpg", "video/mov", "video/wmv", "video/rm");
        if(in_array($_FILES['file']['type'], $allowedTypeArr)){
            //upload file to local server
            if(move_uploaded_file($_FILES['file']['tmp_name'], $filePath)){
                //insert video data in the database
                $insert = $db->insert($title, $desc, $tags, $fileName);

                //store db row id in the session
                $_SESSION['uploadedFileId'] = $insert;
				//echo $_SESSION['uploadedFileId'];
			}else{
                header("Location:".BASE_URL."selectfiles.php?err=ue");
                exit;
            }
        }else{
            header("Location:".BASE_URL."selectfiles.php?err=fe");
            exit;
        }
//echo '<pre>'; print_r($client); echo '</pre>';
// get uploaded video data from database
$videoData = $db->getRow($_SESSION['uploadedFileId']);
//echo '<pre>'; print_r($videoData); echo '</pre>';

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

// Check to ensure that the access token was successfully acquired.
if ($client->getAccessToken()) {
  $htmlBody = '';
  try{
    // REPLACE this value with the path to the file you are uploading.
    $videoPath = 'videos/'.$videoData['file_name'];

    if(!empty($videoData['youtube_video_id'])){
        // uploaded video data
        $videoTitle = $videoData['title'];
        $videoDesc = $videoData['description'];
        $videoTags = $videoData['tags'];
        $videoId = $videoData['youtube_video_id'];
    }else{
        // Create a snippet with title, description, tags and category ID
        // Create an asset resource and set its snippet metadata and type.
        // This example sets the video's title, description, keyword tags, and
        // video category.
        $snippet = new Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($videoData['title']);
        $snippet->setDescription($videoData['description']);
        $snippet->setTags(explode(",",$videoData['tags']));

        // Numeric video category. See
        // https://developers.google.com/youtube/v3/docs/videoCategories/list
        $snippet->setCategoryId("22");

        // Set the video's status to "public". Valid statuses are "public",

        $status = new Google_Service_YouTube_VideoStatus();
        $status->privacyStatus = "public";

        // Associate the snippet and status objects with a new video resource.
        $video = new Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        // Specify the size of each chunk of data, in bytes. Set a higher value for
        // reliable connection as fewer chunks lead to faster uploads. Set a lower
        // value for better recovery on less reliable connections.
        $chunkSizeBytes = 1 * 1024 * 1024;

        // Setting the defer flag to true tells the client to return a request which can be called
        // with ->execute(); instead of making the API call immediately.
        $client->setDefer(true);

        // Create a request for the API's videos.insert method to create and upload the video.
        $insertRequest = $youtube->videos->insert("status,snippet", $video);

        // Create a MediaFileUpload object for resumable uploads.
        $media = new Google_Http_MediaFileUpload(
            $client,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($videoPath));

        echo 'Total file size: '.truncate((filesize($videoPath)/1048576),2).'M';
		//echo '<br>';
        // Read the media file and upload it chunk by chunk.
        $status = false;
        $handle = fopen($videoPath, "rb");
		//$sendDataSize = 0;
		//*************************************************************************************************
        while (!$status && !feof($handle)) {
          $chunk = fread($handle, $chunkSizeBytes);
          $status = $media->nextChunk($chunk); 
		  //echo print_r('Send data: '.truncate(($chunkSizeBytes/1048576),2));
		  //echo '<br>';
		  //$sendDataSize = $sendDataSize + $chunkSizeBytes;
        }

        fclose($handle);
        // If you want to make other calls after the file upload, set setDefer back to false
        $client->setDefer(false);

        // update youtube video id to database
        

        // delete video file from local server
        @unlink("videos/".$videoData['file_name']);

        // uploaded video data
        $videoTitle = $status['snippet']['title'];
        $videoDesc = $status['snippet']['description'];
        $videoTags = implode(",",$status['snippet']['tags']);
        $videoId = $status['id'];
		//echo $videoId;
		$db->update($videoData['id'],$videoId);
		
		global $indexCountFiles;
	    global $count_files;
		$indexCountFiles = $indexCountFiles + 1;
		if($count_files > $indexCountFiles){
	    //echo $indexCountFiles;
	   uploadAllFiles($indexCountFiles);
	   }else{
		   global $videoName;
		   echo "<h1 style='background-color:green;'>Uploaded successfully >> ".$videoName."<h1>";
	   }
    }

    // uploaded video embed html
    $htmlBody .= "<p class='succ-msg'>Video Uploaded to YouTube</p>";
    $htmlBody .= '<embed width="400" height="315" src="https://www.youtube.com/embed/'.$videoId.'"></embed>';
    $htmlBody .= '<ul><li><b>Title: </b>'.$videoTitle.'</li>';
    $htmlBody .= '<li><b>Description: </b>'.$videoDesc.'</li>';
    $htmlBody .= '<li><b>Tags: </b>'.$videoTags.'</li></ul>';
    $htmlBody .= '<a href="logout.php">Logout & close session</a>';

  } catch (Google_Service_Exception $e) {
    $htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
		echo '<div><a href="http://localhost/upload_video_to_youtube_php/logout.php"><h3 style="background-color:yellow;">Please, Enter here to Renew Access Token<h3></a></div>';
  } catch (Google_Exception $e) {
    $htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>',
        htmlspecialchars($e->getMessage()));
    $htmlBody .= 'Please reset session <a href="logout.php">Logout</a>';
  }

  $_SESSION[$tokenSessionKey] = $client->getAccessToken();
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
  <h3>Authorization Required</h3>
  <p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
}
}
///////////////////////////////////////////////////
function truncate($val, $f="0")
{
    if(($p = strpos($val, '.')) !== false) {
        $val = floatval(substr($val, 0, $p + 1 + $f));
    }
    return $val;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload video to YouTube</title>
</head>
<body>
<div class="video-box">
    <div class="uplink"><a href="<?php echo BASE_URL; ?>">Upload more videos</a></div>
    <div class="content">
        <!-- display uploaded video info -->
        <?php echo $htmlBody; ?>
    </div>
	<div class="fileprocessing">
    </div>
</div>
</div>
<script>
      var elem = document.getElementById("videostart");
      elem.remove();
</script>
</body>
</html>