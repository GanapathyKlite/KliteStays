<?php
include('../include/database/config.php');
$root_dir="https://www.klitestays.com/";
//$root_dir="http://192.168.0.129:8080/klitestays/";
$xmlStringmain = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"> ';

   $city = $database->query("SELECT * from ps_city where status=0 and is_for_hotelseo=1")->fetchAll(PDO::FETCH_ASSOC);
$citypair=$city;
$xmlStrings='';
$xmlString=array();
global $starRatings;
$date=date('Y-m-d h:i:s');
foreach($citypair as $citypairk=>$citypairv)
{

	
	if(strpos($citypairv['name'],"/")!=0)
	{
		$strr=explode('/',$citypairv['name']);
		$citypairv['name']=trim($strr[0]);
	}

	$destnationName=$citypairv['name'];
	$c_names=strtolower(implode('-',explode(' ',$citypairv['name'])));
	$xmlString[]=array('url'=>$root_dir.$c_names.'/hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/apartment-in-'.$c_names.'.html','txtKeywords'=>ucwords("Apartment in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Apartment in ".$destnationName." – Best Deal you can Get upto 70% off for All Apartment in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Apartment in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/resorts-in-'.$c_names.'.html','txtKeywords'=>ucwords("Resorts in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Resorts in ".$destnationName." – Best Deal you can Get upto 70% off for All Resorts in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Resorts in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/villa-in-'.$c_names.'.html','txtKeywords'=>ucwords("Villa in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Villa in ".$destnationName." – Best Deal you can Get upto 70% off for All Villa in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Villa in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/homestays-in-'.$c_names.'.html','txtKeywords'=>ucwords("Homestays in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Homestays in ".$destnationName." – Best Deal you can Get upto 70% off for All Homestays in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Homestays in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);


	$xmlString[]=array('url'=>$root_dir.$c_names.'/dormitory-in-'.$c_names.'.html','txtKeywords'=>ucwords("Dormitory in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Dormitory in ".$destnationName." – Best Deal you can Get upto 70% off for All Dormitory in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Dormitory in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/beach-resorts-in-'.$c_names.'.html','txtKeywords'=>ucwords("Beach Resorts in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Beach Resorts in ".$destnationName." – Best Deal you can Get upto 70% off for All Beach Resorts in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Beach Resorts in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);


	$xmlString[]=array('url'=>$root_dir.$c_names.'/guest-house-in-'.$c_names.'.html','txtKeywords'=>ucwords("Guest House in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Guest House in ".$destnationName." – Best Deal you can Get upto 70% off for All Guest House in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Guest House in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);


	$xmlString[]=array('url'=>$root_dir.$c_names.'/budget-3-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("Budget 3 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Budget 3 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All Budget 3 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Budget 3 Star Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/cheap-3-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("Cheap 3 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Cheap 3 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All Cheap 3 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Cheap 3 Star Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/budget-4-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("Budget 4 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Budget 4 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All Budget 4 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Budget 4 Star Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/cheap-4-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("Cheap 4 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Cheap 4 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All Cheap 4 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Cheap 4 Star Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/1-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("1 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("1 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All 1 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('1 Star Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/2-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("2 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("2 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All 2 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('2 Star Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/3-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("3 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("3 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All 3 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('3 Star Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);
$xmlString[]=array('url'=>$root_dir.$c_names.'/4-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("4 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("4 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All 4 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('4 Star Hotels in in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$xmlString[]=array('url'=>$root_dir.$c_names.'/5-star-hotels-in-'.$c_names.'.html','txtKeywords'=>ucwords("5 Star Hotels in ".$destnationName.", Booking Hotels in ".$destnationName.", Cheap Hotels in ".$destnationName.", Best Hotel Deals in ".$destnationName.", Hotels in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("5 Star Hotels in ".$destnationName." – Best Deal you can Get upto 70% off for All 5 Star Hotels in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('5 Star Hotels in '.$destnationName.' '),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	$lmk = $database->query("SELECT * from ps_landmark where status=0 and action='' and selCityId=".$citypairv['id_city'])->fetchAll(PDO::FETCH_ASSOC);
	foreach($lmk as $k=>$v)
	{

		$near_land='';
		$near_land=strtolower(implode('-',explode(' ',$v['txtLandmark'])));
		$xmlString[]=array('url'=>$root_dir.$c_names.'/hotels-near-'.$near_land.'-in-'.$c_names.'.html','txtKeywords'=>ucwords("Hotels Near ".$v['txtLandmark']." in ".$destnationName.", Booking Hotels Near ".$v['txtLandmark']." in ".$destnationName.", Cheap Hotels Near ".$v['txtLandmark']." in ".$destnationName.", Best Hotel Deals Near ".$v['txtLandmark']." in ".$destnationName.", Hotels Near ".$v['txtLandmark']." in ".$destnationName.", ".$destnationName.", Hotels"),'txtDescription'=>ucwords("Hotels Near ".$v['txtLandmark']." in ".$destnationName." – Best Deal you can Get upto 70% off for All Hotels Near ".$v['txtLandmark']." in ".$destnationName.", Book at Klitestays.com"),'txtTitle'=>ucwords('Hotels Near '.$v['txtLandmark'].' in '.$destnationName),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$citypairv['id_state'],'id_city'=>$citypairv['id_city'],'xml_type'=>1);

	}
	

}

foreach($xmlString as $keySeo=>$valueSeo)
{
	$checkexist = $database->query("SELECT * from ps_seohotel where url='".$valueSeo['url']."'")->fetchAll(PDO::FETCH_ASSOC);

	if(empty($checkexist))
	{
		
		$seourl = $database->insert('ps_seohotel', $valueSeo);
		
	}
	/*else if(isset($checkexist[0]['id_seohotel'])&&$checkexist[0]['id_seohotel']!='')
	{
		
		$add=array('status'=>$valueSeo['status']);
		$database->update('ps_seohotel',$add,' where id_seohotel='.$checkexist[0]['id_seohotel'],1);
	}*/
}
$xmlString=array();
$property = $database->query("SELECT p.*,l.txtLandmark,c.name as cityname from ps_property p left join ps_landmark l on(l.id_landmark=p.selLandmark) left join ps_city c on(c.id_city=p.selCityId) where p.status=0 and p.is_delete=0 ")->fetchAll(PDO::FETCH_ASSOC);

$htl=array('hotel'=>1,'resort'=>2,'apartment'=>3,'villa'=>4,'homestays'=>5,'dormitory'=>6,'service-apartment'=>3,'guest-house'=>7,'beach-resorts'=>8);
foreach($property as $propertyK=>$propertyV)
{
	$starratin='';
	if(in_array($propertyV['selPropertyTypeID'],$htl))
	{
		$hotel_type=array_search($propertyV['selPropertyTypeID'],$htl);
	}
	if($propertyV['selStarRating']!='')
	{
		$starratin=$starRatings[$propertyV['selStarRating']];
	}
	$jkdfs=strtolower(implode('-',explode(' ',trim($propertyV['txtPropertyName']))));

	$txtPropertyNamesdas=str_replace("&", "&amp;",$jkdfs);
	$txtPropertylandmark=str_replace("&", "&amp;",strtolower(implode('-',explode(' ',$propertyV['txtLandmark']))));
	$destnationName=strtolower(implode('-',explode(' ',$propertyV['cityname'])));
	$xmlString[]=array('url'=>$root_dir.strtolower(implode('-',explode(' ',$propertyV['cityname']))).'/'.$hotel_type.'_'.$txtPropertyNamesdas.'-in-'.strtolower(implode('-',explode(' ',$propertyV['cityname']))).'.html','txtKeywords'=>ucwords(ucwords($hotel_type)." ".$propertyV['txtPropertyName']." , ".$propertyV['txtPropertyName']." ".ucwords($hotel_type)." in ".ucwords($destnationName).", ".$propertyV['txtPropertyName'].", ".$starratin.' '.$hotel_type." ".$propertyV['txtPropertyName']."  , ".ucwords($hotel_type)." ".$propertyV['txtPropertyName']." tariff, ".ucwords($hotel_type)." ".$propertyV['txtPropertyName']." ".$propertyV['txtLandmark'].", ".ucwords($hotel_type)." ".$propertyV['txtPropertyName']." ".$propertyV['txtAddress1']." , ".ucwords($hotel_type)." ".$propertyV['txtPropertyName']." Deals"),'txtDescription'=>ucwords(ucwords(strip_tags($propertyV['txtPropertyDescription']))." you can Get upto 70% off for Each Booking, Book Hotel ".$propertyV['txtPropertyName']." at Klitestays.com"),'txtTitle'=>ucwords($starratin.' '.$hotel_type.' '.$propertyV['txtPropertyName'].' in '.$destnationName),'date_add'=>$date,'date_upd'=>$date,'id_state'=>$propertyV['selStateId'],'id_city'=>$propertyV['selCityId'],'xml_type'=>2,'status'=>$propertyV['status']);
	
}

foreach($xmlString as $keySeo=>$valueSeo)
{
	$checkexist = $database->query("SELECT * from ps_seohotel where url='".$valueSeo['url']."'")->fetchAll(PDO::FETCH_ASSOC);
	if(empty($checkexist))
	{
		$seourl = $database->insert('ps_seohotel', $valueSeo);
	}
	else if(isset($checkexist[0]['id_seohotel'])&&$checkexist[0]['id_seohotel']!='')
	{
		$add=array('status'=>$valueSeo['status']);
		$database->update('ps_seohotel',$add,' where id_seohotel='.$checkexist[0]['id_seohotel'],1);
	}
}

$xmlString=array();
$seohotelseo = $database->query("SELECT id_state from ps_seohotel where status=0 and action='' group by id_state")->fetchAll(PDO::FETCH_ASSOC);
foreach($seohotelseo as $seohotelseok=>$seohotelseov)
{
	
$getbycity[$seohotelseov['id_state']]=$database->query("SELECT * from ps_seohotel where status=0 and action='' and  id_state=".$seohotelseov['id_state'])->fetchAll(PDO::FETCH_ASSOC);
}

/*foreach($seohotelseo as $seohotelseok=>$seohotelseov)
{
	$xmlString[]='<loc>'.str_replace("&","&amp;",$seohotelseov['url']).'</loc>';
}*/

//$xmlString= array_chunk($xmlString,40000);
$i=1;
$xmlStringsss='';
$sitemap='';

foreach($getbycity as $keyss=>$valuess)
{
	$id_state='';
	$xmlStringsss='';
	foreach($valuess as $keys=>$val)
	{

		$xmlStringsss.='<url>';
		$xmlStringsss.='<loc>'.str_replace("&","&amp;",$val['url']).'</loc>';;
		$xmlStringsss.='<lastmod>'.date('Y-m-d',strtotime($val['date_add'])).'</lastmod>';
		$xmlStringsss.='<changefreq>daily</changefreq>';
		$xmlStringsss.='<priority>1</priority>';
		$xmlStringsss.='</url>';
		
	}
	$xml='<?xml version="1.0" encoding="UTF-8"?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
	$xml.=$xmlStringsss;
	$xml.='</urlset>';
	$dom = new DOMDocument;
	$dom->preserveWhiteSpace = FALSE;
	$dom->loadXML($xml);
	$res=$dom->save('../sitemap/sitemap'.$keyss.'.xml');
	
	$xmlStringmain.='<url>';
	$xmlStringmain.='<loc>'.$root_dir.'sitemap/sitemap'.$keyss.'.xml</loc>';
	$xmlStringmain.='<lastmod>'.date('Y-m-d').'</lastmod>';
	$xmlStringmain.='<priority>1</priority>';
	$xmlStringmain.='</url>';

}

$xmlStringmain.='<url>';
$xmlStringmain.='<loc>'.$root_dir.'index.php</loc>';
$xmlStringmain.='<lastmod>'.date('Y-m-d').'</lastmod>';
$xmlStringmain.='<priority>1</priority>';
$xmlStringmain.='</url>';

/*$content_pages_static=$database->query("select * from ps_page_static where status=0 and action=''")->fetchAll(PDO::FETCH_ASSOC);

if(!empty($content_pages_static))
{
foreach($content_pages_static as $key=>$value)
{
	$xmlStringmain.='<url>';
	$xmlStringmain.='<loc>'.$root_dir.strtolower(implode('-',explode(' ',($value['txtTitle'])))).'</loc>';
	$xmlStringmain.='<lastmod>'.date('Y-m-d').'</lastmod>';
	$xmlStringmain.='</url>';
}
}*/



$xmlStringmain.='</urlset>';
$dom = new DOMDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->loadXML($xmlStringmain);
//Save XML as a file
//$sitemap = $dom->saveXML(); // put string in test1

$res=$dom->save('../sitemap.xml');

?>