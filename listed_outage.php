<!DOCTYPE html>
<html lang="en">

<?php 
date_default_timezone_set('Asia/Manila');
require("db/conn.php");
if(!@$_SESSION['gc_id'] || !@$_SESSION['gf_id']){
  header('Location: log_in.php');
  die();
}
require('head.php');

require('all_about_date_and_time.php');

  ?>
<head>
  <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  <script>
    $(document).ready(function() {
      $('#submit_outage_event').prop('disabled',true);
        $('input:checkbox').click(function() {
          if ($(this).is(':checked')) {
           $('#submit_outage_event').prop("disabled", false);
           } else {
           if ($('.checks').filter(':checked').length < 1){
           $('#submit_outage_event').attr('disabled',true);}
           }
        });

     $('#type_of_outage').on("click", function() {
         var y = $('#type_of_outage').val();
         //alert(y);
         var formData = {
              cid : y
            };

          $.ajax({
              type: "POST",
              url: "get_type_of_outage.php",
              data: formData,
              dataType: "json",
              encode: true,
            }).done(function(data) {
              
              //$('.modal').hide();
              var sel2 = $("#outage_description");
              sel2.empty();
              sel2.append(data['message']);
             //$('.toast').toast('show');
              
            });
             event.preventDefault();
     });

     $('#submit_outage_event').on("click", function() {
      //alert(parseInt(total_outage));
      $('.modal').show();
         var too = $('#type_of_outage').val();
         var o_desc = $('#outage_description').val();
         var own_unit = $('#unit').val();
         var total_outage = $('#total_outage').val();
         var reason = $('#reason').val();
         var date_of_occ = $('#date_of_occ').val();
         var time_of_occ = $('#time_of_occ').val();
         var outage_class = $('#outage_class').val();
         if(too <= 0){
             $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Please Select Type of Outage");
              $('.toast').toast('show');
         }
         else if(o_desc <= 0){
          $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Please Select Outage Description");
              $('.toast').toast('show');
         }
         else if(own_unit <= 0){
          $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Please Select Unit");
              $('.toast').toast('show');
         }
         else if(total_outage == null || total_outage == ""){
          $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Please Input Total Outage");
              $('.toast').toast('show');
         }
         else if(date_of_occ== null || date_of_occ== ''){
          $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Please Select a Date");
              $('.toast').toast('show');
         }
         else if(time_of_occ== null || time_of_occ== ''){
          $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Please Input Time of Occurrence");
              $('.toast').toast('show');
         }
         else if(reason.length <= 3){
          $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Reason must have at least 3 characters");
              $('.toast').toast('show');
         }
         else {
              var formData = {
              too_ : too,
              o_desc_ : o_desc,
              own_unit_ : own_unit,
              total_outage_ : total_outage,
              reason_ : reason,
              date_of_occ_ : date_of_occ,
              time_of_occ_ : time_of_occ,
              outage_class_ : outage_class
            };

          $.ajax({
              type: "POST",
              url: "process_add_outage_event.php",
              data: formData,
              dataType: "json",
              encode: true,
            }).done(function(data) {
              
              if(data["message"] == "SUCCESS"){
                var sel3 = $("#success_message");
                sel3.empty();
                sel3.append('<p class="alert alert-success">New Outage Event Successfully Added. You will be redirected in 1...</p>');
                //document.getElementById("success_message").innerHTML = "";
                document.getElementById("submit_outage_event").disabled = true;
                setTimeout("window.location = 'listed_outage.php';",1000);
              }
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append(data["message"]);
              $('.toast').toast('show');
              
            });
         }
         
        
             event.preventDefault();
     });
     
     
    });
  </script>
</head>
<body>

  <?php 
    require('header.php');
  ?>

