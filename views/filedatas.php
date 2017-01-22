<?php
/**
 * Created by PhpStorm.
 * User: jianghao
 * Date: 2017/1/21
 * Time: 上午9:03
 */

$fkey=$_GET['fkey'];
$ukey=$userInfo['ukey'];

$result = mysqli_query($con,"SELECT ckey,filename FROM f_file WHERE fkey='$fkey' and ukey = '$ukey'");//获取数据
while($row = mysqli_fetch_assoc($result)){
    $ckey=$row['ckey'];
    $filename=$row['filename'];
}
if (!$result){
    exit();
}
$result = mysqli_query($con,"SELECT * FROM f_category WHERE ckey='$ckey'");//获取数据

while($row = mysqli_fetch_assoc($result)){
    $cinfo=$row;
}
$filetit=explode(",",$cinfo['fieldname']);
$ltable=$cinfo['ltable'];
$fieldnum=$cinfo['fieldnum'];
$split=$cinfo['split'];

$result = mysqli_query($con,"SELECT * FROM $ltable WHERE fkey='$fkey'");//获取数据
while($row = mysqli_fetch_row($result)){
    $finfos[]=$row;
}



$smarty->assign("filetit",$filetit);
$smarty->assign("finfos",$finfos);
$smarty->assign("fieldnum",$fieldnum);
$smarty->assign("filename",$filename);
$smarty->assign("split",$split);

$smarty->display("filedatas.html");

?>