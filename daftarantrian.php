<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kelompok-empat-main";

$koneksi = mysqli_connect($host, $user, $pass, $db);

$punya_antrian = false;
$data_antrian = [];

// Cek session
if (isset($_SESSION['antrian_saya']) && count($_SESSION['antrian_saya']) > 0) {
    $punya_antrian = true;
    $id_list = implode(",", $_SESSION['antrian_saya']);
    
    $query = mysqli_query($koneksi, "SELECT * FROM antrian WHERE id IN ($id_list) ORDER BY tanggal ASC");
    while($row = mysqli_fetch_assoc($query)) {
        $data_antrian[] = $row;
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Horizon Service Center - Daftar Antrian Saya</title>
    <link rel="stylesheet" href="daftarantrian.css" />
</head>
<body>
    <div class="sidebar">
      <div class="logo-container"><img src="logo.png" alt="Logo" /></div>
      <ul class="nav-links">
        <li><a href="ambilantrian.php">Ambil Antrian</a></li>
        <li style="background: #ffffff;"><a style="color: #990000;" href="daftarantrian.php">Daftar Antrian</a></li>
        <li><a href="kartuantrian.php">Kartu Antrian</a></li>
      </ul>
      <div class="sidebar-footer"></div>
    </div>

    <div class="main-content">
      <div class="header">DAFTAR ANTRIAN SAYA</div>
      <div class="form-container">
        
        <?php if ($punya_antrian): ?>
            <div class="cards-wrapper">
                <?php foreach ($data_antrian as $tiket): ?>
                    <div class="ticket-card">
                      <h2>Nomor Antrian Anda</h2>
                      <div class="ticket-body">
                        <div class="ticket-info">
                          <span>No. Telepon</span>
                          <span>: <?php echo htmlspecialchars($tiket['no_telepon']); ?></span>
                        </div>
                        <div class="ticket-date"><?php echo date('d F Y', strtotime($tiket['tanggal'])); ?></div>
                        <div class="ticket-number-box"><?php echo sprintf("%03d", $tiket['nomor_antrian']); ?></div>
                      </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="pesan-kosong">Anda belum mendaftar antrian apapun.</div>
        <?php endif; ?>

        <a href="ambilantrian.php" class="back-btn">BACK</a>
      </div>
    </div>
</body>
</html>