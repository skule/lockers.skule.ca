$(document).ready(function(){
  $('.button-collapse').sideNav();


  //Highlight current item in sidebar
  var path =  window.location.pathname.substring(window.location.pathname.lastIndexOf("/") + 1)
  if(path == ""){
    $("ul.side-nav:not(#nav-mobile) li a[href='index.php']").parent().addClass('active');
    $("nav ul.nav-mobile li a[href='index.php']").parent().addClass('active');
  }else{
    $("ul.side-nav:not(#nav-mobile) li a[href='" + path + "']").parent().addClass('active');
    $("nav ul.nav-mobile li a[href='" + path + "']").parent().addClass('active');
  }
});
