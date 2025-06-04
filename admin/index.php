<?php
session_start();

// === Koneksi ke database ===
$host = "localhost";
$user = "root";
$pass = "";
$db   = "koperasi_db";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// === Proses Login ===
if (isset($_POST['login'])) {
    $username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM user WHERE username='$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Password salah!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan!";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background: url('img/kinockb.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            overflow: hidden;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 320px;
            text-align: center;
            animation: fadeIn 1s ease;
        }
        .login-box h2 {
            margin-bottom: 25px;
            font-size: 24px;
            color: #333;
            font-weight: bold;
            text-transform: uppercase;
        }
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
        }
        .login-box input[type="submit"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .login-box input[type="submit"]:hover {
            background: #218838;
        }
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            font-size: 12px;
            color: white;
            background: rgba(0, 0, 128, 0.7);
            text-align: center;
            padding: 8px 0;
        }
        @keyframes fadeIn {
            0% {opacity: 0; transform: translateY(-20px);}
            100% {opacity: 1; transform: translateY(0);}
        }
        @media (max-width: 400px) {
            .login-box {
                width: 90%;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>LOGIN ADMIN</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <script>alert("<?= $_SESSION['error']; ?>");</script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>
</div>

<footer>
    &copy; 2025 PT. Kino Indonesia Tbk. All rights reserved.
</footer>

</body>
</html>
