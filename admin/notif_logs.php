<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('db/conn.php');

//session_start();
$data = [];
$data['increment'] = 'no';
/*$check_logs = mysqli_query($db,"SELECT * FROM logs ORDER BY id DESC");
$how_many_logs = mysqli_num_rows($check_logs);
echo $how_many_logs.' ->'.$_POST['tots_logs'];
die();*/
if($_POST['tots_logs'] != 0){
	$check_logs = mysqli_query($db,"SELECT * FROM logs ORDER BY id DESC");
	$how_many_logs = mysqli_num_rows($check_logs);
	$remarks = '';
	$color = '';
	$time = '';
	if($how_many_logs === $_POST['tots_logs']){
		//$data['message'] = 'No Outage Event Occured.';
		$data['increment'] = 'no';
	}
	else if($how_many_logs > $_POST['tots_logs']){ 
		$get_latest_logs = mysqli_fetch_assoc($check_logs);
		$gf_name = mysqli_query($db,"select * from gf_details WHERE id=$get_latest_logs[gf_id]");
        $gf_n = $gf_name->fetch_assoc();  
        $gfn = $gf_n['gf_name'];

        $u_name = mysqli_query($db,"select * from gu_details WHERE id=$get_latest_logs[unit_id]");
        $uname = $u_name->fetch_assoc();  
        $un = $uname['unit_name'];

        if($get_latest_logs['activity'] == "Resumed Outage Event"){
        		$color = 'success';
        		$time = date("d-M-Y H:i", strtotime($get_latest_logs['date_res']));
        }
        else {
        		$color = 'danger';
        		$time = date("d-M-Y H:i", strtotime($get_latest_logs['date_occ']));
        }
        
        $data['message'] = '<div class="activity-item d-flex"><div class="activite-label">Just Now</div><i class="bi bi-circle-fill activity-badge text-'.$color.' align-self-start"></i><div class="activity-content">'.$gfn.' | '.$un.' | '.$get_latest_logs['activity'].' | '.$time.'H</div></div>';
        $data['increment'] = 'yes';
        //$data['notif'] = 
		?>
		
		
	<?php 
	}
	//$data['message'] = '<p class="alert alert-success">'.$_POST['rank_'].' '.$_POST['fullname_'].' Successfully Added</p>'; 
}
else {
	$data['message'] = '<p class="alert alert-danger">Error 404: Contact Your Handsome Developer.</p>';
}
echo json_encode($data);
?>