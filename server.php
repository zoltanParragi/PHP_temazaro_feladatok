<?php
    session_start();
    ini_set("display_errors", 0);
    error_reporting(~E_ALL);

    
    if($_SERVER["REQUEST_METHOD"] !== 'POST' and !isset($_GET['userId_to_del'])) {
        http_response_code(403);
        exit;
    }

    $connection = mysqli_connect("localhost", "root", "1234", "test");
    if($err = mysqli_connect_error()) {
        exit($err);
    }

    if(isset($_GET['userId_to_del'])) {        
        mysqli_query($connection, "delete from users2 where id='".$_GET['userId_to_del']."' limit 1");
    
        if($err = mysqli_error($connection)){
            exit($err);
        }
    
        $_SESSION["flash"]["msg"] = ['value' => ['Sikeres törlés'], 'type' => 'successmsg'];

        header("location: ". $_SERVER["HTTP_REFERER"]);
        exit();
    }

    $post = $_POST;
    extract($post);

    $errors = Array();

    $length = mb_strlen(trim($name), 'UTF-8');
    if($length < 4 or $length > 30) {
        $errors[] = "A név minimási hossza 4 karakter. <br>";
        $_SESSION["user"]["name"] = $name;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Az email cím formátuma nem megfelelő. <br>";
        $_SESSION["user"]["email"] = $email;
    } else {
        $result = mysqli_query($connection, "select id from users2 where email ='$email' and id !='".$_SESSION["user"]["id"]."'");
        $found = mysqli_num_rows($result);
        if( $found ) {
            $errors[] = "Az email cím már foglalt.";
        }
    }

    if(count($errors) > 0){
        $_SESSION["flash"]["post"] = $post;
        $_SESSION["flash"]["msg"] = ['value' => $errors, 'type' => 'errormsg'];
    } else {
        $email = mysqli_real_escape_string($connection, $email);
        $name = mysqli_real_escape_string($connection, $name);

        mysqli_query($connection, "update users2 set name='$name', email='$email' where id='".$_SESSION["user"]["id"]."' limit 1");
    
        if($err = mysqli_error($connection)){
            exit($err);
        }
    
        $_SESSION["flash"]["msg"] = ['value' => ['Sikeres módosítás'], 'type' => 'successmsg'];
    }

    header("location: ". $_SERVER["HTTP_REFERER"]);
