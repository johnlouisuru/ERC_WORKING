<!DOCTYPE html>
<html lang="en">

<?php 
require("db/conn.php");

require('head.php');
require('fb_time_ago.php');
date_default_timezone_set('Asia/Manila');
$year_today = date("Y");
$month_today = date("F");

  ?>
<head>
  
  <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  <script>
    $(document).ready(function() {
        var q = document.getElementById("myAudio");
        
      var interval;
      function callAjax() {
        
        var y = $('#tots_logs').attr('value');
        var x = 0;
        //alert(y);
        var formData = {
              tots_logs : y
            };
        $.ajax({
              type: "POST",
              url: "notif_logs.php",
              data: formData,
              dataType: "json",
              encode: true,
            }).done(function(data) {
              var acts = $(".activity");
              //acts.empty();
              acts.prepend(data['message']);
              interval = setTimeout(callAjax, 3000);

              //alert(data['message']);
              if(data['increment'] == "yes"){
                y++;
                x = parseInt(y);
                $('#tots_logs').val(x);
                q.play();
                var sel2 = $(".toast-body");
                sel2.empty();
                sel2.append(data['message']);
                $('.toast').toast('show');
              }
              //document.getElementById("tots_logs").value = x;
            });
            event.preventDefault();
              
      }
      callAjax();

     
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

<main id="main" class="main">
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
              <div class="toast-body alert alert-info">
        </div>
      </div>
    </div>
              <!-- Toast with Placements -->

    <audio id="myAudio" controls hidden>
      <source src="notif/new_logs.mp3" type="audio/ogg">
      <source src="notif/new_logs.mp3" type="audio/mpeg">
      Your browser does not support the audio element.
    </audio>
    
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Main Dashboard </li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- PLANNED OUTAGE EVENT TODAY -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">

                <div class="card-body">
                  <h5 class="card-title"><a href="outage-planned.php">PLANNED Outage Event Today</a></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clipboard-check"></i>
                    </div>
                    <?php 
                      $check_outage = mysqli_query($db,"select * from outage_event WHERE is_completed='N' AND too='1' AND DATE(date_occ)=CURDATE()");
                    ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_outage)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- PLANNED OUTAGE EVENT TODAY -->

            <!-- unPLANNED OUTAGE EVENT TODAY -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">


                <div class="card-body">
                  <h5 class="card-title"><a href="outage-unplanned.php">UNPLANNED Outage Event Today</a></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clipboard-x"></i>
                    </div>
    <?php 
      $check_outage = mysqli_query($db,"select * from outage_event WHERE is_completed='N' AND too='2' AND DATE(date_occ)=CURDATE()");
    ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_outage)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- unPLANNED OUTAGE EVENT TODAY -->

            <!-- unPLANNED OUTAGE EVENT TODAY -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">


                <div class="card-body">
                  <h5 class="card-title"><a href="outage-unplanned.php">ON-TIME Outage Event</a></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clipboard-x"></i>
                    </div>
                    <?php 

                      $check_outage_ontime = mysqli_query($db,"SELECT * FROM outage_event where TIMESTAMPDIFF(HOUR,date_in,date_occ) < 48");
                      //echo print_r($check_outage_ontime);
                    ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_outage_ontime)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- unPLANNED OUTAGE EVENT TODAY -->

            <!-- OUTAGE EVENT OCCURRED YESTERDAY TIL TODAY -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">


                <div class="card-body">
                  <h5 class="card-title"><a href="outage-yesterday.php">Outage Event Occurred Yesterday</a></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-graph-down-arrow"></i>
                    </div>
    <?php 
      $check_outage = mysqli_query($db,"select * from outage_event WHERE is_completed='N' AND DATE(date_occ)=CURDATE() - 1");
    ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_outage)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- OUTAGE EVENT OCCURRED YESTERDAY TIL TODAY -->

            <!-- OUTAGE EVENT OCCURRED YESTERDAY TIL TODAY -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">


                <div class="card-body">
                  <h5 class="card-title"><a href="outage-unresumed.php">All Unresumed Outage Event</a></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clipboard-pulse"></i>
                    </div>
    <?php 
      $check_outage = mysqli_query($db,"select * from outage_event WHERE is_completed='N'");
    ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_outage)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- OUTAGE EVENT OCCURRED YESTERDAY TIL TODAY -->

            <!-- OUTAGE EVENT OCCURRED THIS MONTH -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">


                <div class="card-body">
                  <h5 class="card-title"><a href="outage-this-month.php">All Outage Event This <?=date("F")?></a></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-calendar-check"></i>
                    </div>
                      <?php 
                        $check_outage = mysqli_query($db,"select * from outage_event WHERE MONTH(date_occ) = MONTH(now()) AND YEAR(date_occ) = YEAR(now())");
                      ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_outage)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- OUTAGE EVENT OCCURRED THIS MONTH -->

            <!-- OUTAGE EVENT OCCURRED TIL TODAY -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">


                <div class="card-body">
                  <h5 class="card-title">All Outage Event This Year</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-calendar4-week"></i>
                    </div>
    <?php 

      $check_outage = mysqli_query($db,"select * from outage_event WHERE YEAR(date_occ) = '$year_today'");
    ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_outage)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- OUTAGE EVENT OCCURRED TIL TODAY -->

            <!-- REGISTERED COMPANY -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">


                <div class="card-body">
                  <h5 class="card-title"><a href="gen-company.php">Registered Companies</a></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-buildings"></i>
                    </div>
    <?php 

      $check_outage = mysqli_query($db,"select * from gc_details");
    ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_outage)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- REGISTERED COMPANY -->

            <!-- REGISTERED FACILITIES -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">


                <div class="card-body">
                  <h5 class="card-title"><a href="facilities_details.php">Registered Facilities</a></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-house-gear-fill"></i>
                    </div>
    <?php 

      $check_gf = mysqli_query($db,"select * from gf_details");
    ?>
                    <div class="ps-3">
                      <h6><?=mysqli_num_rows($check_gf)?></h6>
                      <!-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> -->

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- REGISTERED FACILITIES -->

          

        

           

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- Recent Activity -->
          <div class="card">

            <div class="card-body">
              <h5 class="card-title">Recent Activity <span>| Today</span></h5>

              <div class="activity">
                <?php 
                  $get_orig_logs = mysqli_query($db,"select * from logs");
                  $orig_logs_count = mysqli_num_rows($get_orig_logs);
                  $get_logs = mysqli_query($db,"select * from logs ORDER BY date DESC LIMIT 10");
                  $logs_exist = mysqli_num_rows($get_logs);
                  if($logs_exist >= 1){
                    while($logs = mysqli_fetch_assoc($get_logs)){
                      $query_gf = mysqli_query($db,"select * from gf_details WHERE id='$logs[gf_id]'");
                        $result1 = $query_gf->fetch_assoc();  
                        $gf_name = $result1['gf_name'];

                        $query_gu = mysqli_query($db,"select * from gu_details WHERE gf_unit='$logs[gf_id]'");
                        $result2 = $query_gu->fetch_assoc();  
                        $gu_name = $result2['unit_name'];
                      if($logs['activity'] == "Submitted Outage Event"){ 
                        

                        $date_occ = date("d-M-Y H:i", strtotime($logs['date']));

                        

                        ?>
                        
                        <div class="activity-item d-flex">
                          <div class="activite-label"><?=facebook_time_ago($date_occ)?></div>
                          <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                          <div class="activity-content">
                            <?=$gf_name?> | <?=$gu_name?> |<?=$logs['activity']?> | <?=date("d-M-Y H:i", strtotime($logs['date_occ']))?>H
                          </div>
                        </div><!-- End OUTAGE item-->
                      <?php 
                      }
                      else if($logs['activity'] == "Resumed Outage Event"){ 
                        $date_occ = date("d-M-Y H:i", strtotime($logs['date']));
                        ?>
                        <div class="activity-item d-flex">
                          <div class="activite-label"><?=facebook_time_ago($date_occ)?></div>
                          <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                          <div class="activity-content">
                            <?=$gf_name?> | <?=$gu_name?> |<?=$logs['activity']?> | <?=date("d-M-Y H:i", strtotime($logs['date_res']))?>H
                          </div>
                        </div><!-- End OUTAGE item-->
                      <?php 
                      }
                    }
                  }
                  else { ?>

                    <div class="activity-content">
                            No Activity Yet.
                          </div>
                  <?php
                  }
                ?>
                <input type="hidden" value="<?=$orig_logs_count?>" id="tots_logs">

                


              </div>

            </div>
          </div><!-- End Recent Activity -->

          

          

        </div><!-- End Right side columns -->
        <div class="col-lg-12">
            <div class="row">
                <!-- WHOLE YEAR GRAPH -->
          <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Monthly Outages Events</h5>

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
                              $query_how_many = mysqli_query($db,"select * from outage_event WHERE MONTHNAME(date_occ)='$month'");
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
        <!-- PLANNED AND UNPLANNED -->
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Planned and Unplanned Column Chart</h5>

              <!-- Column Chart -->
              <div id="columnChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#columnChart"), {
                    series: [{
                      name: 'Unplanned Outages',
                      data: [
                         <?php 
                        $total_outages_overall = 0;
                        $how_many_in = 0;
                          for($i=1; $i<=12; $i++){ 
                              $month = date('F', mktime(0, 0, 0, $i, 10)); 
                              //echo $month . ","; 
                              // It will print: January,February,.............December,
                              $query_how_many = mysqli_query($db,"select * from outage_event WHERE MONTHNAME(date_occ)='$month' AND too='2'");
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
                    }, {
                      name: 'Planned Outages',
                      data: [
                        <?php 
                        $total_outages_overall = 0;
                        $how_many_in = 0;
                          for($i=1; $i<=12; $i++){ 
                              $month = date('F', mktime(0, 0, 0, $i, 10)); 
                              //echo $month . ","; 
                              // It will print: January,February,.............December,
                              $query_how_many = mysqli_query($db,"select * from outage_event WHERE MONTHNAME(date_occ)='$month' AND too='1'");
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
                    }, ],
                    chart: {
                      type: 'bar',
                      height: 350
                    },
                    plotOptions: {
                      bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                      },
                    },
                    dataLabels: {
                      enabled: false
                    },
                    stroke: {
                      show: true,
                      width: 2,
                      colors: ['transparent']
                    },
                    xaxis: {
                      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    },
                    yaxis: {
                      title: {
                        text: 'Outages'
                      }
                    },
                    fill: {
                      opacity: 1
                    },
                    tooltip: {
                      y: {
                        formatter: function(val) {
                          return " " + val + " "
                        }
                      }
                    }
                  }).render();
                });
              </script>
              <!-- End Column Chart -->

            </div>
          </div>
        </div>
        <!-- PLANNED AND UNPLANNED -->

        <!-- TYPE OF CLASS -->
         <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Chart According to its Classification</h5>

              <!-- Pie Chart -->
              <div id="pieChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#pieChart")).setOption({
                    title: {
                      text: 'Outages this <?=date("F")?> According to Classification',
                      subtext: 'Number of Outages',
                      left: 'center'
                    },
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      orient: 'vertical',
                      left: 'left'
                    },
                    series: [{
                      name: 'Outage Classification',
                      type: 'pie',
                      radius: '50%',
                      data: [
                        <?php 
                              $query_how_many_class = mysqli_query($db,"select * from outage_class");
                              if(mysqli_num_rows($query_how_many_class) >= 1){
                                  while($class = mysqli_fetch_assoc($query_how_many_class)){
                                    $outage_class = mysqli_query($db,"select * from outage_event WHERE outage_class='$class[id]' AND MONTHNAME(date_occ)='$month_today'");
                                    $how_many_per_class = mysqli_num_rows($outage_class);
                                    if($how_many_per_class == 0){

                                    }
                                    else {
                                    ?>
                                    {
                                      value: <?=$how_many_per_class?>,
                                      name: '<?=$class['class_name']?> [<?=$how_many_per_class?>]'
                                    },
                                    <?php
                                    }
                                }
                              }
                        ?>
                      ],
                      emphasis: {
                        itemStyle: {
                          shadowBlur: 10,
                          shadowOffsetX: 0,
                          shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                      }
                    }]
                  });
                });
              </script>
              <!-- End Pie Chart -->

            </div>
          </div>
        </div>
        <!-- TYPE OF CLASS -->

        <!-- TECH -->
         <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Number of Outages according to its Technology</h5>

              <!-- Line Chart -->
              <canvas id="lineChart2" style="max-height: 400px;"></canvas>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#lineChart2'), {
                    type: 'line',
                    data: {
                      labels: [
                        <?php 
                          $all_data = '';
                          $get_tech = mysqli_query($db,"select * from tech");
                          if(mysqli_num_rows($get_tech)>= 1){
                            while($row_tech = mysqli_fetch_assoc($get_tech)){
                              $query_tables = mysqli_query($db, "SELECT * FROM outage_event WHERE tech_id = '".$row_tech['id']."'");

                                  ?>
                                   '<?=$row_tech['tech_name']?>',
                                  <?php
                                  $all_data = $all_data . mysqli_num_rows($query_tables). ',';
                                
                            }
                          }
                        ?>
                        ],
                      datasets: [{
                        label: 'Technology Outages',
                        data: [<?=$all_data?>],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                      }]
                    },
                    options: {
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                });
              </script>
              <!-- End Line CHart -->

            </div>
          </div>
        </div>
        <!-- TECH -->
        <!-- <?=print_r($query_tables)?> -->

      </div>
    </section>

  </main><!-- End #main -->
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