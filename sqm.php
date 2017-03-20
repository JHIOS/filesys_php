<?php
/**
 * Created by PhpStorm.
 * User: jianghao
 * Date: 2017/1/16
 * Time: 下午6:26
 */

date_default_timezone_set('Asia/Shanghai');
require_once("config.php");
require_once('includes/function.php');
require_once('includes/connect.php');


$bundleid=$_GET['bundleid'];
$yxtime=$_GET['yxtime'];
$num=$_GET['num'];
$sqm=array();

for ($i=0;$i<$num;$i++){

    $key=randomkeys(16);

    $sqlret = mysqli_query($con,"SELECT * FROM f_key WHERE sqmKey = '$key'");//获取数据

    if ($sqlret->num_rows==0){
        $sqm[$i]=$key;
        $ret = mysqli_query($con,"INSERT INTO  `f_key` (`sqmKey` ,`sqm` ,`expTime`,`bundleid`)VALUES ('$key', '$key', '$yxtime','$bundleid');");//获取数据
    }else{
        $i--;
    }

}

echo json_encode($sqm);



function randomkeys($length)
{
    $key='';
    $pattern='1234567890ABCDEF';
    for($i=0;$i<$length;$i++)
    {
        $key .= $pattern{mt_rand(0,15)};    //生成php随机数
    }
    return $key;
}



?>