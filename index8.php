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
<?php
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
<title>Posts</title>
<div class= "home">
<?php 
echo "<form method=\"post\">";
echo "<input type=\"submit\" value=\"home\" name=\"home\" id=\"home\">";
echo "</form>";
$sql = "SELECT title, description, postedby , likes , id FROM posts";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if($_GET['post'] == $row["id"]){
        $postlikes = $row["likes"];
        $postid = $row["id"];
        $_SESSION['title'] = $row["title"];
        $_SESSION['description'] = $row["description"];
        $_SESSION['postedby'] = $row["postedby"];
        $_SESSION['likes'] = $row['likes'];
        $idmatch = true;

    }
}
if($idmatch == false){
    header("Location: /index.php");
}
echo $_SESSION['title']."<br>";
echo $_SESSION['description']."<br>";
echo "<br> likes: ".$_SESSION['likes']."<br>";
echo "posted by ".$_SESSION['postedby'];
$sql = "SELECT liked, likedby FROM liked";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if($row["likedby"] == $_SESSION['username']){
        if($row["liked"] == $postid){
            $liked = true;  

        }
    }
}

if(isset($_POST['home'])){
    header("Location: /index.php");
    die();
}
$username = $_SESSION['username'];
if(isset($_POST['unlike'])){
    unset($_POST['like']);
    $newlikes2=$postlikes-1;
    $sql ="UPDATE posts SET likes = $newlikes2 WHERE id=$postid";
    if (mysqli_query($conn, $sql)) {
        $liked = false;;
    }
    $sql="DELETE FROM liked WHERE liked=$postid && likedby='$username'";if (mysqli_query($conn, $sql)) {
        header("Refresh:0");
    }
}
if(isset($_POST['like'])){
    $newlikes=$postlikes+1;
    $sql ="UPDATE posts SET likes = $newlikes WHERE id=$postid";
    if (mysqli_query($conn, $sql)) {
        $liked = true;
    }
    $sql = "INSERT INTO `liked` (liked, likedby) VALUES ($postid,'$username')";
    if (!mysqli_query($conn, $sql)) {
        echo "error";

    }else{
        header("Refresh:0");
    }
}
if(isset( $_SESSION['username'])){
    if($liked == false){
    ?>
    <form method="post">
    <input type="submit" value="like post" name="like" id="like">
    </form>
<?php
    }else{
?>
    <form method="post">
    <input type="submit" value="unlike post" name="unlike" id="unlike">
    </form>
<?php
    }
?>
<form method="post">
    <label for="comment">Comment:</label>
    <input type="text" name="comment" id="comment">
    <input type="submit">
</form>
<?php
}
if(isset($_POST['comment'])){
    $comment = $_POST['comment'];
    if(!empty($_POST['comment'])){
        $sql ="INSERT INTO `comments` (commentedon, commentedby, comment) VALUES ($postid,'$username','$comment', 0)";
        if (mysqli_query($conn, $sql)) {
            header("Refresh:0");
        }
    }
}
?>
</div>
<div class="home2">

<?php
$sql = "SELECT username, admin FROM credentials";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if($_SESSION['username'] == $row["username"]){
        if($row["admin"] == 1){
            $admin = 1;
        }else{
            $admin = 0;
        }
    }
}
$sql = "SELECT commentedon, commentedby, comment, commentid FROM comments";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if($row["commentedon"] == $postid){
        echo $row['comment'] . "<br>";
        echo "commented by " . $row["commentedby"];
        echo "<br>";
        if($row['commentedby'] == $username || $admin==1){
            $var1 = true;
            echo"<form method=\"post\">";
            echo "<input type=\"hidden\" value=\"". $row["commentid"] ."\" name=\"commdel\" id=\"commdel\">";
            echo"<input type=\"submit\" value=\"delete comment\" name=\"deletecom\" id=\"deletecom\">";
            echo "</form>";
        }else{
            echo "<br>";
        }
    }
    
}
if(isset($_POST['commdel'])){
    $commentid = $_POST['commdel'];
    $sql = "DELETE FROM comments WHERE commentid = $commentid";
    if (mysqli_query($conn, $sql)) {
        header("Refresh:0");
    }
}

?>
</div>
<style>
    .home2 {
        text-align: center;
        padding-top: 5px;
        padding-bottom: 3px;
        border-style: solid;
        border-width: 2px;
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