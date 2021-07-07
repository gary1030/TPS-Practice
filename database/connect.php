<?php
// Connecting, selecting database
$user = 'root';
$password = 'root';
$db = 'tps_db';
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


/*
// get ID
$inputID = 3;
// get Member Name
$inputMemberName = 'Lucas';
// get Product Name
$inputProductName = 'Mini-Doras';
*/

// 
function allMember($link){
    $sql = "SELECT *
    FROM member";

    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt))
        {
            $rows[] = $result;
        }
        $myJSON = json_encode($rows);
        echo $myJSON;
    
        // $fp = fopen('consumptionPerDay.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }
}

function allProduct($link){
    $sql = "SELECT *
    FROM product";

    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt))
        {
            $rows[] = $result;
        }
        $myJSON = json_encode($rows);
        echo $myJSON;
    
        // $fp = fopen('consumptionPerDay.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }
}
// allProduct($link);

function allTrans($link){
    $sql = "SELECT *
    FROM transaction";

    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt))
        {
            $rows[] = $result;
        }
        $myJSON = json_encode($rows);
        echo $myJSON;
    
        // $fp = fopen('consumptionPerDay.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }
}
// allTrans($link);

// function one
function memberTransDetailByID($ID, $link){
    $sql = "SELECT transaction_id, transaction_date, transaction.product_id, transaction_price
    FROM transaction
    WHERE transaction.member_id = $ID";

    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt))
        {
            $rows[] = $result;
        }
        $myJSON = json_encode($rows);
        echo $myJSON;
    
        // $fp = fopen('memberTransDetailByID_1.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }

    
    $sql = "SELECT 
            transaction.product_id, COUNT(transaction.product_id) as transTimes, SUM(transaction.transaction_price) as transAmount
            FROM transaction WHERE member_id = $ID
            GROUP BY product_id
            ORDER BY product_id";
    
    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt))
        {
            $rows[] = $result;
        }
        $myJSON = json_encode($rows);
        echo $myJSON;
    
        // $fp = fopen('memberTransDetailByID_2.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }
}
//memberTransDetailByID($inputID, $link);

// function memberTransDetailByName($Name, $link){
//     //$sql = "SET @name = 'Tatsj';";
//     $sql = "SELECT transaction_id, transaction_date, transaction.product_id, transaction_price
//     FROM transaction
//     WHERE transaction.member_id IN(
//         SELECT member_id
//         FROM member
//         WHERE member_name LIKE '%$Name'
//     )";

//     if($stmt = $link -> query($sql))
//     {
//         $rows = array();
//         while($result = mysqli_fetch_assoc($stmt))
//         {
//             $rows[] = $result;
//         }
//         $myJSON = json_encode($rows);
//         echo $myJSON;
    
//         // $fp = fopen('memberTransDetailByName_1.json', 'w');
//         // fwrite($fp, $myJSON);
//         // fclose($fp);
//     }

    
//     $sql = "SELECT 
//             transaction.product_id, COUNT(transaction.product_id) as transTimes, SUM(transaction.transaction_price) as transAmount
//             FROM transaction 
//             WHERE transaction.member_id IN(
//                 SELECT member_id
//                 FROM member
//                 WHERE member_name LIKE '%$Name'
//             )
//             GROUP BY product_id
//             ORDER BY product_id";
    
//     if($stmt = $link -> query($sql))
//     {
//         $rows = array();
//         while($result = mysqli_fetch_assoc($stmt))
//         {
//             $rows[] = $result;
//         }
//         $myJSON = json_encode($rows);
//         echo $myJSON;
    
//         // $fp = fopen('memberTransDetailByName_2.json', 'w');
//         // fwrite($fp, $myJSON);
//         // fclose($fp);
//     }
// }
// //memberTransDetailByName($inputMemberName, $link);

// fuction 2
function productTransDetailByID($ID, $link){
    /*function two (input product id and get all the transaction record)*/
    $sql = "SELECT transaction_id, transaction_date, transaction.member_id, transaction_price
    FROM transaction
    WHERE transaction.product_id = $ID";


    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt)) 
        { 
            $rows[] = $result;
        }
        //生成json
        $myJSON = json_encode($rows);
        echo $myJSON;
        
        //在電腦上生成
        // $fp = fopen('productTransDetailByID_1.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }
    

    /*function two (input product id and get transaction sum & times from each member)*/
    $sql = "SELECT transaction.member_id, COUNT(transaction.member_id) AS transTimes, SUM(transaction.transaction_price) AS transAmount
    FROM transaction
    WHERE product_id = $ID
    GROUP BY member_id
    ORDER BY member_id";


    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt)) 
        { 
            $rows[] = $result;
        } 
        //生成json
        $myJSON = json_encode($rows);
        echo $myJSON;
    
        //在電腦上生成
        // $fp = fopen('productTransDetailByID_2.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }
}
//productTransDetailByID($inputID, $link);

