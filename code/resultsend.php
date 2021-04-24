<html>
<head>
    <!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- Bootstrap JS -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- Circle Progress Bar Animation CSS -->
<link rel="stylesheet" href="dialog/css/style.css">
	<!-- Demo CSS -->
<link rel="stylesheet" href="dialog/css/demo.css">
</head>
<body>
<div class="text-center" style="display:none;">  
     <button id="load" class="btn btn-primary">Load Progressbar!</button>
     </div>   
        <div id="ContenerDialog" class="modal js-loading-bar">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">

                <div class="progress blue">
                  <span class="progress-left">
                    <span class="progress-bar"></span>
                  </span>
                  <span class="progress-right">
                    <span class="progress-bar"></span>
                  </span>
                  <div class="progress-value">Processing ...</div>
                </div>

              </div>
            </div>
          </div>
<script>
/*
(function(){
this.$('.js-loading-bar').modal({
  backdrop: 'static',
  show: false
});
$('#load').click(function() {
  var $modal = $('.js-loading-bar'),
      $bar = $modal.find('.progress');
      $modal.modal('show');
  	  $bar.addClass('animate');
	  
  setTimeout(function() {
    $bar.removeClass('animate');
    $modal.modal('hide');
  }, 7000);
});
})(jQuery);
*/  
</script>
</body>
</html>

<?php
/*
- The function of this program is to Upload videos to youtube using php.
- Islamic University Of Gaza.
- Developed by: Abd Alaziz M. Alswasis.
- @2021-2022
*/
$dilogRun = '
<script type="text/javascript">
  var $modal = $(".js-loading-bar"),
      $bar = $modal.find(".progress");
      $modal.modal("show");
  	  $bar.addClass("animate");
</script>
';
$dilogStop = '
<script type="text/javascript">
document.getElementById("ContenerDialog").style.display = "none";
document.getElementsByClassName("modal-backdrop show")[0].style.visibility = "hidden";
</script>
';
echo $dilogRun;
$count_files = $_FILES['myfile']['name'];
echo "LOLO>>>>>>>".$count_files;
?>