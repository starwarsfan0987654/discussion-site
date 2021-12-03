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
<h1>Delete Account</h1>
<form method="post">
<input type="submit" value="Confirm Account Deletion" name="deleteconf" id="deleteconf">
</form>
<form method="post">
<input type="submit" value="back" name="back" id="back">    
</form>
</html>
<?php
if(isset($_SESSION['username'])){
    $username= $_SESSION['username'];
    $password= $_SESSION['password'];
} else{
    header("Location: /index2.2.php");
}
if(isset($_POST['deleteconf'])){
    $sql = "DELETE FROM credentials WHERE username='$username'";
    if (!mysqli_query($conn, $sql)) {
        echo "Error";
    }
    $sql = "DELETE FROM posts WHERE postedby='$username'";
    if (!mysqli_query($conn, $sql)) {
        echo "Error";
    }
    $sql = "DELETE FROM liked WHERE likedby='$username'";
    if (!mysqli_query($conn, $sql)) {
        echo "Error";
    }
    $sql = "DELETE FROM comments WHERE commentedby='$username'";
    if (!mysqli_query($conn, $sql)) {
        echo "Error";
    }
    $sql = "DELETE FROM subreddits WHERE createdby='$username'";
    if (!mysqli_query($conn, $sql)) {
        echo "Error";
    }
    $sql = "DELETE FROM blocked WHERE blockedby='$username'";
    if (mysqli_query($conn, $sql)) {
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        header("Location: /index3.php");
        exit();
    } else {
        echo "Error";
    }
}
if(isset($_POST['back'])){
    unset($_SESSION['index6']);
    header("Location: /index6.php");
    exit();
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
    input[type=text] {
        width: 20%;
        padding: 12px 20px;
        margin: 4px 0;
        box-sizing: border-box;
    }
</style>