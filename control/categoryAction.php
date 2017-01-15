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
        echo userLogin($_POST['username'],$_POST['password'],$con);
        break;
    case 'del':
        echo userLogin($_POST['username'],$_POST['password'],$con);
        break;
    case 'fix':
        echo userLogin($_POST['username'],$_POST['password'],$con);
        break;
    case 'sel':
        echo userLogin($_POST['username'],$_POST['password'],$con);
        break;
    default:
        # code...
        break;
}
function userReg($userName,$passWord,$userid,$email,$SQlcon){
    $userName = str_replace(" ", "", $userName);
    $passWord = str_replace(" ", "", $passWord);
    $userid = str_replace(" ", "", $userid);
    $email = str_replace(" ", "", $email);

    $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
    if ( ! preg_match( $pattern, $email ) || (mb_strlen($email,'UTF8')>22) || (mb_strlen($email,'UTF8')<4) ){
        $result['code']="bad";
        $result['message']=urlencode("电子邮箱或密码不符合规范");
        return urldecode(json_encode($result));
        exit();
    }
    $stmt = mysqli_stmt_init($SQlcon);
    mysqli_stmt_prepare($stmt, 'SELECT id FROM f_user WHERE email=? ');
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$uid);
    $results = mysqli_stmt_fetch($stmt) ;
    if(! empty($results)){
        $result['code']="bad";
        $result['message']=urlencode("此电子邮箱已被注册！");
        return urldecode(json_encode($result));
        exit();
    }

    mysqli_stmt_prepare($stmt, 'SELECT id FROM f_user WHERE uid=? ');
    mysqli_stmt_bind_param($stmt, "s", $userid);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$uid);
    $results = mysqli_stmt_fetch($stmt) ;
    if(! empty($results)){
        $result['code']="bad";
        $result['message']=urlencode("此id已经被绑定！");
        return urldecode(json_encode($result));
        exit();
    }

    mysqli_stmt_prepare($stmt, 'SELECT id FROM f_user WHERE username=? ');
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$uid);
    $results = mysqli_stmt_fetch($stmt) ;
    if(! empty($results)){
        $result['code']="bad";
        $result['message']=urlencode("此用户名已被注册！");
        return urldecode(json_encode($result));
        exit();
    }

    $passWord=md5($passWord."sdshare");
    $timeNow = date('Y-m-d H:i:s');
    $ukey=date('YmdHis').mt_rand(10001,99999);
    $sql="INSERT INTO  `f_user` (`username` ,`pwd` ,`uid`,`email`,`ukey`,`regtime`)VALUES (?, '$passWord', '$userid' ,'$email','$ukey','$timeNow');";
    $stmt = $SQlcon->prepare($sql);
    $stmt->bind_param('s', $userName);
    $rest=$stmt->execute();
    if ($rest){
        $result['code']="ok";
        $result['message']=urlencode("ok");

        $_SESSION[uid]=$ukey;
        $_SESSION[user_check]=md5($userName.$passWord."sdshare");
    }else{
        $result['code']="bad";
        $result['message']=urlencode("操作失败 1000");//插入数据库失败
    }

    return urldecode(json_encode($result));
}



?>