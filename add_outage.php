<!DOCTYPE html>
<html lang="en">

<?php 
require("db/conn.php");
if(!@$_SESSION['gc_id'] || !@$_SESSION['gf_id']){
  header('Location: log_in.php');
  die();
}
require('head.php');

  ?>
<head>
    <style>
          .timepicker {
            border: 1px solid rgb(163, 175, 251);
            text-align: center;
            display: inline;
            border-radius: 4px;
            padding:2px;
        }

            .timepicker .hh, .timepicker .mm {
                width: 135px;
                outline: none;
                border: none;
                text-align: center;
            }

            .timepicker.valid {
                border: solid 1px springgreen;
            }

            .timepicker.invalid {
                border: solid 1px red;
            }

    </style>

  <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

  <script>

    $(document).ready(function() {

      $(".hh").blur(function () {
            if ($(this).val() >= 24)
                $(this).val($(this).val() % 24);

            if ($(this).val() == "")
                $(this).val("");
            else
                if ($(this).val() < 10)
                    $(this).val("0" + parseInt($(this).val()));
      validateTime(x);
        });
        $(".mm").blur(function () {
            if ($(this).val() >= 60)
                $(this).val($(this).val() % 60);

            if ($(this).val() == "")
                $(this).val("");
            else
                if ($(this).val() < 10)
                    $(this).val("0" + parseInt($(this).val()));

            var x = $(this).parent().attr("class").split(" ")[1];
            validateTime(x);
        });

        $(".hh").on("input", function () {
            $(this).parent().removeClass("invalid").removeClass("valid");
            if ($(this).val().length == 2)
                $(this).siblings(".mm").focus().select();
        });
        $(".mm").on("input", function () {
            $(this).parent().removeClass("invalid").removeClass("valid");
            if ($(this).val().length == 2)
                $(this).blur();
        });
    $(".hh").on("focus", function () {
            $(this).parent().removeClass("invalid").removeClass("valid");
        });
    $(".mm").on("focus", function () {
            $(this).parent().removeClass("invalid").removeClass("valid");
        });

        function getTime(x) {
            var t = $(".timepicker." + x).find(".hh").val() + ":" + $(".timepicker." + x).find(".mm").val();
      var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(t);
      var res = t ;
            if (!isValid)
                res = null;
            return res;
        }

        function validateTime(x) {
      var t = $(".timepicker." + x).find(".hh").val() + ":" + $(".timepicker." + x).find(".mm").val();
            var isValid = /^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$/.test(t);
            if (isValid) {
                $(".timepicker." + x).removeClass("invalid").addClass("valid");
            } else {
                $(".timepicker." + x).removeClass("valid").addClass("invalid");
            }

        }

        function setTime(x, t) {
            $(".timepicker." + x).children(".hh").val(t.substring(0, 2));
            $(".timepicker." + x).children(".mm").val(t.substring(3, 5));
            validateTime(x);
        }
    
    $(".btnGetTime").click(function(){
      alert(getTime("timepicker1"));
    });
    
    $(".btnSetTime").click(function(){
      setTime("timepicker1" , "10:35");
    });
    
    $("html").on('input' , ".N" , function () {
      $(this).val($(this).val().replace(/[^0-9.]/g, ""));
    });

    //end of 24-hour format


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
            /*var date_of_occ = $('#date_of_occ').val();
            alert(date_of_occ);*/
          $.ajax({
              type: "POST",
              url: "get_type_of_outage.php",
              data: formData,
              dataType: "json",
              encode: true,
            }).done(function(data) {
              //$('.modal').hide();
              var selo = $("#outage_description");
              selo.empty();
              selo.append(data['message']);
             //$('.toast').toast('show');
              
            });
             event.preventDefault();
     });

     $('#submit_outage_event').on("click", function() {
      //alert(parseInt(total_outage));
      //$('.modal').show();
         var too = $('#type_of_outage').val();
         var o_desc = $('#outage_description').val();
         var own_unit = $('#unit').val();
         var total_outage = $('#total_outage').val();
         var reason = $('#reason').val();
         var date_of_occ = $('#date_of_occ').val();
         //alert(date_of_occ);
         var time_hh = $('.hh').val();
         var time_mm = $('.mm').val();
         var time_of_occ = time_hh+':'+time_mm;
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
         else if(time_hh == null || time_hh == ''){
          $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Please Input Hour Time of Occurrence");
              $('.toast').toast('show');
         }
         else if(time_mm == null || time_mm == ''){
          $('.modal').hide();
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append("Please Input Minute Time of Occurrence");
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
              $('.modal').hide();
              var sel3 = $("#success_message");
              sel3.empty();
              if(data["message"] == "SUCCESS"){
                
                sel3.append('<p class="alert alert-success">New Outage Event Successfully Added. You will be redirected in 1...</p>');
                //document.getElementById("success_message").innerHTML = "";
                document.getElementById("submit_outage_event").disabled = true;
                setTimeout("window.location = 'listed_outage.php';",3000);
              }
              else {
                    sel3.append('<p class="alert alert-danger">'+data["message"]+'</p>');
              }
              var sel2 = $(".toast-body");
              sel2.empty();
              sel2.append(data["message"]);
              $('.toast').toast('show');
              //sel3.append(data["message"]);
              
            }).fail(function() {
              alert(data["message"]);
            })
            .always(function() {
              alert(data["message"]);
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
      <h1><i class="bi bi-lightning-fill"></i> ADD OUTAGE EVENT </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Add New</li>
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
              <div class="toast-body alert alert-info">
        </div>
      </div>
    </div>
              <!-- Toast with Placements -->

              <div class="modal" id="myModal" tabindex="-1" style="position: fixed; top: 25%;">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Standby while your request is still processing...</h5>
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
              

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Type of Outage</label>
                  <div class="col-sm-10">
                    <select class="form-select" id="type_of_outage" aria-label="Type of Outage">
                      <option value="0" selected>Select Type of Outage</option>
                    <?php 
                      $type_of_outage = mysqli_query($db, "SELECT * FROM type_of_outage");
                      if(mysqli_num_rows($type_of_outage)){
                          while($too = mysqli_fetch_assoc($type_of_outage)){ ?>
                            <option value="<?=$too['id']?>"><?=$too['outage_type']?></option>
                      
                          <?php 
                        }
                      }
                    ?>
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Outage Description</label>
                  <div class="col-sm-10">
                    <select id="outage_description" class="form-select" aria-label="Outage Description">
                   
                    </select>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Select Unit</label>
                  <div class="col-sm-10">
                    <select class="form-select" id="unit" aria-label="Unit">
                      <option value="0">Please Select Unit</option>
                    <?php 
                      $fetch_unit = mysqli_query($db, "SELECT * FROM gu_details WHERE gf_unit='$_SESSION[gf_id]'");
                      if(mysqli_num_rows($fetch_unit)){
                          while($unit_id = mysqli_fetch_assoc($fetch_unit)){
                          $check_outage = mysqli_query($db,"select * from outage_event WHERE unit_id='$unit_id[id]' AND is_completed='N'");
                         if(mysqli_num_rows($check_outage)>= 1){ ?>
                          <option value="0" disabled><?=$unit_id['unit_name']?> (Still on Outage)</option>
                          <?php
                          }else {
                           ?>
                            <option value="<?=$unit_id['id']?>"><?=$unit_id['unit_name']?></option>
                            
                          <?php 
                          } 
                        }
                      }
                      else { ?>
                        <option value="0">No Unit Registered Yet.</option>
                      <?php
                      }
                    ?>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Total Outage (MW)</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" id="total_outage" placeholder="Value must be in 3 decimal places... (Ex. 10.234)">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputDate" class="col-sm-2 col-form-label">Date of Occurrence </label>
                  <div class="col-sm-10">
                    <input type="date" id="date_of_occ" class="form-control">
                  </div>
                </div>
               <!--  <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Time of Occurrence (24-Hour)</label>
                  <div class="col-sm-10">
                    <input type="number" id="time_of_occ" class="form-control">
                  </div>
                </div> -->
                
                <div class="row mb-3">
                  <label for="inputNumber" class="col-sm-2 col-form-label">Time of Occurrence (24-Hour)</label>
                  <div class="col-sm-10">
                     <div class="timepicker timepicker1" dir="ltr">
                      <input type="text" class="hh N" min="0" max="23" placeholder="hh" maxlength="2" />:
                      <input type="text" class="mm N" min="0" max="59" placeholder="mm" maxlength="2" />
    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Detailed Reason for Outage</label>
                  <div class="col-sm-10">
                    <textarea class="form-control" id="reason" style="height: 100px" placeholder="Insert Detailed Reason for Outage here..."></textarea>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Select Outage Classification</label>
                  <div class="col-sm-10">
                    <select id="outage_class" class="form-select" aria-label="Type of Outage">
                    <?php 
                      $fetch_class = mysqli_query($db, "SELECT * FROM outage_class");
                      if(mysqli_num_rows($fetch_class)){
                          while($class_id = mysqli_fetch_assoc($fetch_class)){ ?>
                            <option value="<?=$class_id['id']?>"><?=$class_id['class_name']?></option>
                          <?php 
                        }
                      }
                    ?>
                    </select>
                  </div>
                </div>

                <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="verify validationDefault04" onclick="$('#bookit').attr('disabled', !$(this).is(':checked'));" required>
                      <label class="form-check-label" for="flexCheckChecked">
                        By checking this, all details provided are correct.
                      </label>
                      <div class="invalid-feedback">By checking this, all details provided are correct.</div>
                    </div>
                    <hr />

                <div class="row mb-3">
                  <div class="col-sm-10">
                    <button type="submit" id="submit_outage_event" class="btn btn-primary" disabled>Submit Outage Event Report</button>
                  </div>
                </div>

             <!-- End General Form Elements -->

             <!-- end ito ng DITO LAHAT NG UNIT NA MERON SI GENERATING FACILITY -->

            </div>
          </div>

        </div>
      </div>
    </section>
    <script>
const timeInput = document.getElementById("timeInput");
timeInput.addEventListener("input", function () {
  const value = this.value.replace(/[^0-9]/g, "");
  if (value.length > 2) {
    this.value = value.slice(0, 2) + ":" + value.slice(2);
  }
});
</script>

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