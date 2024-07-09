<?php
require_once 'medoo.php';
//include_once 'common_functions.php';

global $database;
$database = $database_hotel = new medoo('buddinfi_hotelbud_technologies');
//$database = $database_hotel = new medoo('staysinn');
//$root_dir="https://".$_SERVER['HTTP_HOST']."/";
$root_dir=$_SERVER['HTTP_HOST']."/";
$bo_img_dir = $root_dir.'img/uploads/';
$bo_hotel_img_dir = $root_dir.'img/uploads/';

if(!defined('_BO_IMG_DIR_'))
	define('_BO_IMG_DIR_', $bo_img_dir);
if(!defined('_BO_HOTEL_IMG_DIR_'))
	define('_BO_HOTEL_IMG_DIR_', $bo_hotel_img_dir);
?>