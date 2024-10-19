<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('db/conn.php');
require('all_about_date_and_time.php');
$data = [];
$combined_date_time = '';
$planned_compliance = 'N/A';
/*$data['message'] = "Okay pa dito.";
echo json_encode($data);
die();*/

//Check kung yung unit ay Under Outage pa.
$check_if_under_outage = mysqli_query($db, "SELECT * FROM outage_event WHERE unit_id = '$_POST[own_unit_]' AND gf_id='$_SESSION[gf_id]' AND is_completed='N'");
if(mysqli_num_rows($check_if_under_outage) >= 1){
	$data['message'] = "This Unit already in Outage Event.";
	echo json_encode($data);
	die();
}
//Check kung yung unit ay Under Outage sa Range ng oras na ilalagay
  $check_outage = mysqli_query($db, "SELECT * FROM outage_event WHERE unit_id = '$_POST[own_unit_]' AND gf_id='$_SESSION[gf_id]' AND is_completed='Y'");
  if(bawal_overlapping($_POST['date_of_occ_'],$_POST['time_of_occ_'],$check_outage) == 1){
  	$data['message'] = "Unit Resumption and Occurrence's Overlapping is Invalid.";
	echo json_encode($data);
	die();
  }
  else {

  }
//Check kung yung unit ay Under Outage sa Range ng date na ilalagay as Date of Occurrence
  $check_outage_overlap = mysqli_query($db, "SELECT * FROM outage_event WHERE unit_id = '$_POST[own_unit_]' AND gf_id='$_SESSION[gf_id]' AND is_completed='Y'");
  if(bawal_overlapping_2($_POST['date_of_occ_'],$_POST['time_of_occ_'],$check_outage_overlap) == 1){
  	$data['message'] = "Unit Occurrence is in between Existing Outage Event: Overlapping is Invalid.";
	echo json_encode($data);
	die();
  }
  else {

  }
 //Get natin ang TECH_ID ni GU_details
  $fetch_tech = mysqli_query($db, "SELECT * FROM gu_details WHERE id='$_POST[own_unit_]'");
  $gtech = $fetch_tech->fetch_assoc();  
  $g_tech = $gtech['tech'];

 //Check kung yung Planned Outage ay 10 days prior
  //$sql = mysqli_query($db, "SELECT * FROM outage_event WHERE unit_id = '$_POST[own_unit_]' AND gf_id='$_SESSION[gf_id]' AND is_completed='Y'");
  if($_POST['too_'] == 1){
  	$planned_compliance = planned_ten_days($_POST['date_of_occ_']);
  }
  
if($_POST['too_'] != 0 && strlen($_POST['o_desc_']) != 0){
	$combined_date_time = $_POST['date_of_occ_'].' '.$_POST['time_of_occ_'].':00';
	$query2 = mysqli_query($db,"INSERT INTO outage_event(gc_id,gf_id,unit_id,total_outage,date_occ,time_occ,reason,too,too_description,outage_class,is_completed,planned_compliance,tech_id)
    VALUES('$_SESSION[gc_id]','$_SESSION[gf_id]','$_POST[own_unit_]','$_POST[total_outage_]','$combined_date_time','$_POST[time_of_occ_]','$_POST[reason_]','$_POST[too_]','$_POST[o_desc_]','$_POST[outage_class_]','N','$planned_compliance','$g_tech')");

    $query_logs = mysqli_query($db,"INSERT INTO logs(gc_id,gf_id,unit_id,activity,date_occ)
    	VALUE('$_SESSION[gc_id]','$_SESSION[gf_id]','$_POST[own_unit_]','Submitted Outage Event','$combined_date_time')");
	if($query2){
   		$data['message'] = 'SUCCESS';
   		if($query_logs){
   			$data['message'] = 'SUCCESS';
   		}
   		else {
   			$data['message'] = 'SUCCESS without logs';
   		}
	}
	else {
		$data['message'] = '<p class="alert alert-danger">Error 404: Please Contact your Handsome Developer.</p>';

	}
	//$data['message'] = '<p class="alert alert-success">'.$_POST['rank_'].' '.$_POST['fullname_'].' Successfully Added</p>'; 
}
else {
	$data['message'] = '<p class="alert alert-danger">Type of Outage or Description Must be The Error.</p>';
}
echo json_encode($data);
?>