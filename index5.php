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
<?php
$blockedby = $_SESSION['username'];
$blockedto = $_GET['username'];
$sql = "SELECT username FROM credentials";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if($row["username"] == $_GET['username']){
        $account = true;
    }
}
$sql = "SELECT blockedto FROM blocked";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if($row["blockedto"] == $_GET['username']){
        $userblocked = true;
    }
}
if(isset($_POST['unblock'])){
    $sql = "DELETE FROM blocked WHERE blockedto='$blockedto'";
    if (mysqli_query($conn, $sql)) {
        $userblocked=false;
    }
}
if(isset($_POST['block'])){
    $sql = "INSERT INTO `blocked` (blockedby, blockedto) VALUES ('$blockedby','$blockedto')";
    if (mysqli_query($conn, $sql)) {
        $userblocked=true;
    } 
}
if($account == true){
    if($_SESSION['username'] == $_GET['username']){
        echo "My account";
    }else{
        echo $_GET['username'];
        if($userblocked == false){
?>
<form method="post">
    <input type="submit" value="block" id="block" name="block">
</form>
<?php
        } else{
?>
<form method="post">
     <input type="submit" value="un-block" id="unblock" name="unblock">
</form>
<?php
        }
    }
}elseif(isset($_GET['username'])){
    echo "No account here";
}

if(!isset($_GET['username']) || empty($_GET['username'])){
?>
<title>Profiles</title>
<form action="/index.php">
<input type="submit" value="home">
</form>
Search Account
<form method="get" name="username">
<input type="text" name="username">
<input type="submit">
</form>
<?php
}else{
?>
<form action="/index.php">
<input type="submit" value="home">
</form>
</div>
<br>
<br>
<?php
}
echo "<h2>Posts:</h2>";
$sql = "SELECT title, description, postedby , likes , id FROM posts";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if($row['postedby'] == $_GET['username']){
        echo "<div class=\"posts\">";
        echo $row["title"] . "<br>";
        echo $row["description"];
        echo"<form method=\"get\" action=\"index8.php\" name=\"post\">";
        echo"<input type=\"submit\" value=\"".$row["id"]."\" name=\"post\" id=\"post\">";
        echo"</form>";
        echo "</div><br>";
    }
}
$sql = "SELECT * FROM comments";
$result = $conn->query($sql);
echo "<h2>Comments:</h2>";
while($row = $result->fetch_assoc()) {
    if($row["commentedby"] == $_GET['username']){
        echo "<div class=\"posts\">";
        echo $row["comment"] , "<br>";
        echo "commented on: ";
        echo $row["commentedon"];
        echo"<form method=\"get\" action=\"index8.php\" name=\"post\">";
        echo"<input type=\"submit\" value=\"".$row["commentedon"]."\" name=\"post\" id=\"post\">";
        echo"</form>";
        echo "</div><br>";
    }
}
?>

<style>
    .posts{
        text-align: center;
        border-style: solid;
        border-width: 2px;
        padding-bottom: 3px;
        padding-top: 5px;
    }
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