<?php
session_start();

?>
<link rel="stylesheet" href="css/awesome/css/font-awesome.min.css"/>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!--<a class="navbar-brand" href="#">Menus</a>-->
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
		<li class="active"><a href="home.php">Home <span class="sr-only">(current)</span></a></li>
		<?php
		$confirmed=array();
			include('include/database/config.php');
			$menudata=$database->query("SELECT * from menus where status=1")->fetchAll();
			foreach($menudata as $menu)
			{
				$s=''; $s1=''; $globatst=1;
				$s1.= " <li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>".$menu['menuname']."<span class='caret'></span></a><ul class='dropdown-menu'>";
		//  echo "SELECT moduleid from menumodulerelations where menuid='".$menu['id']."' and status=1";
				$findmodule=$database->query("SELECT moduleid from menumodulerelations where menuid='".$menu['id']."'")->fetchAll();
				//$checkmodule=$database->query("SELECT mainmodid from menumodulerelations where menuid='".$menu['id']."'")->fetchAll();
				//print_r($findmodule);
				
				foreach($findmodule as $fmodule)
				{
					$globatst=1;
					$verify_rights_result = $database->query("SELECT * from moduleaccessrights where moduleid='".$fmodule['moduleid']."' and userid='".$_SESSION['authtnid']."'")->fetchAll();
					foreach($verify_rights_result as $rights)
					{
						$getmodulename=$database->query("SELECT modules from modules where id='".$fmodule['moduleid']."'")->fetchAll();
						$readaccess=$rights['readaccess'];$writeaccess=$rights['writeaccess'];$editaccess=$rights['editaccess'];$deleteaccess=$rights['deleteaccess'];
						
						   $check_me_in_mainmod=$database->query("SELECT id from modules where mainmodid='".$fmodule['moduleid']."'")->fetchAll();
						   if(count($check_me_in_mainmod)>0)
						   {
							   if (in_array($fmodule['moduleid'], $confirmed))
							   {
								   
							   }
							   else{
								   
							   $s.="<li class='dropdown-submenu'><a tabindex='-1' href='#'>".$getmodulename[0][0]."</a><ul class='dropdown-menu'>";
							   foreach($check_me_in_mainmod as $modids)
								{
									//echo $modids['id'];
									if($globatst==1)
									{
										array_push($confirmed,$fmodule['moduleid']);
										$globatst=2;
									}
									$check_rights_again=$database->query("SELECT count(*) from menumodulerelations where menuid='".$menu['id']."' and moduleid='".$modids['id']."'")->fetchAll();
									if($check_rights_again[0][0]==1)
									{
									array_push($confirmed,$modids['id']);
									
									
									$getmainmodname=$database->query("SELECT modules from modules where id='".$modids['id']."'")->fetchAll();
									
									$pagename = preg_replace('/\s+/', '', $getmainmodname[0][0]);
									$s.="<li id='".$pagename."'><a href='".$pagename.".php'>".$getmainmodname[0][0]."<span class='sr-only'></span></a></li>";
									}
									
									
									
								}
								$s.="</ul></li>";
								//print_r($confirmed);
							   }
						   }
						//echo "sdfdsf";
						
							$check_module_issubmenu=$database->query("SELECT mainmodid from modules where id='".$fmodule['moduleid']."'")->fetchAll();
							if($check_module_issubmenu[0][0]!=0)
							{
								
								
								
							
							}
							else{
								if (in_array($fmodule['moduleid'], $confirmed))
							   {
								   
							   }
							   else{
								$pagename = preg_replace('/\s+/', '', $getmodulename[0][0]);
							$s.= "<li id='".$pagename."'><a href='".$pagename.".php'>".$getmodulename[0][0]."<span class='sr-only'></span></a></li>";
							   }
							}
							
						
					}
				}
				if($s!="")
				{
					echo $s1.$s."</ul></li>";
				}
				else{
					echo $s;
				}
				//echo $s."</ul></li>";
			}
			/*$menudata=$database->query("SELECT * from menus where status=1")->fetchAll();
			foreach($menudata as $menu)
			{
				$findmenu= $database->query("SELECT moduleid from menumodulerelations where menuid='".$menu['id']."'")->fetchAll();
				foreach($findmenu as $fmenus)
				{
					$verify_rights_result = $database->query("SELECT * from moduleaccessrights where moduleid='".$findmenu[0][0]."' and userid='".$_SESSION['authtnid']."'")->fetchAll();
					foreach($verify_rights_result as $rights)
					{
						$pagename = preg_replace('/\s+/', '', $menus_available['modules']);
						echo "<li id='".$pagename."'><a href='".$pagename.".php'>".$menus_available['modules']."<span class='sr-only'></span></a></li>";
					}
				}
			}*/
			
			/*$menu_result = $database->query("SELECT * from modules where status=1")->fetchAll();
			foreach($menu_result as $menus_available)
			{
				//echo "SELECT * from moduleaccessrights where moduleid='".$menus_available['id']."' and userid='".$_SESSION['authtnid']."'";
				$verify_rights_result = $database->query("SELECT * from moduleaccessrights where moduleid='".$menus_available['id']."' and userid='".$_SESSION['authtnid']."'")->fetchAll();
				
						$getmenuname= $database->query("SELECT menuname from menus where id='".$findmenu[0][0]."' and status=1")->fetchAll();
						 echo "<li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>".$getmenuname." <span class='caret'></span></a>
          <ul class='dropdown-menu'>";
				foreach($verify_rights_result as $rights)
				{
					//$readaccess=$rights['readaccess'];$writeaccess=$rights['writeaccess'];$editaccess=$rights['editaccess'];$deleteaccess=$rights['deleteaccess'];
					if($rights['readaccess']!=0)
					{
						
						$pagename = preg_replace('/\s+/', '', $menus_available['modules']);
						echo "<li id='".$pagename."'><a href='".$pagename.".php'>".$menus_available['modules']."<span class='sr-only'></span></a></li>";
					}
				}
			}
			echo "</ul></li>";*/
		?>
       
        <!--<li><a href="#">Create agent</a></li>
        <li>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hotel <span class="caret"></span></a>
			<ul class="dropdown-menu" role="menu">
				<li><a href='add_hotel.php'><span>Add Hotel</span></a></li>
				<li class="dropdown-submenu">
							<a tabindex="-1" href="#">Guest Hotel Address</a>
							<ul class="dropdown-menu">
							
							<li><a tabindex="-1" href='add_address.php'><span>Add New</span></a></li>
							</ul>
				</li>
			</ul>
		</li>-->
		<?php if($_SESSION['username']=="admin") { ?>
		  <li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-gear"></i>&nbsp;Settings<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="AccessRights.php">&nbsp;<i class="fa fa-lock"></i>&nbsp;&nbsp;Manage Rights</a></li>
				<li><a href="Menucreation.php"><i class="fa fa-tasks"></i>&nbsp;Manage Menus</a></li>
				<!--<li><a href="SubMenucreation.php"><i class="fa fa-align-left"></i>&nbsp;Manage Sub-Menus</a></li>-->
				<li><a href="Modulecreation.php"><i class="fa fa-futbol-o"></i>&nbsp;Manage Modules</a></li>
				<li><a href="ModuleMenuRelation.php"><i class="fa fa-sitemap"></i>&nbsp;Menu / Modules Relation</a></li>
				
			</ul>
		  </li>
		<?php } ?>
      </ul>
     
     
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<?php
//$cip='';
date_default_timezone_set("Asia/Kolkata");
//echo date('h:i:s');
//echo date('Y-m-d');
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
 //echo $date;
 echo store_access_details();
 function store_access_details()
 {
	 include('include/database/config.php');
	  $pname=explode(".",basename($_SERVER['PHP_SELF'])); 
	//$s= "insert into loginandaccessreport values(NULL,'".$pname[0]."','".get_client_ip()."','".date('h:i:s')."','".date('Y-m-d')."','".$_SESSION['username']."')";
	$store_details_report=$database->query("insert into loginandaccessreport values(NULL,'".$pname[0]."','".get_client_ip()."','".date('h:i:s')."','".date('Y-m-d')."','".$_SESSION['username']."')")->fetchAll();
	$s="";
	return $s;
 }
 
 ?>