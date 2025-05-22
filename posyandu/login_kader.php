<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = isset($_POST['nama_pengguna']) ? trim($_POST['nama_pengguna']) : '';
    $kata_sandi = isset($_POST['kata_sandi']) ? trim($_POST['kata_sandi']) : '';

    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE nama_pengguna = ? AND kata_sandi = ? AND peran = 'Kader'");
    $stmt->execute([$nama_pengguna, $kata_sandi]);
    $pengguna = $stmt->fetch();

    if ($pengguna) {
        $_SESSION['id_pengguna'] = $pengguna['id'];
        $_SESSION['peran'] = $pengguna['peran'];
        $_SESSION['nama'] = $pengguna['nama'];
        header('Location: kader_dashboard.php');
        exit;
    } else {
        $error = "Nama pengguna atau kata sandi salah, atau Anda bukan Kader.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kader - Posyandu</title>
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
    </style>
</head>
<body>
    <h2>Login Kader</h2>
    <form method="POST">
        <div>
            <label for="nama_pengguna">Nama Pengguna</label><br>
            <input type="text" id="nama_pengguna" name="nama_pengguna" required>
        </div>
        <div>
            <label for="kata_sandi">Kata Sandi</label><br>
            <input type="password" id="kata_sandi" name="kata_sandi" required>
        </div>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <button type="submit">Masuk</button>
    </form>
    <a href="index.php"><button>Kembali</button></a>
</body>
</html>