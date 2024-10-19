<!DOCTYPE html>
<html lang="en">

<?php 
require("db/conn.php");
date_default_timezone_set('Asia/Manila');
if(!@$_SESSION['is_admin'] || !@$_SESSION['admin_id']){
  header('Location: log_in.php');
  die();
}


require('head.php');


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
      <h1><i class="bi bi-buildings"></i> ALL REGISTERED FACILITIES</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">
            <a href="#" onclick="javascript:history.go(-1)">Home</a></li>
          <li class="breadcrumb-item">Facilities</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

 


    <section class="section home">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Welcome, <b><?=$_SESSION['admin_email']?></b></h4>
              <hr />

             <!-- DITO LAHAT NG UNIT NA MERON SI GENERATING FACILITY -->

        
             <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                      <tr>
                        <th scope="col"><i class="bi bi-buildings"></i> Facility Name</th>
                        <th scope="col"><i class="bi bi-building-gear"></i> Total Units</th>
                        <th scope="col"><i class="bi bi-building-gear"></i> Grid</th>
                        <th scope="col"><i class="bi bi-building-gear"></i> Region</th>
                        <th scope="col"><i class="bi bi-building-gear"></i> Total Outages<br> in this Facility</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $check_all_units = mysqli_query($db,"select * from gf_details");
                    if(mysqli_num_rows($check_all_units) >= 1){
                      while($rows = mysqli_fetch_assoc($check_all_units)){ 
                        $total_fac = mysqli_query($db,"select * from gu_details WHERE gf_unit='".$rows['id']."'");
                        $fac_holder = mysqli_num_rows($total_fac);
                        $get_details = mysqli_fetch_assoc($total_fac);

                        /*$to_display = 1;
                        $facilities_holder = '';
                        if($fac_holder > 0){
                          while($all_fac = mysqli_fetch_assoc($total_fac)){
                            $facilities_holder = $facilities_holder . $fac_holder . $all_fac['gf_name'];
                            $to_display++;
                          }
                        }*/     
                        ?>
                        <!-- [<?=$to_display?>]
                        <?=$facilities_holder?> -->

                        <tr>
                          <td><a href="gc_facilities.php?q=<?=md5($rows['id'])?>"><?=$rows['gf_name']?></a></td>
                          

                          <td><center><?=$fac_holder?></center></td>
                          <?php 
                            $grid_name = mysqli_query($db,"select * from grid WHERE id=$rows[grid]");
                            $g_n = $grid_name->fetch_assoc();  
                            $gn = $g_n['grid_name'];

                            $region_name = mysqli_query($db,"select * from table_region WHERE region_id=$rows[region]");
                            $r_n = $region_name->fetch_assoc();  
                            $rn = $r_n['region_name'];
                          ?>
                          <td><?=$gn?></td>
                          <td><?=$rn?></td>
                          <?php 
                            $total_fac_o = mysqli_query($db,"select * from outage_event WHERE gf_id='".$rows['id']."' AND is_completed='Y'");
                            $fac_holder_o = mysqli_num_rows($total_fac_o);
                            //$get_details_o = mysqli_fetch_assoc($total_fac_o);
                          ?>

                           <td><?=$fac_holder_o?></td>
                          
                        </tr>
                      <?php 
                      }
                    }
                    ?>
                    </tbody>
                  </table>
                </div>
             <!-- End General Form Elements -->

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