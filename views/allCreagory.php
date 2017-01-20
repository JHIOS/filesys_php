<?php
/**
 * Created by PhpStorm.
 * User: jianghao
 * Date: 2017/1/16
 * Time: 下午6:26
 */

$result = mysqli_query($con,"SELECT * FROM f_category");//获取数据

$categoryTit=array('名称','分隔符','字段数量','字段名称','文件前缀');
while($row = mysqli_fetch_assoc($result)){
    $categoryOne[]=$row;
}

setcookie("category",json_encode($categoryOne));

$result = mysqli_query($con,"SELECT * FROM f_setting");//获取数据
while($row = mysqli_fetch_assoc($result)){
    $tit = $row['main_tit'];
    $theme = $row['theme'];
    $zzurl = $row['zzurl'];
}

$smarty->template_dir = "content/themes/".$theme;

$smarty->assign("userinfo",$userInfo);
$smarty->assign("category",$categoryOne);
$smarty->assign("categorytit",$categoryTit);

$smarty->assign("path",'content/themes/material/');
$smarty->display("allcategory.html");

?>