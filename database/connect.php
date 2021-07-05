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

// fuction 2
function productTransDetail($ID, $link){
    /*function two (input product id and get all the transaction record)*/
    $sql = "SELECT transaction_id, transaction_date, transaction.member_id, transaction_price
    FROM transaction
    WHERE transaction.product_id = $ID";


    if($stmt = $link -> query($sql))
    {
        echo " <table width='500' height='120' border='1'>";
        echo " <tr height='50' align='center'>";
        echo " <td width = '50'> 交易ID </td>";
        echo " <td width = '80'> 交易日期 </td>";
        echo " <td width= '50'> 會員ID </td>";
        echo " <td width = '80'> 交易金額 </td>";
        //echo " <td width = '160'> member_date </td>";
        echo "</tr>";
        while($row = mysqli_fetch_array($stmt)) 
        { 
            echo "<tr align='center'>";
            for ($j=0; $j<4; $j++)
            { //每行有 4 個欄位
                echo "<td height='30'>$row[$j]</td>";   // 欄位高 30 pix
            }
            echo "</tr>";
        } 
    }

    /*function two (input product id and get transaction sum & times from each member)*/
    $sql = "SELECT transaction.member_id, COUNT(transaction.member_id), SUM(transaction.transaction_price)
    FROM transaction
    WHERE product_id = $ID
    GROUP BY member_id
    ORDER BY member_id";


    if($stmt = $link -> query($sql))
    {
        echo " <table width='500' height='120' border='1'>";
        echo " <tr height='50' align='center'>";
        echo " <td width = '50'> 會員ID </td>";
        echo " <td width = '50'> 歷史購買次數 </td>";
        echo " <td width= '80'> 購買累計金額 </td>";
        //echo " <td width = '160'> 交易金額 </td>";
        //echo " <td width = '160'> member_date </td>";
        echo "</tr>";
        while($row = mysqli_fetch_array($stmt)) 
        { 
            echo "<tr align='center'>";
            for ($j=0; $j<3; $j++)
            { //每行有 3 個欄位
                echo "<td height='30'>$row[$j]</td>";   // 欄位高 30 pix
            }
            echo "</tr>";
        } 
    }
}
productTransDetail(1, $link);

// function three
function consumptionPerDay($ID, $link){
    echo"Hello";
    $sql = "SELECT x.member_id, y.number / x.number AS 'consumption_per_day'
    FROM(
        SELECT DATEDIFF('2022-01-01', member.member_date) AS number, member.member_id
        FROM member
        WHERE member.member_id = 3
    ) x
    JOIN(
        SELECT SUM(transaction_price) AS number
        FROM transaction
        WHERE transaction.member_id = 3
    ) y ";

    if($stmt = $link -> query($sql))
    {
        echo"Hello";
        echo " <table width='300' height='80' border='1'>
        <td width='100'>會員ID</td>
        <td width='200'>平均每日消費金額</td>";
        echo "</tr>";
        while($result = mysqli_fetch_array($stmt))
        {
            echo "<tr>";
            for ($j=0; $j<2; $j++)
            {
                echo "<td height='30'>$result[$j]</td>";
            } 
            echo "</tr>";
            //echo '<p> Transaction ID: '.$result -> transaction_id.', Transaction Date: '.$result -> transaction_date.', member ID: '.$result -> member_id.', Product ID: '.$result -> product_id.', Transaction Price: '.$result -> transaction_price.'</p> ';
        }
    }
    echo "<br>";
}
consumptionPerDay($ID, $link);




// function four
//option = TRUE for gender, FALSE for age level.
function summaryTrans($option, $ID, $link){
        
    if($option)
    {
        /*function four (input product id and output transaction sum & times for different genders)*/
        $sql = "SELECT member.member_gender, COUNT(transaction.member_id), SUM(transaction.transaction_price)
        FROM member
        JOIN transaction
        ON member.member_id = transaction.member_id
        WHERE transaction.product_id = $ID
        GROUP BY member.member_gender
        ORDER BY member.member_gender";

        if($stmt = $link -> query($sql))
        {
            echo " <table width='500' height='120' border='1'>";
            echo " <tr height='50' align='center'>";
            echo " <td width = '50'> 會員性別 </td>";
            echo " <td width = '50'> 消費總人次 </td>";
            echo " <td width= '80'> 消費總金額 </td>";
            echo "</tr>";
            while($row = mysqli_fetch_array($stmt)) 
            { 
                echo "<tr align='center'>";
                for ($j=0; $j<3; $j++)
                { //每行有 3 個欄位
                    echo "<td height='30'>$row[$j]</td>";   // 欄位高 30 pix
                }
                echo "</tr>";
            } 
        }
    }
    else
    {
        /*function four (input product id and output transaction sum & times for different age level)
        */
        /*Where age level is 0 for age group below 20, 1 for age group 20-30, 2 for age group 30-40, 3 for age group 40 above.*/
        $sql = "SELECT INTERVAL(DATEDIFF('2022-01-01', member.member_BD),7305,10958,14610) AS ageLevel, COUNT(transaction.member_id), SUM(transaction.transaction_price)
        FROM member
        JOIN transaction
        ON member.member_id = transaction.member_id
        WHERE transaction.product_id = $ID
        GROUP BY ageLevel
        ORDER BY ageLevel";

        if($stmt = $link -> query($sql))
        {
            echo " <table width='500' height='120' border='1'>";
            echo " <tr height='50' align='center'>";
            echo " <td width = '50'> 年齡區間 </td>";
            echo " <td width = '50'> 消費總人次 </td>";
            echo " <td width= '80'> 消費總金額 </td>";
            echo "</tr>";
            while($row = mysqli_fetch_array($stmt)) 
            { 
                echo "<tr align='center'>";
                for ($j=0; $j<3; $j++)
                { //每行有 3 個欄位
                    echo "<td height='30'>$row[$j]</td>";   // 欄位高 30 pix
                }
                echo "</tr>";
            } 
        }
    }
}
summaryTrans(FALSE, 1, $link);//option = TRUE for gender, FALSE for age level.


?>