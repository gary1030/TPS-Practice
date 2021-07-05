<?php
// Connecting, selecting database
$user = 'root';
$password = 'root';
$db = 'attempt_01';
$host = 'localhost';
$port = 8889;

$link = mysqli_init();
$success = mysqli_real_connect(
    $link, 
    $host, 
    $user, 
    $password, 
    $db,
    $port
) or die('Could not connect: ' .  mysqli_connect_error());

// UTF8
mysqli_set_charset($link, 'utf8');

// $sql = "SELECT * FROM member";
// if($stmt = $link -> query($sql))
// {
//     /*
//     while($result = mysqli_fetch_object($stmt))
//     {
//         echo '<p> ID: '.$result -> member_id.' </p> ';
//     }*/
//     echo " <table width='500' height='120' border='1'>";
//     while($row = mysqli_fetch_array($stmt)) 
//     { 
//         echo "<tr>";
//         for ($j=0; $j<5; $j++)
//         { //每行有 5 個欄位

//             echo "<td height='30'>$row[$j]</td>";   // 欄位高 30 pix

//         }
//         echo "</tr>";
//     } 
// }

// function one
function memberTransDetail($ID, $link){
    $sql = "SELECT *
    FROM transaction
    WHERE transaction.member_id = $ID";

    if($stmt = $link -> query($sql))
    {
        echo " <table width='600' height='120' border='1'>
        <td width='100'>交易ID</td>
        <td width='200'>交易日期</td>
        <td width='100'>會員ID</td>
        <td width='100'>商品ID</td>
        <td width='100'>交易金額</td>";

        while($result = mysqli_fetch_array($stmt))
        {
            echo "<tr>";
            for ($j=0; $j<5; $j++)
            {
                echo "<td height='30'>$result[$j]</td>";
            } 
            echo "</tr>";
            //echo '<p> Transaction ID: '.$result -> transaction_id.', Transaction Date: '.$result -> transaction_date.', member ID: '.$result -> member_id.', Product ID: '.$result -> product_id.', Transaction Price: '.$result -> transaction_price.'</p> ';
        }
    }

    
    $sql = "SELECT 
            transaction.product_id, COUNT(transaction.product_id) as productCnt, SUM(transaction.transaction_price) as productSum
            FROM transaction WHERE member_id = $ID
            GROUP BY product_id
            ORDER BY product_id";
    
    if($stmt = $link -> query($sql))
    {
        echo "<table width='400' height='120' border='1'>
        <td width='100'>商品ID</td>
        <td width='150'>歷史購買次數</td>
        <td width='150'>購買累計金額</td>";
        while($result = mysqli_fetch_array($stmt))
        {
            echo "<tr>";
            for ($j=0; $j<3; $j++)
            {
                echo "<td height='30'>$result[$j]</td>";
            }
        }
    }
    echo "<br>";
}
memberTransDetail(3, $link);




?>