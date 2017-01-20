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
$ukey=$userInfo['ukey'];
$user_dir="".$userinfo['uid'];

$dirpath="/Users/jianghao/server/100021";

$updatetime=date('Y-m-d H:i:s');

$result=mysqli_query($con,"select * from f_category");
$clist=array();
while ($row=mysqli_fetch_assoc($result)){
    $clist[]=$row;
}

$sql="select fkey,f_file.ckey,f_category.ltable from f_file LEFT JOIN f_category ON f_file.ckey=f_category.ckey where ukey='$ukey';";
$result = mysqli_query($con,$sql);
$delrest=array();
while ($row = mysqli_fetch_assoc($result)){
    $delrest[]=$row;
}

foreach ($delrest as $item) {

    $ltable=$item['ltable'];
    $fkey=$item['fkey'];
    $sql="delete from $ltable where fkey='$fkey';";
    $result=mysqli_query($con,$sql);

}
$sql="delete from f_file where ukey='$ukey';";
$result=mysqli_query($con,$sql);


$files=scandir($dirpath);

$start=getCurrentTime();
foreach ($files as $filename){
    if ($filename=="."||$filename=="..")continue;
    $filepre=explode("-",$filename);

    foreach ($clist as $v){
        $isfind=false;
        if ($v['prefix']==$filepre[0]){
            $isfind=true;
            $category=$v;
            break;
        }
    }
    if ($isfind){
        $splitChar = $category['split'];//竖线
        $file = $dirpath."/".$filename;
        $fields = array();
        for ($i=0;$i<$category['fieldnum'];$i++){
            $fields[]="field".$i;
        }
        $table = $category['ltable'];
        $fkey=createName(10);
        $result = loadTxtDataIntoDatabase($splitChar,$file,$table,$con,$fields,$fkey);

        $ckey=$category['ckey'];
        $status=array_shift($result);

        $sql = "INSERT INTO  `f_file` (`filename` ,`fkey` ,`ckey`,`ukey`,`updatetime`,`status`) VALUES ('$filename', '$fkey', '$ckey' ,'$ukey','$updatetime','$status')";
        $result=mysqli_query($con,$sql);

    }
}

echo json_encode('成功');



function getCurrentTime ()  {
    list ($msec, $sec) = explode(" ", microtime());
    return (float)$msec + (float)$sec;
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