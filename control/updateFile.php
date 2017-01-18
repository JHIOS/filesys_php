<?php
/**
 * Created by PhpStorm.
 * User: jianghao
 * Date: 2017/1/17
 * Time: 下午7:11
 */
require_once("../config.php");
require_once('../includes/function.php');
require_once('../includes/connect.php');
require_once('../includes/userShell.php');

//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);

$user_dir="".$userinfo['uid'];


$result=mysqli_query($con,"select * from f_category");
$clist=array();
while ($row=mysqli_fetch_row($result)){
    $clist[]=$row;
}



$splitChar = '----';//竖线
$file = 'yys-006.txt';
$fields = array('filed0','filed1');
$table = 'f_data_yys';
$fkey=createName(8);
$result = loadTxtDataIntoDatabase($splitChar,$file,$table,$con,$fields,$fkey);
if (array_shift($result)){
    echo json_encode("成功");
}else {
    echo json_encode("成功");
}




function loadTxtDataIntoDatabase($splitChar,$file,$table,$conn,$fields=array(),$fkey,$insertType='INSERT'){

    if(empty($fields)) $head = "{$insertType} INTO `{$table}` VALUES('";
    else $head = "{$insertType} INTO `{$table}`(`fkey`,`".implode('`,`',$fields)."`) VALUES('$fkey','";  //数据头
    $end = "')";
    $sqldata = trim(file_get_contents($file));
    if(preg_replace('/\s*/i','',$splitChar) == '') {
        $splitChar = '/(\w+)(\s+)/i';
        $replace = "$1','";
        $specialFunc = 'preg_replace';
    }else {
        $splitChar = $splitChar;
        $replace = "','";
        $specialFunc = 'str_replace';
    }
    //处理数据体，二者顺序不可换，否则空格或Tab分隔符时出错
    $temp=$fkey.$splitChar;
    $sqldata = preg_replace('/(\s*)(\n+)(\s*)/i','\'),(\''."$temp",$sqldata);  //替换换行
    $sqldata = $specialFunc($splitChar,$replace,$sqldata);        //替换分隔符
    $query = $head.$sqldata.$end;  //数据拼接
    if(mysqli_query($conn,$query))
        return array(true);
    else {
        return array(false,mysqli_error($conn),mysqli_errno($conn));
    }
}



function addCreagory($con){

    $cname = str_replace(" ", "", $_POST['cname']);
    $split = str_replace(" ", "", $_POST['split']);
    $prefix = str_replace(" ", "", $_POST['prefix']);
    $filednum = str_replace(" ", "", $_POST['filednum']);
    $filednames = str_replace(" ", "", $_POST['filednames']);
    $filednames = str_replace("，", ",", $filednames);

    $prefix2 = str_replace("-", "", $prefix);
    $prefix2 = str_replace("_", "", $prefix2);

//    $filednames = explode(',',$filednames);
//    $filednames = json_encode($filednames);
    $tablename='f_data_'.$prefix2;

    $sql='CREATE TABLE '.$tablename.' (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `fkey` text CHARACTER SET utf8 NOT NULL,';

    for ($i=0;$i<$filednum;$i++){
        $sql = $sql . "`filed$i` text CHARACTER SET utf8 NOT NULL,
        ";
    }
    $sql = $sql . "  PRIMARY KEY (`id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

    $results = mysqli_query($con, $sql);
    if (!$results){
        $message['code'] = "bad";
        $message['message'] = "增加分类出错 请重试";
        return json_encode($message);
    }

    $ckey=createName(8);
    $sql = "INSERT INTO  `f_category` (`category` ,`split` ,`fieldnum` ,`fieldname` ,`prefix` ,`ckey` ,`ltable`)VALUES 
            ('$cname', '$split', '$filednum', '$filednames' ,'$prefix' ,?,'$tablename');";
    if (!$con) {
        $re = "bad.数据库连接失败";
    } else {
        $stmt = $con->prepare($sql);
        $stmt->bind_param('s', $ckey);
        $rest = $stmt->execute();
    }
    if ($rest){
        $message['code'] = "ok";
        $message['message'] = "增加分类成功";
        return json_encode($message);
    }else{
        $message['code'] = "bad";
        $message['message'] = "增加分类失败";
        return json_encode($message);
    }
}

function delCreagory($con){
    $ckey=$_POST['ckey'];
    $sql="select * from f_category where ckey = '$ckey';";
    $rest=mysqli_query($con,$sql);
    while ($row=mysqli_fetch_assoc($rest)){
        $ltable=$row['ltable'];
    }
    $sql="DROP TABLE IF EXISTS $ltable;";
    $rest=mysqli_query($con,$sql);
    if (!$rest){
        $message['code'] = "bad";
        $message['message'] = "删除分类失败 1001";
        return json_encode($message);
    }
    $sql = "delete from f_category where ckey = '$ckey';";
    $rest = mysqli_query($con,$sql);
    if ($rest){
        $message['code'] = "OK";
        $message['message'] = "删除分类成功";
        return json_encode($message);
    }else{
        $message['code'] = "bad";
        $message['message'] = "删除分类失败 1002";
        return json_encode($message);
    }
}



function createName($len)
{
    $chars_array = array(
        "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
        "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z",
    );
    $charsLen = count($chars_array) - 1;

    $outputstr = "";
    for ($i=0; $i<$len; $i++)
    {
        $outputstr .= $chars_array[mt_rand(0, $charsLen)];
    }
    return $outputstr;
}

?>