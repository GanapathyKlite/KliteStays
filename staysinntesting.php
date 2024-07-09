<?php
if(isset($_GET['source'])&&isset($_GET['destination']))
{
  $res=getmapkm($_GET['source'],$_GET['destination']);
echo $res;
} 
 function getmapkm($source,$destination)
   {
    $source=trim($source);
    $destination=trim($destination);
   //$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$source."&destinations=".$destination."&mode=driving&trafficModel=TrafficModel&language=pl-EN&key=AIzaSyDl45SZcBoNlMg_cl9-LnfYNefLzPkZBuw";
   $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$source."&destinations=".$destination."&mode=driving&trafficModel=TrafficModel&language=pl-EN&key=AIzaSyDU19NE_FOBEwy3ZvRtUMdZSV5EmMWfYEI";// API key - Kiruba
   //echo $url; die;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   $response = curl_exec($ch);
   if($_SERVER['REMOTE_ADDR'] == '117.240.92.249'){
        //echo '<pre>';print_r($response);echo '</pre>';
   }
   curl_close($ch);
   $response_a = json_decode($response, true);
   $km = $response_a['rows'][0]['elements'][0]['distance']['text'];
   $dist=explode(",",$km);
   $dist=str_replace(" km","",$dist[0]);

   return $dist;
   }

?>