<?php

$conn = new mysqli("localhost","root","","quotation_portal");

if($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

?>