<?php
date_default_timezone_set('Asia/Shanghai');
require_once("config.php");
require_once('includes/function.php');
require_once('includes/smarty.inc.php');
require_once('includes/connect.php');
require_once('includes/userShell.php');
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
$id = $_GET["id"];
$key_check=inject_check($id);

$result = mysqli_query($con,"SELECT * FROM f_setting");//获取数据
while($row = mysqli_fetch_assoc($result)){
    $tit = $row['main_tit'];
    $theme = $row['theme'];
    $zzurl = $row['zzurl'];
}

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
$err[0] = false;
$err[1] = "";

if(file_exists("views/config.json")){
	$configFile = fopen("views/config.json", "r");
	$configContent  = fread($configFile,filesize("views/config.json"));
	fclose($configFile);
	$pages = json_decode($configContent,true);
	$idCheck[0] = false;
	foreach($pages as $key => $value) {
		if($value["id"] == $id){
			$idCheck[0] = false;
			$idCheck[1] = $value["file"];

            $smarty->template_dir = "content/themes/".$theme;
            $smarty->assign("path",'content/themes/'.$theme.'/');
            $smarty->assign("userinfo",$userInfo);

			require_once("views/".$idCheck[1].".php");
			exit();
		}else{
			$err[0] = true;
			$err[1] = "当前页面未注册";
		}
	}
}else{
	$err[0] = true;
	$err[1] = "无法读取模板自定义有页面配置文件";
}
if($err[0]){  
	$smarty->template_dir = "content/themes/".$theme;
	$head='<script type="text/javascript" src="./../includes/js/jquery-1.9.1.min.js"></script>';

    $smarty->assign("path",'content/themes/material/');
    $smarty->display("404.html");  // 输出页面
}else{
}
?>