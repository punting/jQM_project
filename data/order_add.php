<?php
/*
*由order.html调用
*根据客户端订单信息，向订单表中插入一条记录，获得数据库返回的订单编号,以json格式
*/
//header('Content-Type: application/json');
$output = array();

@$user_name = $_REQUEST['user_name'];
@$sex = $_REQUEST['user_sex'];
@$phone = $_REQUEST['user_phone'];
@$addr = $_REQUEST['user_addr'];
@$did = $_REQUEST['did'];
$order_time = time()*1000;   //PHP中的time()函数返回当前系统时间对应的整数值，单位为 秒

if(empty($phone) || empty($user_name) || empty($sex) || empty($addr) || empty($did)){
    //echo "[]"; //若客户端提交信息不足，则返回一个空数组，
    echo'{"result":"err","msg":"INVALUD REQUEST DATE"}';
    return;    //并退出当前页面的执行
}

//访问数据库
$conn = mysqli_connect('127.0.0.1','root','root','kaifanla','3306');
$sql = 'SET NAMES utf8';
mysqli_query($conn, $sql);
$sql = "INSERT INTO kf_order(oid,phone,user_name,sex,order_time,addr,did) VALUES(NULL,'$phone','$user_name','$sex','$order_time','$addr','$did')";
$result = mysqli_query($conn, $sql);

$arr = array();
if($result){    //INSERT语句执行成功
    $arr['msg'] = 'succ';
    $arr['oid'] = mysqli_insert_id($conn); //获取最近执行的一条INSERT语句生成的自增主键，在此操作之前必须要有insert操作
}else{          //INSERT语句执行失败
    $arr['msg'] = 'err';
    $arr['reason'] = "SQL语句执行失败：$sql";
}
$output[] = $arr;

echo json_encode($output);
?>