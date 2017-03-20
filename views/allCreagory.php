<?php
/**
 * Created by PhpStorm.
 * User: jianghao
 * Date: 2017/1/16
 * Time: 下午6:26
 */

$result = mysqli_query($con,"SELECT * FROM f_category");//获取数据

$categoryTit=array('名称','分隔符','字段数量','字段名称','文件前缀');
while($row = mysqli_fetch_assoc($result)){
    $categoryOne[]=$row;
}



$smarty->assign("category",$categoryOne);
$smarty->assign("categorytit",$categoryTit);

$smarty->display("allcategory.html");

?>