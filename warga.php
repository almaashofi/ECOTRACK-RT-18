<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role']!='admin') {
    header("Location: ../auth/login.php"); exit;
}

// 1. UPDATE DATA
if (isset($_POST['update_warga'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_wa = mysqli_real_escape_string($conn, $_POST['no_wa']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    // Password (opsional)
    if (!empty($_POST['password'])) {
        $password = $_POST['password']; // Ingat: Login admin sekarang plaintext juga
        $query_pw = ", password='$password'";
    } else {
        $query_pw = "";
    }

    $q = "UPDATE users SET nama='$nama', email='$email', no_wa='$no_wa', alamat='$alamat' $query_pw WHERE id='$id'";
    
    if (mysqli_query($conn, $q)) {
        echo "<script>alert('Data warga berhasil diupdate');window.location='warga.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal update: ".mysqli_error($conn)."');</script>";
    }
}

// 2. HAPUS DATA
if(isset($_GET['hapus'])){
    mysqli_query($conn,"DELETE FROM users WHERE id=".$_GET['hapus']." AND role='warga'");
    header("Location: warga.php");
}

// 3. EDIT DATA (AMBIL DATA)
$edit = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $q_edit = mysqli_query($conn, "SELECT * FROM users WHERE id='$id_edit'");
    if (mysqli_num_rows($q_edit) > 0) {
        $edit = mysqli_fetch_assoc($q_edit);
    }
}

// 4. AMBIL LIST WARGA
$data = mysqli_query($conn,"SELECT * FROM users WHERE role='warga' ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin | Data Warga</title>
<style>
body{font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f4f6f9; padding:20px; margin:0;}
h2{color:#0d6efd; margin-bottom:20px;}

/* LAYOUT GRID */
.container {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 30px;
    align-items: start;
}

/* CARD */
.card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,.05);
}

/* FORM */
label { display: block; margin-bottom: 5px; font-weight: 600; color: #555; font-size: 14px; }
input, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
}
button.save {
    width: 100%;
    padding: 12px;
    background: #0d6efd;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
}
button.save:hover { background: #0b5ed7; }
a.cancel {
    display: block;
    text-align: center;
    margin-top: 10px;
    color: #666;
    text-decoration: none;
    font-size: 14px;
}

/* TABLE */
table { width: 100%; border-collapse: collapse; }
th, td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
th { background: #0d6efd; color: #fff; font-weight: 600; }
tr:hover { background: #f8f9fa; }

/* AVATAR */
.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    background: #eee;
}

/* ACTION BUTTONS */
a.btn-edit {
    display: inline-block;
    padding: 6px 12px;
    background: #ffc107;
    color: #000;
    text-decoration: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
}
a.btn-hapus {
    display: inline-block;
    padding: 6px 12px;
    background: #dc3545;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: bold;
    margin-left: 5px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .container { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>👥 Data Warga RT 18</h2>
        <a href="dashboard.php" style="text-decoration:none; font-weight:bold; color:#0d6efd;">← Kembali ke Dashboard</a>
    </div>

    <div class="container">
        
        <!-- FORM EDIT -->
        <div class="card">
            <h3 style="margin-top:0; color:#333;"><?= $edit ? "✏️ Edit Warga" : "ℹ️ Info"; ?></h3>
            
            <?php if ($edit) { ?>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $edit['id'] ?>">
                    
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $edit['nama'] ?>" required>

                    <label>Email</label>
                    <input type="email" name="email" value="<?= $edit['email'] ?>" required>

                    <label>No WhatsApp</label>
                    <input type="text" name="no_wa" value="<?= $edit['no_wa'] ?>">

                    <label>Alamat</label>
                    <textarea name="alamat" rows="3"><?= $edit['alamat'] ?></textarea>

                    <label>Password Baru (Opsional)</label>
                    <input type="text" name="password" placeholder="Isi jika ingin ubah password">

                    <button type="submit" name="update_warga" class="save">Simpan Perubahan</button>
                    <a href="warga.php" class="cancel">Batal</a>
                </form>
            <?php } else { ?>
                <p style="color:#666; font-size:14px; line-height:1.6;">
                    Pilih tombol <strong>Edit</strong> pada tabel di samping untuk mengubah data warga.<br><br>
                    Anda dapat mengubah Nama, Kontak, Alamat, dan mereset Password warga jika mereka lupa.
                </p>
            <?php } ?>
        </div>

        <!-- LIST DATA TABLE -->
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th width="60">Foto</th>
                        <th>Info Warga</th>
                        <th>Kontak</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($w=mysqli_fetch_assoc($data)){ ?>
                    <tr>
                        <td>
                            <img src="<?= !empty($w['foto']) ? '../uploads/profile/'.$w['foto'] : '../default.png' ?>" class="avatar">
                        </td>
                        <td>
                            <strong><?= $w['nama'] ?></strong><br>
                            <span style="font-size:12px; color:#666;"><?= $w['alamat'] ?></span>
                        </td>
                        <td>
                            <div style="font-size:13px;">
                                📧 <?= $w['email'] ?><br>
                                📞 <?= $w['no_wa'] ?>
                            </div>
                        </td>
                        <td>
                            <a href="?edit=<?= $w['id'] ?>" class="btn-edit">Edit</a>
                            <a href="?hapus=<?= $w['id'] ?>" class="btn-hapus" onclick="return confirm('Yakin hapus warga ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
