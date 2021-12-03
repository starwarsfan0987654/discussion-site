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
<title>New Post</title>
<div class="home">
<form method="post">
<input type="submit" value="back" id="back" name="back">
</form>
<h2>New Post</h2>
<form method="post"><br>
    <label for="title">Title: </label><br>
    <input type="text" id="title" name="title"><br>
    <label for="description">Description: </label><br>
    <input type="text" id="description" name="description"><br>
    <label for="description">Subreddit: </label><br>
    <input type="text" id="subreddit" name="subreddit"><br>
    <input type="submit" id="submit" name="submit">
</form>
<?php
if(isset($_SESSION['subreddit'])){
    $subreddit = $_SESSION['subreddit'];
    if(isset($_POST['back'])){
        
        header("Location: /index10.php?sub=$subreddit");
    }
}else{
    $subreddit = "home";
    if(isset($_POST['back'])){
        header("Location: /index.php");
    }
    $subreddit = $_SESSION['subreddit'];
}
if(isset($_POST['submit'])){
    $titlevar = $_POST['title'];
    $descvar = $_POST['description'];
    $postedby = $_SESSION['username'];
    $subreddit = $_POST['subreddit'];
    if(!empty($titlevar)){
        $sql ="INSERT INTO `posts` (title, description, postedby, likes, postedin) VALUES ('$titlevar','$descvar','$postedby',0, '$subreddit')";
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