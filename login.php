<?php
require_once("config.php");//基础配置文件
require_once('includes/function.php');//函数库
require_once('includes/smarty.inc.php');//smarty模板配置
require_once('includes/connect.php');

$result = mysqli_query($con,"SELECT * FROM f_setting");//获取数据
while($row = mysqli_fetch_assoc($result)){
    $tit = $row['main_tit'];
    $theme = $row['theme'];
    $zzurl = $row['zzurl'];
}

$smarty->template_dir = "content/themes/".$theme;

$smarty->assign("path",'content/themes/material/');
$smarty->display("login.html");  
?>