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

$sql = "SELECT * FROM member";
if($stmt = $link -> query($sql))
{
    /*
    while($result = mysqli_fetch_object($stmt))
    {
        echo '<p> ID: '.$result -> member_id.' </p> ';
    }*/
    echo " <table width='500' height='120' border='1'>";
    while($row = mysqli_fetch_array($stmt)) 
    { 
        echo "<tr>";
        for ($j=0; $j<5; $j++)
        { //每行有 5 個欄位

            echo "<td height='30'>$row[$j]</td>";   // 欄位高 30 pix

        }
        echo "</tr>";
    } 
}



?>