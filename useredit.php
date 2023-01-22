<?php
    session_start();
    ini_set("display_errors", 0);
    error_reporting(~E_ALL);

    unset($_SESSION["user"]);

    $connection = mysqli_connect("localhost", "root", "1234", "test");
    if($err = mysqli_connect_error()) {
        exit($err);
    }

    $user = mysqli_fetch_all(mysqli_query($connection, "select * from users2 where id=".$_GET['userId']));

    $_SESSION["user"]["id"] = $user[0][0];
    $_SESSION["user"]["name"] = $user[0][1];
    $_SESSION["user"]["email"] = $user[0][2];
       
    function getinput($key, $user) {
        if(isset($_SESSION["flash"]["post"][$key])){
            print($_SESSION["flash"]["post"][$key]??'');
        } else {
            if($key === "name") {
                print($user[0][1]);
            } 
            
            if($key === "email") {
                print($user[0][2]);
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User edit</title>
</head>
<body>
    <?php
        if(!isset($user)) {
            exit(print("
                <div id='noUserSelected'>
                    <h1>No user selected.</h1>
                    <a href='userlist.php'>&#60;&#60;  Back to the user list.</a>
                </div>
            "));
        }
    ?>
    <h1>Edit user data</h1>

    <?php
        if(isset($_SESSION["flash"]["msg"])){
    ?>
        <div class="<?php print $_SESSION["flash"]["msg"]["type"]?>">
            <?php 
                foreach($_SESSION["flash"]["msg"]["value"] as $value){
                    print "
                        <div class='msg'>
                            <p>$value</p>
                        </div>
                    ";
                }
            ?>
        </div>
    <?php
        }
    ?>

    <div class="formWrapper">
        <form action="server.php" method="post">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" value="<?php getinput("name", $user)?>"><br>
            <label for="email">Email:</label><br>
            <input type="text" id="email" name="email" value="<?php getinput("email", $user)?>"><br>
            <button>Save</button>
        </form>
        <div>
            <a href='userlist.php'>&#60;&#60;  Back to the user list.</a>
        </div>
    </div>
</body>
</html>
<?php unset($_SESSION["flash"]);?>
