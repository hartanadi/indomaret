<?php
define ('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];
    $name = $_POST['name'];
    $status = $_POST['status'];
    
    
    if ($action == 'add') {
        $query = "INSERT INTO kasir (id, nama, Status) VALUES ('$id', '$name', '$status')";
        mysqli_query($conn, $query);
    } elseif ($action == 'edit') {
        $query = "UPDATE kasir SET nama='$name', Status='$status' WHERE id='$id'";
        mysqli_query($conn, $query);
    } elseif ($action == 'delete') {
        $query = "DELETE FROM kasir WHERE id='$id'";
        mysqli_query($conn, $query);
    }

    header("Location: ../pages/cashiers/list.php");
    exit;
}