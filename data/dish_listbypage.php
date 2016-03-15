<?php
/*
*由main.html调用
*根据客户端提交的菜品的序号，分页返回后续的5条菜品
*/
header('Content-Type: application/json');
$output = array();

/*
第1页：  从0条开始 取5条
第2页：  从5条开始 取5条
第3页：  从10条开始 取5条
...
*/

$count = 3;  //一次最多返回的记录条数
@$start = $_REQUEST['start'];  //客户端提交的起始记录的序号
//var_dump($start);   //测试语句
/* @符号用于压制该行代码产生的错误/警告消息 */
if( empty($start) ){        //empty()函数用于判断一个变量是否为空    $start === NULL
    $start = 0;
}

//访问数据库
$conn = mysqli_connect('127.0.0.1','root','root','kaifanla','3306');  //与数据库建立连接,mysqli增强版的,3306端口号为默认值，可省
//var_dump($conn);        //测试语句
$sql = 'SET NAMES utf8';   //设置编码方式
mysqli_query($conn, $sql);   //将操作与数据库连接
$sql = "SELECT did,name,img_sm,img_lg,material,price FROM kf_dish LIMIT $start,$count"; //设置操作，双引号可以拼接变量
$result = mysqli_query($conn, $sql);   //接收操作返回的结果
while( ($row=mysqli_fetch_assoc($result))!==NULL ){//mysqli_fetch_assoc得到的是关联数组,mysqli_fetch_row，返回的每一列，用索引
    $output[] = $row;   //将返回的结果数据压入数组中
}

echo json_encode($output);
?>