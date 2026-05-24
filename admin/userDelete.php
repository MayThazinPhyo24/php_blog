<?php
session_start();
require '../config/config.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
    exit();
}

if(isset($_GET['id'])){

    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id=:id");
    $stmt->bindParam(':id', $id);

    if($stmt->execute()){
        header('Location: user.php');
        exit();
    }else{
        echo "Delete Failed";
    }

}else{
    echo "Invalid ID";
}
?>