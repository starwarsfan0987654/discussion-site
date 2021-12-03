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
function generateRandomString($length = 10) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
echo  generateRandomString();  // OR: generateRandomString(24)
$salt = generateRandomString(15);
$salt2 = generateRandomString(15);
?>
<div class="home">
<html>
<title>Register</title>
<head>
<h1>Register</h1>
<form action="/index.php">
<input type="submit" value="home">
</form>
</head>
<body>
    <form method="post" id="reg">
        <label for="username">Username</label><br>
        <input type="text" id="userman" name="username"><br>
        <label for="password">Password</label><br>
        <input type="text" id="password" name="password"><br><br>
        <input type="submit" value="Submit" name="Submit">
    </form>
</body>
</html>

<?php
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$sql = "SELECT password, username, ip FROM credentials";
$result = $conn->query($sql);
$formsubmitted=0;
if(isset($_POST['Submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password .= $salt;
    $password = $salt2 . $password;
    
    $data = $password;
    $v = 'haval256,5';
    $hash = hash($v, $data, false);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $dbpassword=$row["password"];
            $dbusername=$row["username"];
            $dbip=$row["ip"];
            if($row["username"] == $_POST['username']){
                $nametaken = true;
            }
        }
    }
}

if($nametaken == true){
    echo "Username taken";
}else{ 
    if (!empty($hash)){
        if(!empty($username)){
            $ciphering = "AES-128-CTR";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $encryption_iv = '1234567891011121';
            $encryption_key2 = "fsp33ax9iR6ZXtQTu7Jf";
            $encsalt = openssl_encrypt($salt, $ciphering, $encryption_key2, $options, $encryption_iv);
            $encsalt2 = openssl_encrypt($salt2, $ciphering, $encryption_key2, $options, $encryption_iv);
            $sql = "INSERT INTO `credentials` (username, password, ip, salt, salt2, admin) VALUES ('$username','$hash','$ip','$encsalt','$encsalt2', 0)";
            if (mysqli_query($conn, $sql)) { 
                echo "Account created successfully";
            }else{
                echo "Error: " . mysqli_error($conn);
            }
        }else{
            if (isset($_POST['username'])){
                echo "Please enter a username";
            }
        }
    }else{
        if(isset($_POST['password'])){
            echo "Please enter a password";
        }
    } 
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