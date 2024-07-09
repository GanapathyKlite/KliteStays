<?php
error_reporting(0);
if(!$_SERVER['HTTP_X_REQUESTED_WITH']== 'XMLHttpRequest')
{
   header("Location:index.php");
}
else
{

	session_start();

	include('include/database/config.php');

	//$database_ps = new medoo('b2btraveladmin');
	$date= new DateTime('now', new DateTimeZone('Asia/Kolkata'));

	function get_client_ip()
	 {
		  $ipaddress = '';
		  if (getenv('HTTP_CLIENT_IP'))
			  $ipaddress = getenv('HTTP_CLIENT_IP');
		  else if(getenv('HTTP_X_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		  else if(getenv('HTTP_X_FORWARDED'))
			  $ipaddress = getenv('HTTP_X_FORWARDED');
		  else if(getenv('HTTP_FORWARDED_FOR'))
			  $ipaddress = getenv('HTTP_FORWARDED_FOR');
		  else if(getenv('HTTP_FORWARDED'))
			  $ipaddress = getenv('HTTP_FORWARDED');
		  else if(getenv('REMOTE_ADDR'))
			  $ipaddress = getenv('REMOTE_ADDR');
		  else
			  $ipaddress = 'UNKNOWN';

		  return $ipaddress;
	 }
	if($_POST['getmods']=="authenticateuser")
	{
		
		$result['status']='0';

			$auth_result = $database->query("SELECT * from ps_customers where username='".$_POST['username']."' OR mobile='".$_POST['username']."' and is_active=0 and is_delete=1")->fetchAll(PDO::FETCH_ASSOC);
			if(!empty($auth_result))
			{

			
				if($auth_result[0]['id_customer']!=0){

					$hash=$auth_result[0]['password'];
					if(password_verify($_POST['password'],$hash))
					{	
						
						$_SESSION['authtnid']=$auth_result[0]['id_customer'];
						$_SESSION['first_name']=$auth_result[0]["first_name"];
						$_SESSION['username']=$auth_result[0]["username"];
						$_SESSION['reference']=$auth_result[0]["reference"];
						$result['status']='1';
					}
					
					
					
				} else {
					$_SESSION['authtnid']=0;
					$result='0';
				}
			}
			if(($_POST['search']==1)&&($result==1))
			{
				header("Refresh:0");
			}
			echo json_encode($result);
			die();
		
	}
	
	
	
}

?>