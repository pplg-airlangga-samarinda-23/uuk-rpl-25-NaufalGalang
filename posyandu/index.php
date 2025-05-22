<?php
session_start();

// Debugging: Tampilkan nilai sesi untuk memeriksa (hapus setelah pengujian)
if (isset($_SESSION['peran'])) {
    error_log("Session peran: " . $_SESSION['peran']);
}

// Jika sudah login, arahkan ke dashboard sesuai peran
if (isset($_SESSION['peran'])) {
    if ($_SESSION['peran'] == 'Admin') {
        header('Location: admin_dashboard.php');
        exit;
    } elseif ($_SESSION['peran'] == 'Kader') {
        header('Location: kader_dashboard.php');
        exit;
    } else {
        // Jika peran tidak valid, hapus sesi dan tetap di index.php
        session_destroy();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di Aplikasi Posyandu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #add8e6;
            text-align: center;
            padding: 50px;
        }
        input, select, textarea, button {
            padding: 10px;
            margin: 5px;
            width: 200px;
            box-sizing: border-box;
        }
        button {
            background: #8000ff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #6600cc;
        }
        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 80%;
            background: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background: #8000ff;
            color: white;
        }
        h2 {
            color: #333;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            color: white;
        }
        a button {
            width: auto;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h2>Selamat Datang di Aplikasi Posyandu</h2>
    <p>Pilih opsi login di bawah ini:</p>
    <a href="login_admin.php"><button>Login sebagai Admin</button></a>
    <a href="login_kader.php"><button>Login sebagai Kader</button></a>
</body>
</html>