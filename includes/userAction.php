<?php
require_once("../config.php");
require_once('function.php');
require_once('connect.php');
//require_once('userShell.php');
session_start();

//ini_set("display_errors", "On");
//error_reporting(E_ALL | E_STRICT);
$action = $_GET['action'];
if(!empty($_POST['action'])){
	switch ($_GET['action']) {
		case 'logout':
			unset($_SESSION['uid']);
   			unset($_SESSION['user_check']);
   			echo '<script>window.history.go(-1);</script>';
			break;
		
		default:
			# code...
			break;
	}
	exit();
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
function userLogin($userName,$passWord,$SQlcon){
	$userName = str_replace(" ", "", $userName);
	$passWord = str_replace(" ", "", $passWord);
	if(empty($userName) || empty($passWord)){
		$result['code']="bad";
		$result['message']=urlencode("表单不完整");
		return urldecode(json_encode($result));
		exit();
	}
	$stmt = mysqli_stmt_init($SQlcon);
	mysqli_stmt_prepare($stmt, 'SELECT ukey,username,pwd FROM f_user WHERE username=?');
	mysqli_stmt_bind_param($stmt, "s", $userName);
 	mysqli_stmt_execute($stmt);
 	$results = mysqli_stmt_bind_result($stmt,$uid,$um,$pwd);
 	$result['code']="bad";
	$result['message']=urlencode("用户名或密码错误");
	while (mysqli_stmt_fetch($stmt)) {
        if(md5($passWord."sdshare") == $pwd){
 			$ps = true ;
 		}else{
 			$ps = false ;
 		}
 		if($ps){
 			$result['code']="ok";
			$result['message']=urlencode("登录成功");
			$_SESSION[uid]=$uid;
    		$_SESSION[user_check]=md5($um.$pwd."sdshare");
 		}else{
 		}
    }
 	return urldecode(json_encode($result));
}

function changePwd($pwdNew,$con,$pwdNow,$pwdInput,$userId){
	$pwdNew = str_replace(" ", "", $pwdNew);
	if((mb_strlen($pwdNew,'UTF8')<4) || (mb_strlen($pwdNew,'UTF8')>16)){
		return('bad.密码长度应在4-16字符之间');
		exit();
	}
	if(md5($pwdInput."sdshare") != $pwdNow){
		return('bad.原密码错误');
		exit();
	}
	$pwdNew = md5($pwdNew."sdshare");
	$sql="UPDATE `sd_users` SET `pwd` = '$pwdNew' WHERE `uid` = $userId";
 	mysqli_query($con,$sql);
 	return ('ok.密码修改成功');
}
switch ($action) {
	case 'login':
		echo userLogin($_POST['username'],$_POST['password'],$con);
		break;
	case 'register':
		echo userReg($_GET['username_reg'],$_GET['password_reg'],$_GET['uid'],$_GET['email'],$con);
		break;
	case 'changepwd':
		echo changePwd($_POST['pwd'],$con,$userInfo['pwd'],$_POST['pwdnow'],$userInfo['uid']);
		break;
	default:
		# code...
		break;
}
?>