<?php

require_once 'config.php';

//TODO check session

$name_en = "";
$name_ch = "";
$show_date = "";
$show_state = "";
$info = "";
$img_src = "";



if (isset($_POST['nameen'])){
	$name_en = $_POST['nameen'];
}else{
	die("English name is needed.");
}


if (isset($_POST['namech'])){
	$name_ch = $_POST['namech'];
}

if (isset($_POST['showdate'])){
	$show_date = $_POST['showdate'];
}
if (isset($_POST['showstate'])){
	$show_state = $_POST['showstate'];
}
if (isset($_POST['info'])){
	$info = $_POST['info'];
}
if (isset($_POST['imgsrc'])){
	$img_src = $_POST['imgsrc'];
}

$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if (!$mysqli){
	die("Cannot connect to db.\n");
}

print "connected.\n";


$query = "insert into series(name_en,name_ch,show_date,show_state,info,img_src) values(?,?,?,?,?,?)";

$bind_result = $mysqli->prepare($query);
$bind_result->bind_param("ssssss", $name_en,$name_ch,$show_date,$show_state,$info,$img_src);

$bind_result->execute();
$bind_result->close();
$mysqli->close();

echo "Add done.\n";




?>