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

  //Fix ugly outline on click
  //Credit: Vadim Ovchinnikov on Stack Overflow
    var elements = Array.from(document.querySelectorAll(".fix-outline"));

    elements.forEach(a => a.addEventListener("click", function() {
    a.classList.add("no-outline");
    }));

    elements.forEach(a => a.addEventListener("focus", function() {
    a.classList.remove("no-outline");
    }));

    //Use spacebar as click
    $('[role=button]').on("keydown",function(e){
      if(e.key == "Space" || e.key == "Enter"){
        $(this).find('.collapsible-header').click();
        e.preventDefault();
        e.stopPropagation();
      }
    });

    $('input').on("keydown",function(e){
      if(e.key == "Space" || e.key == "Enter"){
        e.stopPropagation();
      }
    });
});
