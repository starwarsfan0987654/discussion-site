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
<h2>New Subreddit</h2>
<form method="post">
<input type="submit" value="home" id="home" name="home">
</form>
<form method="post">
<input type="submit" value="subreddits" name="subs" id="subs">
<input type="submit" value="Your Subreddits" name="ursubs" id="ursubs">
</form>
<title>Create Subreddit</title>
<form method="post"><br>
    <label for="title">Sub name: </label><br>
    <input type="text" id="title" name="title"><br>
    <label for="description">Sub description: </label><br>
    <input type="text" id="description" name="description"><br>
    <input type="submit" id="submit" name="submit">
</form>
<?php
if(isset($_POST['home'])){
    header("Location: /index.php");
}
if(isset($_POST['ursubs'])){
    header("Location: /index12.php");
}
if(isset($_POST['subs'])){
    header("Location: /index11.php");
}
if(isset($_POST['submit'])){
    $titlevar = $_POST['title'];
    $descvar = $_POST['description'];
    $postedby = $_SESSION['username'];
    if(!empty($titlevar)){
        $sql ="INSERT INTO `subreddits` (subname, subdesc, createdby) VALUES ('$titlevar','$descvar','$postedby')";
        if (mysqli_query($conn, $sql)) {
            echo "done";
        }
    }else{
        echo "<br>Please enter a title";
    }
}
?>
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