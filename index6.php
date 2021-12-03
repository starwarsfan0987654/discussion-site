<?php
$servername = "localhost";
$usernam = "root";
$passwor = "";
$dbname = "users";
session_start();
$conn = new mysqli($servername, $usernam, $passwor, $dbname);
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$sql = "SELECT ip FROM `banned-ips`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
       if($ip == $row["ip"]){
           die("you have banned");
       }
    }
}
?>
<div class="home">
<html>
<meta name="viewport" content="width=device-width, initial-scale=2.0">
<title>Settings</title>
<form method="post">
<input type="submit" value="home" id="home" name="home">
</form>
<h1>Settings</h1>
<?php

if(isset($_SESSION['username'])){
?>
<form method="post">
<input type="submit" value="log out" id="logout" name="logout">
</form>
<form method="post">
<input type="submit" value="delete account" name="delete">
</form>
<?php
$sql ="SELECT * FROM credentials";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if($row["username"] == $_SESSION["username"] && $row["admin"] == 1){
        echo "<form action=\"admin.php\"><input type=\"submit\" value=\"admin console\"></form>";
    }
}
?>
<form method="get" action="/index5.php">
<label for="username"> Search Accounts: </label>
<input type="search" id="username" name="username">
<input type="submit">
</form>
</html>
<?php
}else{
    ?>
<form method="post">
<input type="submit" value="Log in" name="loginpage" id="loginpage">
<input type="submit" value="Register" name="regpage" id="regpage">
</form>
<?php
}
if(isset($_POST['logout'])){
    unset($_SESSION['username']);
    header("Location: /index.php");
}
if(isset($_POST['delete'])){
    header("Location: /index4.php");
}
if(isset($_POST['loginpage'])){
    $_SESSION['backhome'] = true;
    header("Location: /index2.2.php");
}
if(isset($_POST['regpage'])){
    $_SESSION['backhome'] = true;
    header("Location: /index3.php");
}
if(isset($_POST['home'])){
    header("Location: /index.php");
}
?>
</div>
<style>
    .home {
        text-align: center;
        border-style: solid;
        border-width: 5px;
        padding-bottom: 3px;
    }
    input[type=submit] {
        width: 20%;
        padding: 12px 20px;
        margin: 4px 0;
        box-sizing: border-box;
    }
    input[type=search] {
        width: 20%;
        padding: 12px 20px;
        margin: 4px 0;
        box-sizing: border-box;
    }
</style>