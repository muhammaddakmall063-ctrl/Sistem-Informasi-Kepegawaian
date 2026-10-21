<?php
require_once 'config/koneksi.php';

$page_title = 'Dashboard';
$base = '';

$total_pegawai = $pdo->query("SELECT COUNT(*) AS jml FROM pegawai")->fetch()['jml'];
$total_jabatan = $pdo->query("SELECT COUNT(*) AS jml FROM jabatan")->fetch()['jml'];
$total_departemen = $pdo->query("SELECT COUNT(*) AS jml FROM departemen")->fetch()['jml'];
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$stmt_gaji = $pdo->prepare("
    SELECT COALESCE(SUM(total_gaji), 0) AS total
    FROM penggajian
    WHERE MONTH(periode) = :bulan
    AND YEAR(periode) = :tahun
");

$stmt_gaji->execute([
    'bulan' => $bulan,
    'tahun' => $tahun
]);

$total_gaji_bulan_ini = $stmt_gaji->fetch()['total'];

require_once 'includes/header.php';
?>

<div class="dashboard-cards">
    <div class="card">
        <h3><?= $total_pegawai ?></h3>
        <p>Total Pegawai</p>
    </div>
    <div class="card">
        <h3><?= $total_jabatan ?></h3>
        <p>Total Jabatan</p>
    </div>
    <div class="card">
        <h3><?= $total_departemen ?></h3>
        <p>Total Departemen</p>
    </div>
    <div class="card total-gaji-card">
        <form method="get" class="form-bulan">
        <select name="bulan" onchange="this.form.submit()">
            <option value="01" <?= $bulan == '01' ? 'selected' : '' ?>>Januari 2026</option>
            <option value="02" <?= $bulan == '02' ? 'selected' : '' ?>>Februari 2026</option>
            <option value="03" <?= $bulan == '03' ? 'selected' : '' ?>>Maret 2026</option>
            <option value="04" <?= $bulan == '04' ? 'selected' : '' ?>>April 2026</option>
            <option value="05" <?= $bulan == '05' ? 'selected' : '' ?>>Mei 2026</option>
            <option value="06" <?= $bulan == '06' ? 'selected' : '' ?>>Juni 2026</option>
            <option value="07" <?= $bulan == '07' ? 'selected' : '' ?>>Juli 2026</option>
            <option value="08" <?= $bulan == '08' ? 'selected' : '' ?>>Agustus 2026</option>
            <option value="09" <?= $bulan == '09' ? 'selected' : '' ?>>September 2026</option>
            <option value="10" <?= $bulan == '10' ? 'selected' : '' ?>>Oktober 2026</option>
            <option value="11" <?= $bulan == '11' ? 'selected' : '' ?>>November 2026</option>
            <option value="12" <?= $bulan == '12' ? 'selected' : '' ?>>Desember 2026</option>
        </select>
        </form>

        <h3>
        Rp <?= number_format($total_gaji_bulan_ini, 0, ',', '.') ?>
        </h3>

    <p>Total Gaji</p>
    </div>
</div>

<div class="card">
    <p>Selamat datang di <strong>Sistem Informasi Kepegawaian</strong>.
    Gunakan menu di samping untuk mengelola data Pegawai, Jabatan, Departemen, dan Penggajian.</p>
</div>

<?php require_once 'includes/footer.php'; ?>
