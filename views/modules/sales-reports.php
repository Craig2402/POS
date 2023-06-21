 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Sales</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
              <li class="breadcrumb-item active">Sales</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
          <!-- /.col-md-6 -->

            <div class="card card-primary card-outline">
              <div class="card-header">
              
                    <button type="button" class="btn btn-default float-right" id="daterange-btn">
                      <i class="far fa-calendar-alt"></i> Date range picker
                      <i class="fas fa-caret-down"></i>
                    </button>
                  
                      <?php

                        // if(isset($_GET["inicialDate"])){

                        //   echo '<a href="views/modules/download-report.php?report=report&inicialDate='.$_GET["inicialDate"].'&finalDate='.$_GET["finalDate"].'">';

                        // }else{

                        //   echo '<a href="views/modules/download-report.php?report=report">';

                        // }         

                        ?>
                  </div>
              <div class="card-body">
                <div class="row">
                <div class="col-xs-12">

                </div>
                </div>
              </div>
            </div>
    
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
  //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

var initialDate = start.format('YYYY-MM-DD');

var finalDate = end.format('YYYY-MM-DD');

var captureRange = $("#daterange-btn span").html();

 localStorage.setItem("captureRange", captureRange);
 console.log("localStorage", localStorage);

 window.location = "index.php?route=sales-reports&initialDate="+initialDate+"&finalDate="+finalDate;

      }
    )
    /*=============================================
CANCEL DATES RANGE
=============================================*/

$(".daterangepicker.opensleft .range_inputs .cancelBtn").on("click", function(){

localStorage.removeItem("captureRange");
localStorage.clear();
window.location = "sales";
})

/*=============================================
CAPTURE TODAY'S BUTTON
=============================================*/

$(".daterangepicker.opensleft .ranges li").on("click", function(){

var todayButton = $(this).attr("data-range-key");

if(todayButton == "Today"){

  var d = new Date();
  
  var day = d.getDate();
  var month= d.getMonth()+1;
  var year = d.getFullYear();

  if(month < 10){

    var initialDate = year+"-0"+month+"-"+day;
    var finalDate = year+"-0"+month+"-"+day;

  }else if(day < 10){

    var initialDate = year+"-"+month+"-0"+day;
    var finalDate = year+"-"+month+"-0"+day;

  }else if(month < 10 && day < 10){

    var initialDate = year+"-0"+month+"-0"+day;
    var finalDate = year+"-0"+month+"-0"+day;

  }else{

    var initialDate = year+"-"+month+"-"+day;
      var finalDate = year+"-"+month+"-"+day;

  }	

    localStorage.setItem("captureRange", "Today");

    window.location = "index.php?route=sales-reports&initialDate="+initialDate+"&finalDate="+finalDate;

}

})
</script>