<?php 
    require('sidebar.php');

  ?>

  <!-- BODY CONTENT -->
  <main id="main" class="toggle-sidebar">

    <div class="pagetitle">
      <h1><span class="bi bi-eye-fill"></span> UNIT/s UNDER OUTAGE</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="all_units.php">Home</a></li>
          <li class="breadcrumb-item">List</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- Toast with Placements -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11" data-bs-autohide="false">
  <div id='liveToast'
  class="toast"
  role="alert"
  aria-live="assertive"
  aria-atomic="true">
              <div class="toast-header" >
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">Notification</div>
                <small id='minutes'>Just Now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body alert alert-danger">
        </div>
      </div>
    </div>
              <!-- Toast with Placements -->

              <div class="modal" id="myModal" tabindex="-1" style="position: fixed; top: 25%;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Please Wait...</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p>Please Wait while sending your request...</p>
                  <div class="col-12" id="lowding">
                    <div class="spinner-grow text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-secondary" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-danger" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-warning" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-info" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-light" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-dark" role="status">
                      <span class="visually-hidden">Loading...</span>
                    </div>
              </div>
                </div>
              </div>
            </div>
          </div>
    
              <!-- LOADING KEMERUT -->


    <section class="section home">
      <div class="row">



        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div id="success_message"></div>
              <?php 

                    $q_grid = mysqli_query($db,"select * from grid WHERE id=$_SESSION[grid]");
                    $grid = $q_grid->fetch_assoc();  
                    $gn = $grid['grid_name'];

                    $q_reg = mysqli_query($db,"select * from table_region WHERE region_id=$_SESSION[region]");
                    $reg = $q_reg->fetch_assoc();  
                    $rn = $reg['region_name'];
                    $rn_desc = $reg['region_description'];

                    $q_gcom = mysqli_query($db,"select * from gc_details WHERE id=$_SESSION[gc_id]");
                    $gcom = $q_gcom->fetch_assoc();  
                    $gc_name = $gcom['gc_name'];
                    ?>
              <h4 class="card-title text-success">Grid: <b><?=$gn?></b> | Region: <b><?=$rn?>-<?=$rn_desc?></b> Company: <b><?=$gc_name?></b></h4>
              <h4 class="card-title">Generating Facility: <b><?=$_SESSION['gf_name']?></b></h4>
              <hr />
              
             <!-- DITO LAHAT NG UNIT NA MERON SI GENERATING FACILITY -->

             <!-- General Form Elements -->
              
             <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                      <tr>
                        <th scope="col"><i class="bi bi-buildings"></i> Unit Name</th>
                        <th scope="col"><i class="bi bi-graph-down-arrow"></i> Total Outage Capacity (MW)</th>
                        <th scope="col"><i class="bi bi-calendar-plus"></i> Date of Occurrence</th>
                        <th scope="col"><i class="bi bi-clock"></i> Time of Occurrence</th>
                        <th scope="col"><i class="bi bi-patch-exclamation-fill"></i> Duration</th>
                        <th scope="col"><i class="bi bi-info-circle"></i> Detailed Reason for Outage</th>
                        <th scope="col"><i class="bi bi-journal-check"></i> Type of Outage</th>
                        <th scope="col"><i class="bi bi-lightning-fill"></i> Outage Classification</th>
                        <th scope="col"><i class="bi bi-pen"></i> Action</th>
                      </tr>
                    </thead>
                    <tbody>
              <?php 
              $check_outage = mysqli_query($db,"select * from outage_event WHERE gf_id='$_SESSION[gf_id]' AND is_completed='N'");
              if(mysqli_num_rows($check_outage) >= 1){
                while($rows = mysqli_fetch_assoc($check_outage)){ ?>
                  <tr>
                    <?php 
                    $query_unit_name = mysqli_query($db,"select * from gu_details WHERE id=$rows[unit_id]");
                    $result4 = $query_unit_name->fetch_assoc();  
                    $un = $result4['unit_name'];
                    ?>
                    <td><?=$un?></td>
                    <td><?=$rows['total_outage']?></td>
                    


                    <td><?=date_format_natin($rows['date_occ'])?></td>
                    <td><?=$rows['time_occ']?>H</td>
                    <?php 
                      $date1 = $rows['date_occ'];
                      $date2 = date("Y-M-d H:i:s");
                      //$date_filed = date("d-M-Y H:i", strtotime($rows2['date']));
                    ?>
                    
                      <?php 
                        if($rows['too'] == 1){?>
                          <td><?=$rows['planned_compliance']?> Days Ahead Prior Outage</td>
                        <?php
                        }
                        else if($rows['too'] == 2){ ?>
                          <td><?=ilang_oras_na($date1,$date2)?> Hrs</td>
                        <?php 
                        }
                      ?>
                      
                    <td><?=$rows['reason']?></td>
                    <?php //Get the Type of Outage in Table

                    $query_too = mysqli_query($db,"select * from type_of_outage_description WHERE too_id=$rows[too] AND id=$rows[too_description]");
                    $result3 = $query_too->fetch_assoc();  
                    $too = $result3['description'];

                    if($rows['too'] == 1){
                      $too = 'Planned: '.$too;
                    }
                    ?>
                    <td><?=$too?></td>
                    <?php //Get the Outage Classification in Table
                    $query_oc = mysqli_query($db,"select * from outage_class WHERE id=$rows[outage_class]");
                    $result4 = $query_oc->fetch_assoc();  
                    $oc = $result4['class_name'];
                    ?>
                    <td><?=$oc?></td>
                    <?php 
                      $unit_id_holder = md5($rows['unit_id']);
                    ?>
                    <td><a href="resume_outage.php?q=<?=$unit_id_holder?>">Resume this Unit</td>
                  </tr>
                <?php 
                }
              }
              ?>
                      
                    </tbody>
                  </table>
                </div>

             <!-- End General Form Elements -->

             <!-- <tr>
                        <th scope="row"><a href="#">#2457</a></th>
                        <td>Brandon Jacob</td>
                        <td><a href="#" class="text-primary">At praesentium minu</a></td>
                        <td>$64</td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr> -->

             <!-- end ito ng DITO LAHAT NG UNIT NA MERON SI GENERATING FACILITY -->

            </div>
          </div>
          

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- BODY CONTENT -->

  <?php 
    //require('footer.php');
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  

</body>

</html>