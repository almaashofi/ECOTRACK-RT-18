<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warga') {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/database.php";

// Fetch Stats
$agenda_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM agenda_rt"))['total'];
$laporan_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM laporan_warga WHERE user_id='".$_SESSION['user_id']."'"))['total'];

// Fetch Informasi
$informasi = mysqli_query($conn, "SELECT * FROM informasi_rt ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Warga | EcoTrack</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #0f172a;
        --accent: #0ea5e9;
        --bg: #f8fafc;
        --card-bg: #ffffff;
        --text-main: #334155;
        --text-light: #64748b;
        --border: #e2e8f0;
    }

    * { margin:0; padding:0; box-sizing:border-box; }
    
    body { 
        font-family: 'Inter', sans-serif; 
        background: var(--bg); 
        color: var(--text-main); 
        -webkit-font-smoothing: antialiased;
    }

    /* GLASS NAV */
    nav {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 15px 5%;
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-brand {
        font-weight: 800;
        font-size: 20px;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .nav-links a {
        color: var(--text-light);
        text-decoration: none;
        margin-left: 25px;
        font-weight: 500;
        font-size: 14px;
        transition: 0.2s;
    }

    .nav-links a:hover, .nav-links a.active {
        color: var(--accent);
    }

    .btn-logout {
        padding: 8px 16px;
        background: #fee2e2;
        color: #ef4444 !important;
        border-radius: 6px;
        font-weight: 600;
    }

    /* HERO SECTION */
    .hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        padding: 60px 5%;
        text-align: center;
        border-bottom-left-radius: 30px;
        border-bottom-right-radius: 30px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.2);
    }

    .hero h1 {
        font-size: 2.5rem;
        margin-bottom: 15px;
        font-weight: 800;
    }
    
    .hero p {
        font-size: 1.1rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    /* GRID LAYOUT */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
        padding-bottom: 50px;
    }

    /* CARDS */
    .card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 25px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
        transition: transform 0.2s;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* NEWS FEED */
    .news-item {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--border);
    }
    
    .news-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .news-img {
        width: 180px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .news-content h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .news-meta {
        font-size: 12px;
        color: var(--text-light);
        margin-bottom: 10px;
        display: block;
        font-weight: 500;
    }

    .news-desc {
        font-size: 14px;
        color: var(--text-main);
        line-height: 1.6;
    }

    /* STATS WIDGET */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .stat-card {
        background: #f1f5f9;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }

    .stat-card b {
        font-size: 28px;
        color: var(--accent);
        display: block;
        margin-bottom: 5px;
    }

    .stat-card span {
        font-size: 13px;
        color: var(--text-light);
        font-weight: 600;
    }

    /* OFFICIALS */
    .official {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 10px;
        transition: 0.2s;
    }

    .official:hover {
        background: #f8fafc;
    }

    .official img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border);
    }

    .official-info h5 {
        font-size: 14px;
        color: var(--primary);
    }

    .official-info p {
        font-size: 12px;
        color: var(--text-light);
    }

    /* FOOTER */
    footer {
        text-align: center;
        padding: 40px;
        color: var(--text-light);
        font-size: 14px;
        border-top: 1px solid var(--border);
        background: white;
    }

    @media (max-width: 768px) {
        .container { grid-template-columns: 1fr; }
        .hero h1 { font-size: 2rem; }
        .news-item { flex-direction: column; }
        .news-img { width: 100%; height: 200px; }
        .nav-links { display: none; } /* Simplified for mobile demo */
    }
</style>
</head>
<body>

<nav>
    <div class="nav-brand">
        🌿 EcoTrack RT 18
    </div>
    <div class="nav-links">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="jadwal.php">Jadwal Ronda</a>
        <a href="laporan.php">Laporan</a>
        <a href="agenda.php">Agenda</a>
        <a href="daftar_warga.php">Data Warga</a>
        <a href="profile.php">Profil</a>
        <a href="../auth/logout.php" class="btn-logout">Keluar</a>
    </div>
</nav>

<div class="hero">
    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?></h1>
    <p>Portal informasi digital untuk warga RT 18. Tetap terhubung, aman, dan nyaman bersama.</p>
</div>

<div class="container">
    
    <!-- MAIN FEED -->
    <div class="wrapper-main">
        <h2 class="section-title">📢 Informasi Terbaru</h2>
        
        <div class="card">
            <?php if(mysqli_num_rows($informasi) > 0){ ?>
                <?php while($info = mysqli_fetch_assoc($informasi)){ ?>
                <div class="news-item">
                    <?php if(!empty($info['foto'])){ ?>
                        <img src="../uploads/informasi/<?= $info['foto'] ?>" class="news-img">
                    <?php } else { ?>
                         <!-- Placeholder if no image -->
                         <div class="news-img" style="background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                            EcoTrack
                         </div>
                    <?php } ?>
                    
                    <div class="news-content">
                        <h3><?= htmlspecialchars($info['judul']) ?></h3>
                        <span class="news-meta">Diposting pada <?= date('d M Y', strtotime($info['created_at'])) ?></span>
                        <p class="news-desc">
                            <?= nl2br(htmlspecialchars(substr($info['isi'], 0, 200))) ?>...
                            <br><a href="#" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; margin-top:5px; display:inline-block;">Baca Selengkapnya →</a>
                        </p>
                    </div>
                </div>
                <?php } ?>
            <?php } else { ?>
                <div style="text-align:center; padding:40px;">
                    <p style="color:var(--text-light);">Belum ada pengumuman terbaru.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- SIDEBAR -->
    <div class="wrapper-sidebar">
        
        <div class="card">
            <h3 class="section-title">📊 Ringkasan Anda</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <b><?= $agenda_count ?></b>
                    <span>Agenda</span>
                </div>
                <div class="stat-card">
                    <b><?= $laporan_count ?></b>
                    <span>Laporan</span>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="section-title">🏛️ Pengurus RT</h3>
            
            <div class="official">
                <img src="https://ui-avatars.com/api/?name=Sutarno&background=0f172a&color=fff" alt="RT">
                <div class="official-info">
                    <h5>Sutarno</h5>
                    <p>Ketua RT</p>
                </div>
            </div>

            <div class="official">
                <img src="https://ui-avatars.com/api/?name=Nurohman&background=0ea5e9&color=fff" alt="Sekretaris">
                <div class="official-info">
                    <h5>Nurohman</h5>
                    <p>Sekretaris</p>
                </div>
            </div>
            
            <div class="official">
                <img src="https://ui-avatars.com/api/?name=Aji+Suraji&background=64748b&color=fff" alt="Bendahara">
                <div class="official-info">
                    <h5>Aji Suraji</h5>
                    <p>Bendahara</p>
                </div>
            </div>

        </div>

    </div>

</div>

<footer>
    &copy; <?= date('Y') ?> EcoTrack RT 18. Developed for better community.
</footer>

</body>
</html>
