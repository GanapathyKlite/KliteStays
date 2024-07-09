<?php
session_start();
include('include/database/config.php');
//print_r($_SESSION); exit();
$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));
//$get_logintime=$database->query("select logintime from loginandaccessreportagent1 where id='".$_SESSION['curtrackid']."'")->fetchAll();
function get_time_difference($time1, $time2) {
    $time1 = strtotime("1980-01-01 $time1");
    $time2 = strtotime("1980-01-01 $time2");
    
    if ($time2 < $time1) {
        $time2 += 86400;
    }
    
    return date("H:i:s", strtotime("1980-01-01 00:00:00") + ($time2 - $time1));
}
$diff= get_time_difference($get_logintime[0][0], $date->format('h:i:s'));
//$store_details_report=$database->query("update loginandaccessreportagent1 set logouttime='".$date->format('h:i:s')."',duration='".$diff."' where id='".$_SESSION['curtrackid']."'")->fetchAll();
//print_r($_SESSION); exit();
if(isset($_SESSION['username'])){
	$_SESSION['username'] = '';
	unset($_SESSION['username']);

}
elseif(isset($_SESSION['super_user'])){
	$_SESSION['super_user'] = '';
	unset($_SESSION['super_user']);	
}
elseif(isset($_SESSION['superadmin'])){
	$_SESSION['superadmin'] = '';
	unset($_SESSION['superadmin']);	
}
session_destroy();
header("Location:index.php");


/*if(isset($_SESSION['user'])){
    unset($_SESSION['user']);   
}
else if(isset($_SESSION['super_user'])){
    unset($_SESSION['super_user']);   
}

session_destroy();
*//*else{
    $_SESSION['user'] = '';
    unset($_SESSION['user']);
}*/
/*    echo "<script> window.location.href = 'index.php'</script>";
*/?>
