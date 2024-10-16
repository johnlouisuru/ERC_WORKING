<!DOCTYPE html>
<html lang="en">

<?php 
require("db/conn.php");
date_default_timezone_set('Asia/Manila');
if(!@$_SESSION['gc_id'] || !@$_SESSION['gf_id']){
  header('Location: log_in.php');
  die();
}


require('head.php');


  ?>
<head>
  <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  <script>
    $(document).ready(function() {
      event.preventDefault();

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
      <h1><i class="bi bi-buildings"></i> ALL UNITS</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="all_units.php">Home</a></li>
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
              <?php
               /* $date1 = "2014-05-27 01:40:00";
                $date2 = "2014-05-28 02:00:00";
                $timestamp1 = strtotime($date1);
                $timestamp2 = strtotime($date2);
                echo "Difference between two dates is " . round($hour = abs($timestamp2 - $timestamp1)/(60*60), 3) . " hour(s)";*/
                ?>
             <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                      <tr>
                        <th scope="col"><i class="bi bi-buildings"></i> Unit <br>Name</th>
                        <th scope="col"><i class="bi bi-graph-down-arrow"></i> Nameplate <br>Rating (MW)</th>
                        <!--<th scope="col"><i class="bi bi-calendar-plus"></i> Date of Occurrence</th>
                        <th scope="col"><i class="bi bi-clock"></i> Time of Occurrence</th>
                        
                        <th scope="col"><i class="bi bi-clock"></i> Time of Resumption</th> -->
                        <th scope="col"><i class="bi bi-patch-exclamation-fill"></i> Total <br>Outage Hours</th>
                        <th scope="col"><i class="bi bi-patch-exclamation-fill"></i> Number of <br>Outages</th>
                        <th scope="col"><i class="bi bi-gear"></i> Technology</th>
                        <th scope="col"><i class="bi bi-journal-check"></i> COC/PAO #</th>
                        <!-- <th scope="col"><i class="bi bi-lightning-fill"></i> Outage Classification</th> -->
                      </tr>
                    </thead>
                    <tbody>
              <?php 
              $dates_of_outage = '';
              $check_all_units = mysqli_query($db,"select * from gu_details WHERE gf_unit=$_SESSION[gf_id]");
              if(mysqli_num_rows($check_all_units) >= 1){
                while($rows = mysqli_fetch_assoc($check_all_units)){ 
                  $count_all_outages = mysqli_query($db,"select * from outage_event WHERE unit_id='$rows[id]' AND is_completed='Y'");
                  $total_hours_holder = 0;
                  $number_of_outages = 0;
                  $tempo_hour_holder = 0;
                  $dates_of_outage = '';
                  $time_each_date = 0;
                  
                    if(mysqli_num_rows($count_all_outages) >= 1){
                      while($rows2 = mysqli_fetch_assoc($count_all_outages)){
                        $timestamp1 = strtotime($rows2['date_occ']);
                        $timestamp2 = strtotime($rows2['date_res']);
                        $time_each_date = round($hour = abs($timestamp2 - $timestamp1)/(60*60), 3);
                        $dates_of_outage = $dates_of_outage . date("Y-M-d H:i", strtotime($rows2['date_occ'])).'H to ';
                        $dates_of_outage = $dates_of_outage . date("Y-M-d H:i", strtotime($rows2['date_res'])).'H ['.$time_each_date.' hrs]<br>';
                        $tempo_hour_holder = round($hour = abs($timestamp2 - $timestamp1)/(60*60), 3);
                        $total_hours_holder = $total_hours_holder + $tempo_hour_holder;
                        $number_of_outages++;
                      }
                    }
                  //ilang_outages_na($count_all_outages,$total_hours_holder, $number_of_outages, $tempo_hour_holder, $dates_of_outage);
                  
                  $q_tech = mysqli_query($db,"select * from tech WHERE id=$rows[tech]");
                    $qtech = $q_tech->fetch_assoc();  
                    $tech_name = $qtech['tech_name'];
                  ?>
                  
                  <tr>
                    <td><a href="spec_unit.php?q=<?=md5($rows['id'])?>"><?=$rows['unit_name']?></a></td>
                    <td><?=$rows["unit_rating"]?></td>
                    <?php 
                      if($total_hours_holder >= 100){ ?>
                        <td class="card-title text-danger"><?=$total_hours_holder?> Hrs</td>
                      <?php 
                      }
                      else { ?>
                        <td class="card-title text-info"><?=$total_hours_holder?> Hrs</td>
                      <?php
                      }
                    ?>
                    
                    <td><?=$number_of_outages?></td>
                    <td><?=$tech_name?></td>
                    <td><?=$rows["coc-pao"]?></td>
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