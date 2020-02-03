<?php
    session_start();
    include "conn.php";
    $phone = $_POST['phone'];
    $myjson = 0;
    $sql = "SELECT `email`,`sic` FROM `student` WHERE `phone`='$phone'";
    $result = mysqli_query($conn, $sql);
    if(isset($_SESSION['sic'])) {
        if( $result == true and mysqli_num_rows($result) )  {
            $res = mysqli_fetch_array($result);
            // echo "$res[email]";
            if( $res['sic'] == $_SESSION['sic']) {
                $myjson = 0;
            }
            else {
                $myjson = 1;
            }
        }
    }
    else {
        if(mysqli_num_rows($result) > 0)  {
            $myjson = 1;
        }
    }
    echo "$myjson";
    mysqli_close($conn);
?>