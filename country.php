<?php 

$currentpage="hotelsearch";

include("include/header.php");
error_reporting(E_ALL);
$res=explode('hotels-in-',$_GET['country']);
$country=implode(' ',explode('-',$res[1]));
$quer="SELECT l.*,c.name as cityname, s.name as statename FROM ps_country_lang l left join ps_city c on(c.id_country=l.id_country) left join ps_state s on(s.id_state=c.id_state) where l.name like '".$country."' and c.is_for_hotel=1";
$cou=$database_hotel->query($quer);

if($cou)
{
$cou=$cou->fetchAll(PDO::FETCH_ASSOC);	
}

$list=_group_by($cou,'statename');
function _group_by($array, $key) {
    $return = array();
    foreach($array as $val) {
        $return[$val[$key]][] = $val;
    }
    return $return;
}
//echo '<pre>';print_r($list);echo '</pre>';
$content='';
?>

<style>
	
	.list_of_cityes{margin-top: 110px;}
</style>
<div class="container list_of_cityes">

	<div class="div_top_inde"></div>
	<?php
	$content.='<ol >';
	foreach($list as $key=>$val)
	{
		$content.='<li class="col-sm-3" > ';
		$content.='<a href="">'.$key.'</a>';
			$content.='<ol>';
			foreach ($val as $key => $value) {
				$content.='<li><a href="">'.$value['cityname'].'</a></li>';
				
			}			
			$content.='</ol>';

		$content.='</li>';
	}
	$content.='</ol>';
echo $content;

?>
</div>
<?php
include("include/footer.php")
?>