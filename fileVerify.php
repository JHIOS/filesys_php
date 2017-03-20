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

$data=$_POST['data'];
$data=substr($data,1);

$data=base64_decode($data);
$data = json_decode($data);
$data=$data->service;

$deviceid=$data->deviceid;
$serialNum=$data->serialNum;
$app=$data->app;
$sqm=$data->sqm;

$result['code']='0';
$result['msg']='未知错误';

$sqlret = mysqli_query($con,"SELECT * FROM f_key WHERE sqm = '$sqm'");//获取数据

if ($sqlret->num_rows==0){
    $result['msg']="授权码无效";
    $info = 'q'.base64_encode(json_encode($result));
    $json['result']=$info;
    echo json_encode($json);
    exit(0);
}


while($row = mysqli_fetch_assoc($sqlret)){
    $status=$row['status'];
    $sqmKey=$row['sqmKey'];
    $expTime=$row['expTime'];
    $bundleid=$row['bundleid'];
}
if ($status=='Y'){

    $sqlret = mysqli_query($con,"SELECT * FROM f_verify WHERE sqmKey = '$sqmKey'");//获取数据

    while($row = mysqli_fetch_assoc($sqlret)){
        $nserialNum = $row['serialNum'];
        $ndeviceid=$row['deviceId'];
        $time=$row['yxtime'];

    }
    if ($serialNum==$nserialNum){

        $verifyInfo['data']=md5($serialNum.'***'.$deviceid);
        $verifyInfo['time']=$time;
        $verifyInfo['sqm'] = $sqm;

        $result['resultctx']=base64_encode(json_encode($verifyInfo));

        $result['code']='2';
        $result['msg']="success";

        $info = 'q'.base64_encode(json_encode($result));
        $json['result']=$info;
        echo json_encode($json);
        exit(0);
    }else{
        $result['msg']="授权码无效";
        $info = 'q'.base64_encode(json_encode($result));
        $json['result']=$info;
        echo json_encode($json);
        exit(0);
    }



}

if ($app!=$bundleid){
    $result['msg']="授权码不对应";
    $info = 'q'.base64_encode(json_encode($result));
    $json['result']=$info;
    echo json_encode($json);
    exit(0);
}



$time='+'.$expTime.' day';
$time=strtotime('+'.$expTime.' day');

$verifyInfo['data']=md5($serialNum.'***'.$deviceid);
$verifyInfo['time']=$time;
$verifyInfo['sqm'] = $sqm;
$result['resultctx']=base64_encode(json_encode($verifyInfo));

$result['code']='1';
$result['msg']="授权成功";

$info = 'q'.base64_encode(json_encode($result));
$json['result']=$info;
echo json_encode($json);


$sqlret = mysqli_query($con,"update f_key set status = 'Y' WHERE sqm = '$sqm';");//获取数据
$sqlret = mysqli_query($con,"INSERT INTO  `f_verify` (`serialNum` ,`deviceId` ,`sqmKey`,`yxtime`)VALUES ('$serialNum', '$deviceid', '$sqmKey',$time);");//获取数据





?>