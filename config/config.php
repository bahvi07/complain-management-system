<?php
$conn=new mysqli('localhost','root','','complaint_system');
if($conn->connect_error){
    die('connection failed');
}
?>