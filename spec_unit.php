<!DOCTYPE html>
<html lang="en">

<?php 
require("db/conn.php");
date_default_timezone_set('Asia/Manila');
if(!@$_SESSION['gc_id'] || !@$_SESSION['gf_id']){
  header('Location: log_in.php');
  die();
}
require("head.php");
if(!empty($_GET['q'])){
  $flag = 0;
  $orig_unit_id_holder = 0;
  $get_if_q_valid = mysqli_query($db, "SELECT * FROM gu_details");

  if(mysqli_num_rows($get_if_q_valid)){
    while($valid_q = mysqli_fetch_assoc($get_if_q_valid)){
      if($_GET['q'] == md5($valid_q['id'])){
          $flag++;
          $unit_id = $valid_q['id'];
          $un = $valid_q['unit_name'];
          $unr = $valid_q['unit_rating'];
          $tech_id = $valid_q['tech'];
          $coc_pao = $valid_q['coc-pao'];
      }
    }
    
  }
  if($flag == 0){
    echo "<h1>You Are UNAUTHORIZED!</h1>";
    exit();
  }
}
else {
  echo "<h1>You Are UNAUTHORIZED!</h1>";
  exit();
}
  ?>
<head>
  <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

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
      <h1><i class="bi bi-buildings"></i> <?=$un?></h1>
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
                <table class="table table-hover">
                    <thead>
                      <tr>
                        <th scope="col"><i class="bi bi-buildings"></i> Unit <br>Name</th>
                        <th scope="col"><i class="bi bi-graph-down-arrow"></i> Nameplate <br>Rating (MW)</th>
                        <th scope="col"><i class="bi bi-patch-exclamation-fill"></i> Total <br>Outage Hours</th>
                        <th scope="col"><i class="bi bi-patch-exclamation-fill"></i> Number of <br>Outages</th>
                        <th scope="col"><i class="bi bi-gear"></i> Technology</th>
                        <th scope="col"><i class="bi bi-journal-check"></i> COC/PAO #</th>
                      </tr>
                    </thead>
                    <tbody>
              <?php 
              $dates_of_outage = '';
              $check_all_units = mysqli_query($db,"select * from gu_details WHERE gf_unit='$_SESSION[gf_id]' AND id='$unit_id'");
              if(mysqli_num_rows($check_all_units) >= 1){
                while($rows = mysqli_fetch_assoc($check_all_units)){ 
                  $count_all_outages = mysqli_query($db,"select * from outage_event WHERE unit_id='$rows[id]' AND is_completed='Y'");
                  $total_hours_holder = 0;
                  $number_of_outages = 0;
                  $tempo_hour_holder = 0;
                  $time_each_date = 0;
                  if(mysqli_num_rows($count_all_outages) >= 1){
                    while($rows2 = mysqli_fetch_assoc($count_all_outages)){
                      $timestamp1 = strtotime($rows2['date_occ']);
                      $timestamp2 = strtotime($rows2['date_res']);
                      $time_each_date = round($hour = abs($timestamp2 - $timestamp1)/(60*60), 3);
                      
                      $tempo_hour_holder = round($hour = abs($timestamp2 - $timestamp1)/(60*60), 3);
                      $total_hours_holder = $total_hours_holder + $tempo_hour_holder;
                      $number_of_outages++;
                    }
                  }
                  $q_tech = mysqli_query($db,"select * from tech WHERE id=$rows[tech]");
                    $qtech = $q_tech->fetch_assoc();  
                    $tech_name = $qtech['tech_name'];
                  ?>
                  
                  <tr>
                    <td><?=$rows['unit_name']?></td>
                    <td><?=$rows["unit_rating"]?></td>
                    <td><?=$total_hours_holder?> Hrs</td>
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

             <!-- PARA TO SA DATES OF OUTAGES -->

             <div class="table-responsive">
                <h4><i class="text text-info">Dates of Outages:</i></h4>
              <?php 
              $loops = 1;
              $check_all_units = mysqli_query($db,"select * from gu_details WHERE gf_unit='$_SESSION[gf_id]' AND id='$unit_id'");
              if(mysqli_num_rows($check_all_units) >= 1){
                while($rows = mysqli_fetch_assoc($check_all_units)){ 
                  $count_all_outages = mysqli_query($db,"select * from outage_event WHERE unit_id='$rows[id]' AND is_completed='Y' ORDER BY date_occ DESC");
                  $dates_of_outage = '';
                  $total_hours_holder = 0;
                  $tempo_hour_holder = 0;
                  if(mysqli_num_rows($count_all_outages) >= 1){
                    while($rows2 = mysqli_fetch_assoc($count_all_outages)){
                      $total_hours_holder = 0;
                      $tempo_hour_holder = 0;
                      $timestamp1 = strtotime($rows2['date_occ']);
                      $timestamp2 = strtotime($rows2['date_res']);
                      $time_each_date = round($hour = abs($timestamp2 - $timestamp1)/(60*60), 3);
                      
                      $tempo_hour_holder = round($hour = abs($timestamp2 - $timestamp1)/(60*60), 3);
                      $total_hours_holder = $total_hours_holder + $tempo_hour_holder;
                      $dates_of_outage = $dates_of_outage . "$loops.) " .date("Y-M-d H:i", strtotime($rows2['date_occ'])).'H to ';
                      $dates_of_outage = $dates_of_outage . date("Y-M-d H:i", strtotime($rows2['date_res'])).'H <b>['.$time_each_date.' </b>hrs]<br />';
                      
                       $loops++;
                    }

                  }
                  ?>
                   
                  
                <?php 
                echo $dates_of_outage;
                     
                }
              }
              ?>
                      
                   
                </div>

             <!-- end ito ng DITO LAHAT NG UNIT NA MERON SI GENERATING FACILITY -->

            </div>
          </div>
          
            <!-- WHOLE YEAR GRAPH -->
          <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Monthly Number of Outages For <b><?=$un?></b></h5>

              <!-- Line Chart -->
              <div id="lineChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#lineChart"), {
                    series: [{
                      name: "Outages",
                      data: [
                        <?php 
                        $total_outages_overall = 0;
                        $how_many_in = 0;
                          for($i=1; $i<=12; $i++){ 
                              $month = date('F', mktime(0, 0, 0, $i, 10)); 
                              //echo $month . ","; 
                              // It will print: January,February,.............December,
                              $query_how_many = mysqli_query($db,"select * from outage_event WHERE MONTHNAME(date_occ)='$month' AND unit_id='$unit_id' AND is_completed='Y'");
                              $how_many_in = mysqli_num_rows($query_how_many);
                              if($i==12){
                                echo $how_many_in;
                              }
                              else {
                                echo $how_many_in.',';
                              }
                              $total_outages_overall += $how_many_in;
                          }
                          
                        ?>
                        ]
                      
                      
                    }],
                    chart: {
                      height: 350,
                      type: 'line',
                      zoom: {
                        enabled: false
                      }
                    },
                    dataLabels: {
                      enabled: false
                    },
                    stroke: {
                      curve: 'straight'
                    },
                    grid: {
                      row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                      },
                    },
                    xaxis: {
                      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    }
                  }).render();
                });
              </script>
              <!-- End Line Chart -->
              <p class="card-title">Total Outages as of Today: <b><?=$total_outages_overall?></b></p>
            </div>
          </div>
        </div>
        <!-- WHOLE YEAR GRAPH -->
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