<?php
session_start(); // WAJIB ditaruh paling atas untuk memulai session
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kelompok-empat-main";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) {
    $no_telepon = $_POST['no_telepon'];
    $tanggal_input = $_POST['tanggal'];
    
    date_default_timezone_set('Asia/Jakarta');
    $waktu_ambil = date('H:i:s');
    
    // Logika nomor antrian
    $query_cek = mysqli_query($koneksi, "SELECT MAX(nomor_antrian) as max_nomor FROM antrian WHERE tanggal = '$tanggal_input'");
    $data_cek = mysqli_fetch_assoc($query_cek);
    
    if ($data_cek['max_nomor'] != null) {
        $nomor_antrian = $data_cek['max_nomor'] + 1;
    } else {
        $nomor_antrian = 1;
    }

    $query_insert = "INSERT INTO antrian (no_telepon, tanggal, nomor_antrian, waktu_ambil) 
                     VALUES ('$no_telepon', '$tanggal_input', '$nomor_antrian', '$waktu_ambil')";
    
    $simpan = mysqli_query($koneksi, $query_insert);

    if ($simpan) {
        $id_baru = mysqli_insert_id($koneksi);
        
        // --- TAMBAHAN BARU: Simpan ID ke Session agar diingat sistem ---
        if(!isset($_SESSION['antrian_saya'])) {
            $_SESSION['antrian_saya'] = array(); // Buat array jika belum ada
        }
        // Masukkan ID antrian baru ke dalam daftar antrian milik user ini
        $_SESSION['antrian_saya'][] = $id_baru;
        
        // Redirect
        header("Location: daftarantrian.php");
        exit();
    } else {
        echo "<script>alert('Gagal mengambil antrian: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Horizon Service Center - Pendaftaran</title>
    <link rel="stylesheet" href="ambilantrian.css" />
  </head>

  <body>
    <div class="sidebar">
      <div class="logo-container">
        <img src="logo.png" alt="Logo"/>
      </div>

      <ul class="nav-links">
        <li><a href="ambilantrian.php">Ambil Antrian</a></li>
        <li><a href="daftarantrian.php">Daftar Antrian</a></li>
        <li><a href="kartuantrian.php">Kartu Antrian</a></li>
      </ul>

      <div class="sidebar-footer"></div>
    </div>

    <div class="main-content">
      <div class="header">PENDAFTARAN PELANGGAN</div>

      <div class="form-container">
        <form method="POST" action="">
            <div class="card">
              <div class="input-group">
                <input type="text" name="no_telepon" placeholder="No. Telepon" required />
              </div>

              <div class="input-group">
                <input type="date" name="tanggal" required />
              </div>

              <button type="submit" name="submit" class="submit-btn">SUBMIT</button>
            </div>
        </form>
      </div>
    </div>
  </body>
</html>
