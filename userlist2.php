<?php
    session_start();
    ini_set("display_errors", 0);
    error_reporting(~E_ALL);

    $connection = mysqli_connect("localhost", "root", "1234", "test");
    if($err = mysqli_connect_error()) {
        exit($err);
    }
    $users = mysqli_fetch_all(mysqli_query($connection, "select * from users2"));
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User List</title>
</head>
<body>
    <h1>User List</h1>
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
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Date of registration</th>
                <th>Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $key1 => $user){ 
            ?>
                <tr>
                    <?php
                        foreach($user as $key2 => $userdata){
                            if($key2 === 1 or $key2 === 2 or $key2 === 3) {
                            print "<td>$userdata</td>";
                            }
                        }
                    ?>
                    <td> 
                        <div class="btns">
                            <a href="<?php print('http://localhost/full-stack-course/Feladatok/PHP_temazaro_feladatok/useredit.php?userId='.$user[0]) ?>">
                                <button>Edit</button>
                            </a>
                            <a href="<?php print('http://localhost/full-stack-course/Feladatok/PHP_temazaro_feladatok/server.php?userId_to_del='.$user[0]) ?>">
                                <button>Delete</button>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php unset($_SESSION["flash"]);?>