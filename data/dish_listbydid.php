<?php
/*
*由detail.html调用
*根据客户端提交的菜品编号，返回指定菜品的详情
*/
header('Content-Type:application/json');
$output = array();

@$did = $_REQUEST['did'];
if(empty($did)){   //若客户端未提交菜品编号，$did === NULL
    echo "[]"; //若客户端未提交菜品编号，则返回一个空数组，
    return;    //并退出当前页面的执行
}

//访问数据库
$conn = mysqli_connect('127.0.0.1','root','root','kaifanla');
$sql = 'SET NAMES utf8';
mysqli_query($conn, $sql);
$sql = "SELECT did,name,img_lg,material,detail,price FROM kf_dish WHERE did=$did";
$result = mysqli_query($conn, $sql);
//根据编号查询，结果集最多只有一行记录
if( ($row=mysqli_fetch_assoc($result))!==NULL ){ //根据编号查询，结果只有一条，没必要用循环
    $output[] = $row;
}

echo json_encode($output);
?>