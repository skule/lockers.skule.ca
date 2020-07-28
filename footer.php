<footer class="page-footer" style="flex-shrink: 0;">
    <div class="container ">
      <div class="row valign-wrapper">
        <div class="col s6">
          <img src="img/skule_crest.png" class="footer-img"></img>
        </div>
        <div class="col s12">
          <p class="grey-text text-lighten-4">
            A service of the University of Toronto Engineering Society.<br/>
            For any questions or concerns, please contact the President at
            <a href="president@skule.ca"> president@skule.ca </a>. For any
            bug reports, please contact the Webmaster at
            <a href="webmaster@skule.ca">webmaster@skule.ca</a>
          </p>
        </div>
      </div>
    </div>
    <div class="footer-copyright">
      <!-- TODO: Add copyright -->
    </div>
  </footer>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/materialize.min.js"></script>
<script src="js/chart.min.js"></script>
<script src="js/init.js"></script>

<script>
  $(document).ready(function() {
    $('.button-collapse').sideNav();
    // Initialize modal
    $('.modal').modal();

    // Initialize select list
    $('select').material_select();
    $('.dropdown-trigger').dropdown();

    // Initialize datepicker
    $('.datepicker').pickadate();
    $('.datepicker').on('mousedown',function(event){
        event.preventDefault();
    })

    $('.tooltipped').tooltip();

    // Hide messagebox after 5 second
    setTimeout(function(){
      $('#msgBox').hide();
    }, 5000);

    // Search
    $('#search').on('keyup', function() {
        var value = $(this).val();
        var patt = new RegExp(value, "i");
          $('#myTable').find('tr').each(function() {
            if (!($(this).find('td').text().search(patt) >= 0)) {
              $(this).not('.myHead').hide();
            }
            if (($(this).find('td').text().search(patt) >= 0)) {
              $(this).show();
            }
          });
        });


  function setAnchorDisabled(anchor, disabled) {
    //debugger;
    if(disabled)
      $(anchor).addClass("disabled");
    else
      $(anchor).removeClass("disabled");
  }
  var location = "";

  // Set active location
  $(".collapsible-header").on('click', function() {
    location = $(this).find('.location').text();
  })

  // Get available lockers by size and building
  $("select.size").on('change', function() {
    var size = $(this).val();
    var obj = this;
    locker = $(obj).parent().parent().parent().parent().find('select.locker'); //The select object
    //debugger;
    //First check if anything has been selected
    if(size == ""){ //If not, reset the locker and return
      locker.html("<option value=''>Select Locker</option>");
    } else {
      $.ajax({
        url: "fetch_lockers.php",
        method: "GET",
        data:{location:location,size:size},
        dataType: "text",
        success:function(data) {
          debugger;
          //Create HTML from returned data
          var dataArr = JSON.parse(data);
          var selectHTML = "";
          if(dataArr[0] == "Select Locker" && dataArr.length == 1) //This is the magic value referenced in fetch_lockers.php.
            dataArr = [];
          for(var i = 0; i < dataArr.length; i++){
            selectHTML += "<option>" + dataArr[i] + "</option>\n";
          }
          if(dataArr.length == 0){
            selectHTML = "<option value=''>None avabilable</option>";
          }
          // Replace options of nearest locker selected
          locker.html(selectHTML); //Fill the select object with the generated HTML
          $('select').material_select(); //Since we changed the select, we need to initialize it again
        }
      });
    }
    $('select').material_select(); //Since we changed the select, we need to initialize it again
    $(locker).change(); //Trigger the change event for the locker select so that it can update the button
  });

  //When a locker is selected, set the book button
  $('select.locker').on('change', function() {
    var btn = $(this).parent().parent().parent().find("a.btn");
    console.log(this.value);
    setAnchorDisabled(btn, this.value == '');
    btn[0].href = btn[0].href.substring(0,btn[0].href.lastIndexOf("=") + 1) + this.value;
  });

  });

</script>
</body>
</html>
