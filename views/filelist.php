<?php
/**
 * Created by PhpStorm.
 * User: jianghao
 * Date: 2017/1/16
 * Time: 下午6:26
 */

$ckey=$_GET['ckey'];
$ukey=$userInfo['ukey'];

$filetit=array('名称','更新时间','状态');

$result = mysqli_query($con,"SELECT * FROM f_file WHERE ckey='$ckey' and ukey = '$ukey'");//获取数据
while($row = mysqli_fetch_assoc($result)){
    $filedetail[]=$row;
}

$result = mysqli_query($con,"SELECT category FROM f_category WHERE ckey='$ckey'");//获取数据
while($row = mysqli_fetch_assoc($result)){
    $category=$row['category'];
}



$smarty->assign("filedetail",$filedetail);
$smarty->assign("filetit",$filetit);
$smarty->assign("cname",$category);

$smarty->display("filelist.html");

?>