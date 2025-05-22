<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['peran']) || $_SESSION['peran'] != 'Kader') {
    header('Location: /posyandu/index.php');
    exit;
}

$stmt->execute([$_SESSION['id_pengguna']]);
$bayi_list = $stmt->fetchAll();

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM bayi WHERE id = ? AND id_kader = ?");
    $stmt->execute([$id, $_SESSION['id_pengguna']]);
    header('Location: /posyandu/kader_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kader</title>
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
    <h2>Selamat datang, <?php ($_SESSION['nama']); ?> (Kader)</h2>
    <a href="tambah_bayi.php" class="button-link">Tambah Bayi</a>
    <a href="logout.php" class="button-link">Keluar</a>
    <h2>Daftar Bayi</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Umur</th>
                <th>Berat Badan (kg)</th>
                <th>Tinggi Badan (cm)</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
    </body>