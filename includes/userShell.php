<?php
$isVisitor = "true";
session_start();
$userInfo2=user_check($_SESSION[uid],$_SESSION[user_check],$con);
if($userInfo2 == "bad"){
	$isVisitor = "true";
	$userInfo["uid"]="";
	$userInfo["group"]="1";
    echo '<script>window.location.href="login.php";</script>';
    exit();
}else{
	$isVisitor = "false";
	unset($userInfo2["pwd"]);
	$userInfo = $userInfo2;

}
?>