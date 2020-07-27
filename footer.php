<footer class="page-footer" style="flex-shrink: 0;">
    <div class="container ">
      <div class="row valign-wrapper">
        <div class="col s6">
          <img src="img/skule_crest.png" class="footer-img"></img>
        </div>
        <div class="col s12">
          <p class="grey-text text-lighten-4">
            A service of the University of Toronto Engineering Society. For any
            questions or concerns, please contact the President at president@skule.ca.
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

    // Initialize datepicker
    $('.datepicker').pickadate({
      format: 'dd/mm/yy'
    });

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


  var location = "";

  // Set active location
  $(".collapsible-header").on('click', function() {
    location = $(this).find('.location').text();
  })

  // Get available lockers by size and building
  $(".size").on('change', function() {
    var size = $(this).val();

    $.ajax({
      url: "fetch_lockers.php",
      method: "GET",
      data:{location:location,size:size},
      dataType: "text",
      success:function(data) {
        // Replace options of nearest locker selected
        console.log(data);
        $(this).parent().next().find('.locker').html(data);
      }
    })
  });

  });

</script>
</body>
</html>
