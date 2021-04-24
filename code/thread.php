<?php
/*
- The function of this program is to Upload videos to youtube using php.
- Islamic University Of Gaza.
- Developed by: Abd Alaziz M. Alswasis.
- @2021-2022
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="http://hayageek.github.io/jQuery-Upload-File/uploadfile.min.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://hayageek.github.io/jQuery-Upload-File/jquery.uploadfile.min.js"></script>  
</head>
<body>    
  <div id="fileuploader">Upload</div>
  <img class="img" src="" alt="">
<script>

$("#fileuploader").uploadFile({
	url: "resultsend.php",
    method: "POST",
    //allowedTypes:"jpg", //,png,gif,doc,pdf,zip
    fileName: "myfile",
	dragDrop:true,
    multiple: true,
    autoSubmit: true,
	showFileCounter:false,
	showFileSize: true,
	maxFileCount:30,
    showStatusAfterSuccess:true,
    onSuccess:function(files,data,xhr) {
        //alert( data );
		 $("#resultfromserver1").text(data);
    },error : function () {
           alert("error");
        }
});
</script>
<div id="resultfromserver1"><div>
</body>
</html>