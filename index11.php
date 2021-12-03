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
<div class= "home">
<?php 
if(!empty($_GET['sub']) && isset($_GET['sub'])){
    $sql = "SELECT * FROM subreddits";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        if($_GET['sub'] == $row["subname"]){
            echo "<title>s/" . $_GET['sub'] ."</title>";
            $subreddit = $_GET['sub'];
            echo "<h1>" . $_GET['sub'] . "</h1>";
            echo "<form method=\"post\">";
            echo "<input type=\"submit\" value=\"Subreddits\" name=\"subs\" id=\"subs\">";
            echo "<input type=\"submit\" value=\"New Post\" name=\"newpost\" id=\"newpost\">";
            echo "</form>";
        }
    }
    echo $subreddit;
    if(isset($subreddit)){
        $sql = "SELECT * from posts";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            echo "<div class=\"grid-container\">";
            if($row["postedin"] == $subreddit){
                echo"<div class=\"grid-inline\">";
                echo "<h2>" . $row["title"] . "</h2>";
                echo $row["description"];
                echo"<form method=\"get\" action=\"index8.php\" name=\"post\">";
                echo"<input type=\"submit\" value=\"".$row["id"]."\" name=\"post\" id=\"post\">";
                echo"</form>";
                echo"</div>";
            }
            echo"</div>";
        }
    }
}
if(!isset($subreddit)){
    echo "<h1>Subreddits</h1>";
    echo "<form method=\"post\">";
    echo "<input type=\"submit\" value=\"home\" name=\"home\" id=\"home\">";
    echo "<input type=\"submit\" value=\"create subreddit\" name=\"createsub\" id=\"createsub\">";
    echo "</form>";
    echo "</div>";
    echo "<div class=\"home\"><br>";
    $sql = "SELECT * from subreddits";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()) {
        if(!empty($row["subname"])){
            echo "<form method=\"get\">";
            echo "<input type=\"submit\" value=\"".$row["subname"]."\" name=\"sub\" id=\"sub\">";
        }
    }
    echo "</form>";
}
if (isset($_POST['home'])){
    header("Location: /index.php");
}
if (isset($_POST['subs'])){
    header("Location: /index11.php");
}
if (isset($_POST['createsub'])){
    header("Location: /index10.php");
}
if (isset($_POST['newpost'])){
    $_SESSION['subreddit'] = $_GET['sub'];
    header("Location: /index13.php");
}
?>
</div>
<style>
    .grid-container {
        display: grid;
        padding-right: 0;
        grid-gap: 5px;
        align-content: center;
        padding-top: 5px;
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
        padding-bottom: 3px;
    }
    input[type=submit] {
        width: 20%;
        padding: 12px 20px;
        margin: 0 0;
        box-sizing: border-box;
    }
    input[type=text] {
        width: 20%;
        padding: 12px 20px;
        margin: 4px 0;
        box-sizing: border-box;
    }
    h2{
        margin: 0;
    }
</style>