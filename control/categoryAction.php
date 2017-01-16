<?php
/**
 * Created by PhpStorm.
 * User: jianghao
 * Date: 2017/1/15
 * Time: 下午10:07
 */
require_once("../config.php");
require_once('../includes/function.php');
require_once('../includes/connect.php');
require_once('../includes/userShell.php');

//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
$action = $_POST['action'];
switch ($action) {
    case 'add':
        echo addCreagory($con);
        break;
    case 'del':
        echo delCreagory();
        break;
    case 'fix':
        echo fixCreagory();
        break;
    case 'sel':
        echo selCreagory();
        break;
    default:
        # code...
        break;
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
        echo json_encode($message);
        exit();
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
        echo json_encode($message);
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