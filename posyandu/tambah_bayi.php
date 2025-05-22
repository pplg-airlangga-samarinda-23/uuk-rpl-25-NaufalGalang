<?php
session_start();
require 'db_connect.php';

// Debugging: Aktifkan error reporting dan log sesi
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_log("Akses tambah_bayi.php: peran=" . (isset($_SESSION['peran']) ? $_SESSION['peran'] : 'tidak ada') . ", id_pengguna=" . (isset($_SESSION['id_pengguna']) ? $_SESSION['id_pengguna'] : 'tidak ada'));

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'Kader') {
    error_log("Redirect ke index.php dari tambah_bayi.php: peran=" . (isset($_SESSION['peran']) ? $_SESSION['peran'] : 'tidak ada'));
    header('Location: /posyandu/index.php');
    exit;
}

$bayi = null;
$error = '';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM bayi WHERE id = ? AND id_kader = ?");
    $stmt->execute([$_GET['id'], $_SESSION['id_pengguna']]);
    $bayi = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $jenis_kelamin = isset($_POST['jenis_kelamin']) ? trim($_POST['jenis_kelamin']) : '';
    $umur = isset($_POST['umur']) ? (int)$_POST['umur'] : 0;
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;

    if (empty($nama) || empty($jenis_kelamin) || $umur <= 0) {
        $error = "Semua field harus diisi dengan benar.";
    } elseif (!in_array($jenis_kelamin, ['Laki-laki', 'Perempuan'])) {
        $error = "Jenis kelamin tidak valid.";
    } else {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE bayi SET nama = ?, jenis_kelamin = ?, umur = ? WHERE id = ? AND id_kader = ?");
                $stmt->execute([$nama, $jenis_kelamin, $umur, $id, $_SESSION['id_pengguna']]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO bayi (nama, jenis_kelamin, umur, id_kader) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nama, $jenis_kelamin, $umur, $_SESSION['id_pengguna']]);
            }
            header('Location: /posyandu/kader_dashboard.php');
            exit;
        } catch (PDOException $e) {
            $error = "Gagal menyimpan data: " . $e->getMessage();
            error_log("Error di tambah_bayi.php: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $bayi ? 'Ubah Bayi' : 'Tambah Bayi'; ?></title>
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
        button, .button-link {
            background: #8000ff;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }
        button:hover, .button-link:hover {
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
    <h2><?php echo $bayi ? 'Ubah Bayi' : 'Tambah Bayi'; ?></h2>
    <?php if ($error) { ?>
        <p class="error"><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo isset($bayi['id']) ? htmlspecialchars($bayi['id']) : ''; ?>">
        <div>
            <label for="nama">Nama</label><br>
            <input type="text" id="nama" name="nama" value="<?php echo isset($bayi['nama']) ? htmlspecialchars($bayi['nama']) : ''; ?>" required>
        </div>
        <div>
            <label for="jenis_kelamin">Jenis Kelamin</label><br>
            <select id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="Laki-laki" <?php echo isset($bayi['jenis_kelamin']) && $bayi['jenis_kelamin'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                <option value="Perempuan" <?php echo isset($bayi['jenis_kelamin']) && $bayi['jenis_kelamin'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
            </select>
        </div>
        <div>
            <label for="umur">Umur (bulan)</label><br>
            <input type="number" id="umur" name="umur" value="<?php echo isset($bayi['umur']) ? htmlspecialchars($bayi['umur']) : ''; ?>" required>
        </div>
        <button type="submit">Simpan</button>
        <a href="/posyandu/kader_dashboard.php" class="button-link">Batal</a>
    </form>
</body>
</html>