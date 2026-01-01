<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warga') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* PROSES UPLOAD */
if (isset($_POST['upload'])) {
    $jadwal_id = $_POST['jadwal_id']; // ID jadwal, bukan tanggal saja
    $tanggal = $_POST['tanggal'];

    if (!empty($_FILES['foto']['name'])) {
        $ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = uniqid().".".$ext;
        
        // Buat folder jika belum ada (safety check)
        if(!is_dir("../uploads/ronda/")) mkdir("../uploads/ronda/", 0777, true);

        if(move_uploaded_file($_FILES['foto']['tmp_name'], "../uploads/ronda/".$foto)){
            
            // Cek apakah sudah ada upload sebelumnya untuk jadwal ini?
            // Kita pakai tanggal sebagai unikan simple atau jadwal_id jika perlu lebih spesifik
            // Disini kita insert baru setiap kali upload
            mysqli_query($conn, "
                INSERT INTO bukti_ronda (user_id, tanggal, foto)
                VALUES ('$user_id', '$tanggal', '$foto')
            ");
            echo "<script>alert('Bukti berhasil diupload'); window.location='jadwal.php';</script>";
        } else {
            echo "<script>alert('Gagal upload file');</script>";
        }
    }
}

// AMBIL JADWAL DARI DATABASE (LIMIT 20 TERBARU)
$query_jadwal = mysqli_query($conn, "SELECT * FROM jadwal_ronda ORDER BY tanggal ASC LIMIT 20");

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwal Ronda Warga</title>
<style>
body{font-family:'Segoe UI', sans-serif; background:#f4f6f9; padding:20px;}
h2{color:#0d6efd;}
a.back{text-decoration:none; font-weight:bold; color:#0d6efd;}

.card-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,.05);
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.date-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.date {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.time-loc {
    font-size: 13px;
    color: #666;
    margin-top: 5px;
}

.petugas {
    background: #eef2f7;
    padding: 10px;
    border-radius: 8px;
    font-size: 14px;
    color: #444;
    line-height: 1.6;
    margin-bottom: 15px;
}

.upload-area {
    margin-top: auto;
    border-top: 1px dashed #ddd;
    padding-top: 15px;
}

button {
    width: 100%;
    padding: 10px;
    background: #0d6efd;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}
button:hover{background:#0b5ed7}

.status-ok {
    text-align: center;
    color: #198754;
    font-weight: bold;
    font-size: 14px;
    background: #d1e7dd;
    padding: 8px;
    border-radius: 8px;
}
img.bukti-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
}
</style>
</head>
<body>

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>📅 Jadwal Ronda</h2>
        <a href="dashboard.php" class="back">← Kembali ke Dashboard</a>
    </div>

    <div class="card-container">
        <?php 
        if(mysqli_num_rows($query_jadwal) > 0){
            while($j = mysqli_fetch_assoc($query_jadwal)){ 
                $tgl = $j['tanggal'];
                
                // Cek apakah user sudah upload bukti u/ tanggal ini?
                $cek_bukti = mysqli_query($conn, "SELECT * FROM bukti_ronda WHERE user_id='$user_id' AND tanggal='$tgl'");
                $bukti = mysqli_fetch_assoc($cek_bukti);
        ?>
            <div class="card">
                <div>
                    <div class="date-box">
                        <div class="date"><?= date('d M Y', strtotime($tgl)) ?></div>
                    </div>
                    
                    <div class="time-loc">
                        🕒 <?= $j['waktu'] ?: '22:00 - Selesai' ?><br>
                        📍 <?= $j['lokasi'] ?: 'Pos Kamling' ?>
                    </div>
                    <br>
                    
                    <label style="font-size:12px; font-weight:bold; color:#888;">PETUGAS / KETERANGAN:</label>
                    <div class="petugas">
                        <?= nl2br($j['keterangan']) ?>
                    </div>
                </div>

                <div class="upload-area">
                    <?php if($bukti){ ?>
                        <div class="status-ok">
                            ✅ Bukti Terkirim
                        </div>
                        <br>
                        <img src="../uploads/ronda/<?= $bukti['foto'] ?>" class="bukti-img">
                    <?php } else { ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="jadwal_id" value="<?= $j['id'] ?>">
                            <input type="hidden" name="tanggal" value="<?= $tgl ?>">
                            
                            <label style="font-size:13px; color:#555;">Upload Bukti:</label>
                            <input type="file" name="foto" required style="width:100%; margin-bottom:10px; margin-top:5px; font-size:12px;">
                            <button name="upload">Kirim Bukti</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        <?php 
            }
        } else {
            echo "<p>Belum ada jadwal ronda yang dibuat admin.</p>";
        } 
        ?>
    </div>

</body>
</html>
