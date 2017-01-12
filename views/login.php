<?php
require_once("../config.php");//基础配置文件
require_once('../includes/function.php');//函数库
require_once('../includes/smarty.inc.php');//smarty模板配置
require_once('../includes/connect.php');
//require_once('../includes/userShell.php');
//if($isVisitor=="true"){}else{
//	echo '<script>window.location.href="index.php";</script>';
//	exit();
//}
$result = mysqli_query($con,"SELECT * FROM f_setting");//获取数据
while($row = mysqli_fetch_assoc($result)){
    $tit = $row['main_tit'];
    $theme = $row['theme'];
    $zzurl = $row['zzurl'];
}

$smarty->template_dir = "./../content/themes/".$theme;
$head='<script type="text/javascript" src="./../includes/js/jquery-1.9.1.min.js"></script>';
$jscode=$tjcode;
//$smarty->assign("tit", $tit);
//$smarty->assign("zzurl", $url);
//$smarty->assign("isVisitor", $isVisitor);
//$smarty->assign("userinfo", $userInfo);
//$smarty->assign("head", $head);
//$smarty->assign("jscode", 'Powerd by <a target="_blank" href="https://appstore.yonyou.com">用友金融</a> '.$jscode);
$smarty->assign("path",'../content/themes/material/');
$smarty->display("login.html");  
?>