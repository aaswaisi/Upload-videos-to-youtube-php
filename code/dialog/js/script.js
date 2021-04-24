
// Setup
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