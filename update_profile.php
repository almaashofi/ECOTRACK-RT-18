<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id = $_SESSION['user_id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$no_wa = mysqli_real_escape_string($conn, $_POST['no_wa']);

// Cek Upload Foto
if (!empty($_FILES['foto']['name'])) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nama_foto = uniqid() . "." . $ext;
    $target = "../uploads/profile/" . $nama_foto;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
        // Update dengan foto
        $query = "UPDATE users SET nama='$nama', alamat='$alamat', no_wa='$no_wa', foto='$nama_foto' WHERE id='$id'";
    } else {
        echo "<script>alert('Gagal upload foto');window.location='profile.php';</script>";
        exit;
    }
} else {
    // Update tanpa foto
    $query = "UPDATE users SET nama='$nama', alamat='$alamat', no_wa='$no_wa' WHERE id='$id'";
}

if (mysqli_query($conn, $query)) {
    // Update session nama jika berubah
    $_SESSION['nama'] = $nama;
    echo "<script>alert('Profil berhasil diupdate');window.location='profile.php';</script>";
} else {
    echo "Error updating record: " . mysqli_error($conn);
}
?>
