<?php
session_start();


require "./vendor/autoload.php";
include "database.php";

// kullanıcı giriş yapmış mı kontrol ediyoruz
if (isset($_SESSION["id"]) && isset($_SESSION["username"]) && isset($_SESSION["email"])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["re_password"])) {
    // verideki özel karakterleri temizler
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // benzersiz, 6 haneli sayılardan oluşan doğrulama kodu oluşturur
    function generateVCode($length = 6)
    {
        $chars = '0123456789';
        $charsLength = strlen($chars);
        $vcode = '';
        for ($i = 0; $i < $length; $i++) {
            $vcode .= $chars[mt_rand(0, $charsLength - 1)];
        }
        return $vcode;
    }

   





    // verilerdeki özel karakterleri temizliyoruz
    $username = validate($_POST["username"]);
    $email = validate($_POST["email"]);
    $password = validate($_POST["password"]);
    $re_password = validate($_POST["re_password"]);

 

    // veri boşluk kontrolü yapıyoruz
    if (empty($username)) {
        header("Location: signup.php?error=username_empty");
        exit();
    } else if (empty($email)) {
        header("Location: signup.php?error=email_empty");
        exit();
    } else if (empty($password)) {
        header("Location: signup.php?error=password_empty");
        exit();
    } else if (empty($re_password)) {
        header("Location: signup.php?error=re_password_empty");
        exit();
    } else if ($password != $re_password) {
        header("Location: signup.php?error=passwords_not_matched");
        exit();
    } else {
        $password = md5($password); // şifreyi 'md5()' fonksiyonu ile şifreliyoruz

        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            header("Location: signup.php?error=Bu e posta kullanılıyor!");
            exit();
        } else {
            $query = "INSERT INTO users(id, username, email, password, verified) VALUES ('', '$username', '$email', '$password', '0')";
            $result = mysqli_query($conn, $query); // veritabanına kullanıcıyı ekliyoruz

            
            
            
            
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" type="text/css" href="./css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <form action="./signup.php" method="POST">
        <h3>Kayıt Ol</h3>
        <?php if (isset($_GET["success"]) && !empty($_GET["success"])) { ?>
            <div class="success"><?php echo $_GET["success"]; ?></div>
        <?php } ?>
        <?php if (isset($_GET["error"]) && !empty($_GET["error"])) { ?>
            <div class="error"><?php echo $_GET["error"]; ?></div>
        <?php } ?>
        <label for="username">Kullanıcı Adı</label>
        <input type="text" name="username" placeholder="Kullanıcı Adı" required>
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="Email" required>
        <label for="password">Şifre</label>
        <input type="password" name="password" placeholder="Şifre" required>
        <label for="re_password">Şifre Tekrar</label>
        <input type="password" name="re_password" placeholder="Şifre Tekrar" required>
        <button>Kayıt Ol</button>
        <p>Zaten hesabın var mı? <a href="./login.php">Giriş Yap</a></p>

    </form>
</body>

</html>