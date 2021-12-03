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
<title>Login</title>
<h1>Login</h1>
<?php
if(!isset($match)){
?>
<form action="/index.php">
<input type="submit" value="home">
</form>
<?php
}
?>
<form method="post">
<label for="username">Username</label><br>
<input type="text" id="username" name="username"><br>
<label for="password">Password</label><br>
<input type="text" id="password" name="password"><br><br>
<input type="submit" name="login" value="Log in">
</form>
</html>
<?php
$sql = "SELECT password, username, ip, salt, salt2 FROM credentials";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    $salt = $row["salt"];
    $salt2 = $row["salt2"];
    $password = $_POST['password'];
    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $decryption_iv = '1234567891011121';
    $decryption_key2 = "35378883796257572734";
    $decsalt = openssl_decrypt($salt, $ciphering, $decryption_key2, $options, $decryption_iv);
    $decsalt2 = openssl_decrypt($salt2, $ciphering, $decryption_key2, $options, $decryption_iv);
    $readyhash = $decsalt2 . $password . $decsalt;
    $data = $readyhash;
    $v = 'haval256,5';
    $hash = hash($v, $data, false);
    if($row["username"] == $_POST['username']){
        if($hash == $row["password"]){
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['password'] = $hash;
        }
    }
}
if(isset($_SESSION['username'])){
    $match = true;
}
if($match == true){
    echo "Logged in <br>";
    echo "<form action=\"index.php\">";
    echo "<input type=\"submit\" value=\"home\">";
    echo "</form>";
} else if(isset($_POST['username'])){
    echo "Incorrect Credentials";
}
if(isset($_POST['redir'])){
    header("Location: /index3.php");
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