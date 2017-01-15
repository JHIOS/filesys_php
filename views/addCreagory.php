<?php
/**
 * Created by PhpStorm.
 * User: jianghao
 * Date: 2017/1/14
 * Time: 下午7:33
 */
$result = mysqli_query($con,"SELECT * FROM f_setting");//获取数据
while($row = mysqli_fetch_assoc($result)){
    $tit = $row['main_tit'];
    $theme = $row['theme'];
    $zzurl = $row['zzurl'];
}

$smarty->template_dir = "content/themes/".$theme;

$smarty->assign("userinfo",$userInfo);
$smarty->assign("path",'content/themes/material/');
$smarty->display("createCreagory.html");

?>