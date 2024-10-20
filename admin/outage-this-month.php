<!DOCTYPE html>
<html lang="en">

<?php 
require("db/conn.php");
if(!@$_SESSION['is_admin'] || !@$_SESSION['admin_id']){
  header('Location: log_in.php');
  die();
}
date_default_timezone_set('Asia/Manila');

require('head.php');
require('fb_time_ago.php');

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
      <h1><span class="bi bi-eye-fill"></span> UNIT/s OUTAGE EVENT THIS [<?=date("F")?>]</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item">List</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

   


    <section class="section home">
      <div class="row">



        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <div id="success_message"></div>
              
              <h5 class="card-title">OUTAGE EVENT</h5>

             <!-- DITO LAHAT NG UNIT NA MERON SI GENERATING FACILITY -->

             <!-- General Form Elements -->
              
             <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                      <tr>
                        <th scope="col"><i class="bi bi-graph-down-arrow"></i> Total Outage Capacity (MW)</th>
                        <th scope="col"><i class="bi bi-calendar-plus"></i> Date of Occurrence</th>
                        <th scope="col"><i class="bi bi-clock"></i> Time of Occurrence</th>
                        <th scope="col"><i class="bi bi-patch-exclamation-fill"></i> Duration</th>
                        <th scope="col"><i class="bi bi-info-circle"></i> Detailed Reason for Outage</th>
                        <th scope="col"><i class="bi bi-journal-check"></i> Type of Outage</th>
                        <th scope="col"><i class="bi bi-lightning-fill"></i> Outage Classification</th>
                        <!-- <th scope="col"><i class="bi bi-pen"></i> Action</th> -->
                      </tr>
                    </thead>
                    <tbody>
              <?php 
              $date_today = date("Y-m-d");
              $year_today = date("Y");
              $month_today = date("F");
              $check_outage = mysqli_query($db,"select * from outage_event WHERE MONTH(date_occ) = MONTH(now()) AND YEAR(date_occ) = YEAR(now())");
              //$check_outage = mysqli_query($db,"select * from outage_event WHERE gf_id='$_SESSION[gf_id]' AND is_completed='N'");
              if(mysqli_num_rows($check_outage) >= 1){
                while($rows = mysqli_fetch_assoc($check_outage)){ ?>
                  <tr>
                    <td><?=$rows['total_outage']?></td>
                    
                    <td><?=date("d-M-Y", strtotime($rows['date_occ']))?></td>
                    <td><?=$rows['time_occ']?>H</td>
                    <?php 
                      $date1 = date("d-M-Y H:i", strtotime($rows['date_occ']));
                      //$date_filed = date("d-M-Y H:i", strtotime($rows2['date']));
                      

                      $time_ago = strtotime($date1);  
                      $current_time = time();  
                      $time_difference = $current_time - $time_ago;  
                      $seconds = $time_difference;  
                      $minutes = round($seconds / 60 );           // value 60 is seconds  
                      $hours   = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec  
                      if($rows['is_completed'] == 'N'){
                        $hours = 'Still on Outage';
                      }
                      else if($hours < 0){
                        $hours = 'Not yet Occurred';
                      }
                      else if($hours > 0 && $hours < 2){
                        $hours = facebook_time_ago($date1);
                      }
                      else {
                        $hours = $hours . ' Hrs';
                      }
                    ?>
                    <?php
                      if($hours >= 100){ ?>
                        <td class="text-danger"><?=$hours?></td>
                      <?php 
                      }else if($hours == "Not yet Occurred"){ ?>
                        <td class="text-success"><?=$hours?></td>
                      <?php 
                      }else if($hours == "Still on Outage"){ ?>
                        <td class="text-warning"><?=$hours?></td>
                      <?php 
                      }
                      else { ?>
                        <td class="text-info"><?=$hours?></td>
                      <?php 
                      }
                    ?>
                    
                    <td><?=$rows['reason']?></td>
                    <?php //Get the Type of Outage in Table

                    $query_too = mysqli_query($db,"select * from type_of_outage_description WHERE too_id=$rows[too] AND id=$rows[too_description]");
                    $result3 = $query_too->fetch_assoc();  
                    //$too = $result3['description'];

                    if($rows['too'] == 1){
                      $too = '<b class="text-success">Planned</b>: '.$result3['description'];
                    }
                    else {
                      $too = '<b class="text-warning">Unplanned</b>: '.$result3['description'];
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
                    <!-- <td><a href="resume_outage.php?q=<?=$unit_id_holder?>">Resume this Unit</td> -->
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