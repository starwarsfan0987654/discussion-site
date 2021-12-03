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
<title>My Subreddits</title>
<div class = "home">
<form action="/index10.php">
<input type="submit" value="Subreddits">
</form>
<h2>My Subreddits</h2>
</div>
<?php
if(!isset($_SESSION['username'])){
    header("Location: /index3.php");
}
$sql = "SELECT * FROM subreddits";
$result = $conn->query($sql);
echo "<div class=\"grid-container\">";
while($row = $result->fetch_assoc()) {
    if($row['createdby'] == $_SESSION['username']){
        echo"<div class=\"grid-inline\">";
        echo "<h2>" . $row["subname"] . "</h2>";
        echo $row["subdesc"];
        echo"<form method=\"get\" name=\"sub\">";
        echo"<input type=\"submit\" value=\"".$row["subname"]."\" name=\"sub\" id=\"sub\">";
        echo"</form>";
        echo"<form method=\"post\">";
        echo "<input type=\"hidden\" value=\"". $row["subname"] ."\" name=\"postdel\" id=\"postdel\">";
        echo"<input type=\"submit\" value=\"delete sub\" name=\"deletepost\" id=\"deletepost\">";
        echo"</form>";
        if($_GET['sub'] == $row["subname"]){
            header("Location: /index11.php?sub=". $row["subname"]);
        }
    $haveposts = true;
    }
    echo"</div>";
}
echo"</div>";
if(isset($_POST['postdel'])){
    $postid = $_POST['postdel'];
    $sql = "DELETE FROM subreddits WHERE subname='$postid'";
    if (!mysqli_query($conn, $sql)) {
        echo "Error";
    }else{
        header("Refresh:0");
    }
}
if ($haveposts == false){
    echo "No Subreddits";
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