<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'Admin') {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM pengguna WHERE peran = 'Kader'");
$kader_list = $stmt->fetchAll();

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM pengguna WHERE id = ? AND peran = 'Kader'");
    $stmt->execute([$id]);
    header('Location: admin_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
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
        a button {
            width: auto;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h2> <?php echo htmlspecialchars($_SESSION['nama']); ?> (Admin)</h2>
    <a href="tambah_kader.php"><button>Tambah Kader</button></a>
    <a href="logout.php"><button>Keluar</button></a>
    <h2>Daftar Kader</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Nama Pengguna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kader_list as $kader) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($kader['nama']); ?></td>
                    <td><?php echo htmlspecialchars($kader['nama_pengguna']); ?></td>
                    <td>
                        <a href="tambah_kader.php?id=<?php echo $kader['id']; ?>"><button>Ubah</button></a>
                        <a href="admin_dashboard.php?hapus=<?php echo $kader['id']; ?>" onclick="return confirm('Apakah Anda yakin?')"><button>Hapus</button></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>