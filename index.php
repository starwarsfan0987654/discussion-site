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
<html>
<div class="home">
<title>Home</title>
<head>

<h1>Home</h1>
<?php
if(isset($_SESSION['username'])){
    echo "<h2>Hello ". $_SESSION['username'] ."</h2>";
}else{
?>
<form method="post">
<input type="submit" value="Log in" name="loginpage" id="loginpage">
<input type="submit" value="Register" name="regpage" id="regpage">
</form>
<?php
}
if(isset($_POST['loginpage'])){
    $_SESSION['backhome'] = true;
    header("Location: /index2.2.php");
}
if(isset($_POST['regpage'])){
    $_SESSION['backhome'] = true;
    header("Location: /index3.php");
}
?>
</html>
<form method="post">
<input type="submit" value="settings" id="settings" name="settings">
</form>
</div>
<form action="index11.php">
<input type="submit" value="Subreddits" id="subs" name="subs">
</form>
<?php

if(isset($_POST['settings'])){
    header("Location: /index6.php");
    die();
}

echo "<h2>Posts:</h2>";
if(isset($_SESSION['username'])){
?>

<form method="post">
<input type="submit" value="New Post" id="create" name="create">
<input type="submit" value="My Posts" id="myposts" name="myposts">
</form>
<?php
}
if(isset($_POST['myposts'])){
    header("Location: /index9.php");
    die();
}
if(isset($_POST['create'])){
    if(isset($_SESSION['subreddit'])){
        unset($_SESSION['subreddit']);
    }
    header("Location: /index7.php");
    die();
}

$sql = "SELECT blockedby, blockedto FROM `blocked`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
       if($row["blockedby"] == $_SESSION['username']){
           $blockedto = $row["blockedto"];
       }
    }
}else{
}
$sql = "SELECT title, description, postedby , likes , id FROM posts";
$result = $conn->query($sql);
echo "<div class=\"grid-container\">";
while($row = $result->fetch_assoc()) {
    if($blockedto !== $row["postedby"]){
        echo"<div class=\"grid-inline\">";
        echo"<form method=\"post\" name=\"post\">";
        echo"<div class=\"button\">";
        echo "<input type=\"hidden\" value=\"". $row["id"] ."\" name=\"post2\" id=\"post2\">";
        echo"<input type=\"submit\" value=\"" . $row["title"] . "\" name=\"post\" id=\"post\"><br>";
        echo"</div>";
        echo "user: " . $row["postedby"];
        echo "<br>likes:" . $row["likes"];
        echo "</form>";
        echo"</div>";
        if(isset($_POST['post'])){
            header("Location: /index8.php?post=". $_POST['post2']);
        }
    }
    if($_GET['post'] == $row["id"]){
        $_SESSION['id'] = $row['id'];
        $_SESSION['title']=$row["title"];
        $_SESSION['description']=$row["description"];
        $_SESSION['postedby']=$row["postedby"];
        $_SESSION['likes'] = $row["likes"];
        header("Location: /index8.php?post=". $_SESSION['id']);
    }
}
echo"</div>";
?>
</head>
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        padding-right: 0;
        grid-gap: 5px;
        align-content: center;
    }
    .grid-inline {
        border-style: solid;
        border-width: 2px;
        text-align: center;
    }
    .home {
        text-align: center;
        border-style: solid;
        border-width: 5px;
    }
    input[type=submit] {
        width: 20%;
        padding: 12px 20px;
        margin: 4px 0;
        box-sizing: border-box;
    }
    input[type=text] {
        width: 20%;
        padding: 12px 20px;
        margin: 4px 0;
        box-sizing: border-box;
    }
</style>

