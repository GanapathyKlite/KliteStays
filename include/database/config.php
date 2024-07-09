<?php

require_once 'medoo.php';

include_once 'common_functions.php';
include_once 'config-keyspayment.php';

global $database;
$database = $database_hotel =$database_car = new medoo('klitestays');
//$database_bus = new medoo('buddyadm_bus');
//$database_flight = new medoo('buddyadm_flight');


if($_SERVER['HTTP_HOST'] == 'klitestays.com'||$_SERVER['HTTP_HOST'] == 'www.klitestays.com'||$_SERVER['HTTP_HOST'] == 'http://klitestays.com'){
	$root_dir="https://".$_SERVER['HTTP_HOST']."/";
	$bo_img_dir = 'https://hotel.buddiestechnologies.com/img/uploads/';
	$bo_hotel_img_dir = $bo_img_dir;

//	$bo_hotel_img_dir = 'http://hotel.buddiestours.com/img/uploads/';
}else{
	$root_dir="http://".$_SERVER['HTTP_HOST']."/klitestays/";
	//$bo_img_dir = 'http://'.$_SERVER['HTTP_HOST'].'/admin/img/uploads/';
	$bo_hotel_img_dir =$bo_img_dir = 'http://'.$_SERVER['HTTP_HOST'].'/hotel/img/uploads/';

	//$bo_hotel_img_dir = 'http://'.$_SERVER['HTTP_HOST'].'/car/img/uploads/';
}


if(!defined('SITENAME'))
	define('SITENAME', 'Klitestays');
if(!defined('_BO_IMG_DIR_'))
	define('_BO_IMG_DIR_', $bo_img_dir);
if(!defined('_BO_HOTEL_IMG_DIR_'))
	define('_BO_HOTEL_IMG_DIR_', $bo_hotel_img_dir);
global $propertyTypes;
$propertyTypes = array(
				1 => 'Hotel',
				2 => 'Resort',
				3 => 'Appartment',
				4 => 'Villa',
				5 => 'Home Stay',
				6 => 'Dormitory',
				7 => 'Guest House',
				8 => 'Beach Resort'
			);
global $starRatings;
$starRatings = array(
				1 => '1 Star',
				2 => '2 Star (Standard & Deluxe)',
				3 => '3 Star',
				4 => '4 Star',
				5 => '5 Star',
				6 => '7 Star'
				//7 => 'Standard',
				//8 => 'Deluxe'
			);
global $roomCategory;
$roomCategory = array(
				1 => 'Standard AC',
				7 => 'Standard Non-AC',
				2 => 'Premium AC',
				8 => 'Premium Non-AC',
				3 => 'Executive AC',
				9 => 'Executive Non-AC',
				4 => 'Suite AC',
				10 => 'Suite Non-AC',
				5 => 'Deluxe AC',
				11 => 'Deluxe Non-AC',
				6 => 'Superior AC',
				12 => 'Superior Non-AC',
				13 => 'Others'
			);
global $roomTypes;
$roomTypes = array(
				1 => 'Single',
				//5 => 'Single with AC',
				2 => 'Double',
				//6 => 'Double with AC',
				3 => 'Triple',
				//7 => 'Triple with AC',
				4 => 'Quadruple',
				//8 => 'Quadruple with AC'
			);
global $roomBedSize;
$roomBedSize = array(
				1 => 'Single Size Bed',
				2 => 'Double Size Bed',
				3 => 'Twin Size Bed'
			);
global $mealPlanList;
$mealPlanList = array(
				1 => 'AP',
				2 => 'MAP',
				3 => 'CP',
				4 => 'EP'
			);
global $months;
$months = array(
				1 => 'January',
				2 => 'February',
				3 => 'March',
				4 => 'April',
				5 => 'May',
				6 => 'June',
				7 => 'July',
				8 => 'August',
				9 => 'September',
				10 => 'October',
				11 => 'November',
				12 => 'December'
			);

?>