// function productTransDetailByName($Name, $link){
//     $sql = "SELECT transaction_id, transaction_date, transaction.member_id, transaction_price
//     FROM transaction
//     WHERE transaction.product_id IN(
//         SELECT product_id
//         FROM product
//         WHERE product_name LIKE '%$Name'
//     )";

//     if($stmt = $link -> query($sql))
//     {
//         $rows = array();
//         while($result = mysqli_fetch_assoc($stmt))
//         {
//             $rows[] = $result;
//         }
//         $myJSON = json_encode($rows);
//         echo $myJSON;
    
//         // $fp = fopen('productTransDetailByName_1.json', 'w');
//         // fwrite($fp, $myJSON);
//         // fclose($fp);
//     }
//     else{
//         echo "Fail to find.<br>";
//     }
//     /*function two (input product id and get transaction sum & times from each member)*/
//     $sql = "SELECT transaction.member_id, COUNT(transaction.member_id) AS transTimes, SUM(transaction.transaction_price) AS transAmount
//     FROM transaction
//     WHERE transaction.product_id IN(
//         SELECT product_id
//         FROM product
//         WHERE product_name LIKE '%$Name'
//     )
//     GROUP BY member_id
//     ORDER BY member_id";


//     if($stmt = $link -> query($sql))
//     {
//         $rows = array();
//         while($result = mysqli_fetch_assoc($stmt)) 
//         { 
//             $rows[] = $result;
//         } 
//         //生成json
//         $myJSON = json_encode($rows);
//         echo $myJSON;
    
//         //在電腦上生成
//         // $fp = fopen('productTransDetailByName_2.json', 'w');
//         // fwrite($fp, $myJSON);
//         // fclose($fp);
//     }
// }
// //productTransDetailByName($inputProductName, $link);

// function three
function consumptionPerDay($ID, $link){
    $sql = "SELECT x.member_id, y.number / x.number AS 'consumption_per_day'
    FROM(
        SELECT DATEDIFF('2022-01-01', member.member_date) AS number, member.member_id
        FROM member
        WHERE member.member_id = $ID
    ) x
    JOIN(
        SELECT SUM(transaction_price) AS number
        FROM transaction
        WHERE transaction.member_id = $ID
    ) y ";

    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt))
        {
            $rows[] = $result;
        }
        $myJSON = json_encode($rows);
        echo $myJSON;
    
        // $fp = fopen('consumptionPerDay.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }
}
// consumptionPerDay($inputID, $link);

// function four
function summaryTrans($ID, $link){
        
    /*function four (input product id and output transaction sum & times for different genders)*/
    $sql = "SELECT member.member_gender, COUNT(transaction.member_id) AS transaction_times, SUM(transaction.transaction_price) AS transaction_amount
    FROM member
    JOIN transaction
    ON member.member_id = transaction.member_id
    WHERE transaction.product_id = $ID
    GROUP BY member.member_gender
    ORDER BY member.member_gender";

    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt))
        {
            $rows[] = $result;
        }
        $myJSON = json_encode($rows);
        echo $myJSON;

        // $fp = fopen('summaryTrans_gender.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }
     
    /*function four (input product id and output transaction sum & times for different age level)
    */
    /*Where age level is 0 for age group below 20, 1 for age group 20-30, 2 for age group 30-40, 3 for age group 40 above.*/
    $sql = "SELECT INTERVAL(DATEDIFF('2022-01-01', member.member_BD),7305,10958,14610) AS ageLevel, COUNT(transaction.member_id) AS transTimes, SUM(transaction.transaction_price) AS transAmount
    FROM member
    JOIN transaction
    ON member.member_id = transaction.member_id
    WHERE transaction.product_id = $ID
    GROUP BY ageLevel
    ORDER BY ageLevel";

    if($stmt = $link -> query($sql))
    {
        $rows = array();
        while($result = mysqli_fetch_assoc($stmt))
        {
            if($result['ageLevel'] == '0'){
                $result['ageLevel'] = 'below 20';
            }
            else if($result['ageLevel'] == '1'){
                $result['ageLevel'] = '20-30';
            }
            else if($result['ageLevel'] == '2'){
                $result['ageLevel'] = '30-40';
            }
            else if($result['ageLevel'] == '3'){
                $result['ageLevel'] = '40 above';
            }
            $rows[] = $result;
        }

        $myJSON = json_encode($rows);
        echo $myJSON;

        // $fp = fopen('summaryTrans_age.json', 'w');
        // fwrite($fp, $myJSON);
        // fclose($fp);
    }

}
// summaryTrans($inputID, $link);


// get data from frontend
$received_data = json_decode(file_get_contents("php://input"));

if($received_data->action == "getProductTrans"){
    memberTransDetailByID($received_data->params, $link);
}



?>