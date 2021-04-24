<?php
/*
- The function of this program is to Upload videos to youtube using php.
- Islamic University Of Gaza.
- Developed by: Abd Alaziz M. Alswasis.
- @2021-2022
*/
require('index.php');
if(isset($_GET['err'])){
    if($_GET['err'] == 'bf'){
        $errorMsg = 'Please select a video file to upload.';
    }elseif($_GET['err'] == 'ue'){
        $errorMsg = 'Sorry, there was an error on uploading your file.';
    }elseif($_GET['err'] == 'fe'){
        $errorMsg = 'Sorry, only MP4, AVI, MPEG, MPG, MOV and WMV files are allowed.';
    }else{
        $errorMsg = 'Some problems occurred, please try again.';
    }
}
$valueis = $_SESSION[$tokenSessionKey];
//echo '<pre>'; print_r($valueis); echo '</pre>';
?>
<!DOCTYPE html>
<html>
<head>
<title>Upload video to YouTube</title>
<link href="http://hayageek.github.io/jQuery-Upload-File/uploadfile.min.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://hayageek.github.io/jQuery-Upload-File/jquery.uploadfile.min.js"></script>  
</head>
<body>
 <div id="fileuploader">Upload</div>
  <img class="img" src="" alt="">
<script>

$("#fileuploader").uploadFile({
	url: "upload.php",
    method: "POST",
    //allowedTypes:"jpg", //,png,gif,doc,pdf,zip
    fileName: "file",
	dragDrop:true,
    multiple: true,
    autoSubmit: true,
	showFileCounter:false,
	showFileSize: true,
	maxFileCount:30,
    showStatusAfterSuccess:true,
    onSuccess:function(files,data,xhr) {
        //alert( data );
		 //$("#resultfromserver1").html(data);
		 
		 div = $("<div>").html(data);
		 $("#resultfromserver1").prepend(div);
		 div2 = $("<div>").text("************************************************************************");
		 $("#resultfromserver1").prepend(div2);
    },error : function () {
           alert("error");
        }
});
</script>
<div id="resultfromserver1"><div>
<script>
      var elem = document.getElementById("videostart");
      elem.remove();
	//document.getElementsByClassName('video-box')[0].style.visibility = 'hidden';
</script> 
</body>
</html>
