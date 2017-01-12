<?php
require_once("config.php");//基础配置文件
if(!isset($sqlid)){
	die('<script>
window.location="install";

</script>');
}


require_once('includes/function.php');//函数库
require_once('includes/smarty.inc.php');//smarty模板配置
require_once('includes/connect.php');
require_once('includes/userShell.php');

$result = mysqli_query($con,"SELECT * FROM f_setting");//获取数据
//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
while($row = mysqli_fetch_assoc($result)){ 
	$tit = $row['main_tit'];
	$theme = $row['theme'];
	$zzurl = $row['zzurl'];
}



$smarty->template_dir = "content/themes/".$theme;
$head ='
<meta name="description" content="'.$des.'" />
<meta name="keywords" content="'.$kw.'" />';
$staticFile = '<script type="text/javascript" src="includes/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="includes/js/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="includes/js/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="includes/js/qiniu.js"></script>
<script type="text/javascript" src="includes/js/main.js"></script>
<script type="text/javascript" src="includes/js/ui.js"></script>';
$jscode = $tjcode.'
<script type="text/javascript">
var autoname='.$autoName.';'.$fileType.'; var max='.$fileSize.'; var fp="'.$filePart.'"; var upserver ="'.$upServer.'";var policyType="'.$policyType.'"</script>';

//$smarty->assign("isVisitor", $isVisitor);
//$smarty->assign("userinfo", $userInfo);
//$smarty->assign("des", $des);
//$smarty->assign("kw", $kw);
//$smarty->assign("notice", $notice);
//$smarty->assign("titmain", $tit."-".$tit1);
//$smarty->assign("tit", $tit);
//$smarty->assign("zzurl", $zzurl);
//$smarty->assign("head", $head);
//$smarty->assign("static", $staticFile);
//$smarty->assign("jscode", 'Powerd by <a target="_blank" href="https://appstore.yonyou.com/sites/">用友金融</a> '.$jscode);
$smarty->assign("path",'content/themes/material/');
$smarty->display("index.html");

?>
