<?php
$user_name = "root";
$password = "admin";
$database = "buddies_admin";
$server = "localhost";

$db_handle = mysql_connect($server, $user_name, $password);

$db_found = mysql_select_db($database, $db_handle);

if ($db_found) {

//stval
$sql1   = 'SELECT stval FROM cunextvalue WHERE id = 1';
$result1 = mysql_query($sql1, $db_handle);

if (!$result1) {
    echo "DB Error, could not query the database\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

while ($row = mysql_fetch_assoc($result1)) {
    $stval= $row['stval'];
	if(strlen($stval)==1){ $stval="000".$stval;}
	if(strlen($stval)==2){ $stval="00".$stval;}
	if(strlen($stval)==3){ $stval="0".$stval;}
	
}
//btsval
$sql    = 'SELECT btsval FROM btsnextvalue WHERE id = 1';
$result = mysql_query($sql, $db_handle);
if (!$result) {
    echo "DB Error, could not query the database\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}
while ($row = mysql_fetch_assoc($result)) {
    $btsval= $row['btsval'];
	if(strlen($btsval)==1){ $btsval="000".$btsval;}
	if(strlen($btsval)==2){ $btsval="00".$btsval;}
	if(strlen($btsval)==3){ $btsval="0".$btsval;}
	
}
//print "Database Found ";
mysql_close($db_handle);

}
else {

print "Database NOT Found ";

}
?>