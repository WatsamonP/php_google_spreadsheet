function callLoading() {
  $('#loading').show();
  setTimeout(function () {
    document.location.reload(true)
  }, 100);
}