<?php
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$servername = "localhost";
$usernam = "root";
$passwor = "";
$dbname = "users";
session_start();
$username = $_SESSION['username'];
$conn = new mysqli($servername, $usernam, $passwor, $dbname);
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
<title>My Posts</title>
<div class = "home">
<form action="/index.php">
<input type="submit" value="home">
</form>
<h2>My Posts</h2>
</div>
<?php
if(!isset($_SESSION['username'])){
    header("Location: /index3.php");
}
$sql = "SELECT title, description, postedby , likes , id FROM posts";
$result = $conn->query($sql);
echo "<div class=\"grid-container\">";
while($row = $result->fetch_assoc()) {
    if($row['postedby'] == $_SESSION['username']){
        echo"<div class=\"grid-inline\">";
        echo "<h2>" . $row["title"] . "</h2>";
        echo $row["description"];
        echo"<form method=\"get\" name=\"post\">";
        echo"<input type=\"submit\" value=\"".$row["id"]."\" name=\"post\" id=\"post\">";
        echo"</form>";
        echo"<form method=\"post\">";
        echo "<input type=\"hidden\" value=\"". $row["id"] ."\" name=\"postdel\" id=\"postdel\">";
        echo"<input type=\"submit\" value=\"delete post\" name=\"deletepost\" id=\"deletepost\">";
        echo"</form>";
        if($_GET['post'] == $row["id"]){
            $_SESSION['id'] = $row['id'];
            $_SESSION['title']=$row["title"];
            $_SESSION['description']=$row["description"];
            $_SESSION['postedby']=$row["postedby"];
            $_SESSION['likes'] = $row["likes"];
            header("Location: /index8.php?post=". $_SESSION['id']);
        }
    $haveposts = true;
    }
    echo"</div>";
}
echo"</div>";
if(isset($_POST['postdel'])){
    $postid = $_POST['postdel'];
    $sql = "DELETE FROM posts WHERE id=$postid";
    if (!mysqli_query($conn, $sql)) {
        echo "Error";
    }
    $sql = "DELETE FROM comments WHERE commentedon=$postid";
    if (mysqli_query($conn, $sql)) {
        echo "Error";
    }
    $sql = "DELETE FROM liked WHERE liked=$postid";
    if (mysqli_query($conn, $sql)) {
        header("Refresh:0");
    }
}
if ($haveposts == false){
    echo "No Posts";
}
?>
<style>
    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
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