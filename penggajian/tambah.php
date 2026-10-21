<?php
require_once '../config/koneksi.php';
$page_title = 'Tambah Penggajian';
$base = '../';

// Daftar pegawai beserta jabatan & gaji pokok (untuk ditampilkan di JS/HTML data-attribute)
$daftar_pegawai = $pdo->query(
    "SELECT p.id_pegawai, p.nama, p.nip, j.nama_jabatan, j.gaji_pokok
     FROM pegawai p LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
     ORDER BY p.nama"
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pegawai = $_POST['id_pegawai'] ?? '';
    $periode = $_POST['periode'] ?? '';
    $tunjangan = $_POST['tunjangan'] ?? 0;
    $potongan = $_POST['potongan'] ?? 0;

    if ($id_pegawai === '' || $periode === '' || !is_numeric($tunjangan) || !is_numeric($potongan)) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Semua kolom wajib diisi dengan benar.'];
    } else {
        // Ambil gaji pokok terkini dari jabatan pegawai (bukan dari input, agar konsisten dengan data master)
        $stmtGP = $pdo->prepare(
            "SELECT j.gaji_pokok FROM pegawai p LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
             WHERE p.id_pegawai = :id"
        );
        $stmtGP->execute(['id' => $id_pegawai]);
        $gaji_pokok = $stmtGP->fetchColumn() ?: 0;

        $total_gaji = $gaji_pokok + $tunjangan - $potongan;

        $stmt = $pdo->prepare(
            "INSERT INTO penggajian (id_pegawai, periode, tunjangan, potongan, total_gaji)
             VALUES (:id_pegawai, :periode, :tunjangan, :potongan, :total_gaji)"
        );
        $stmt->execute([
            'id_pegawai' => $id_pegawai, 'periode' => $periode,
            'tunjangan' => $tunjangan, 'potongan' => $potongan, 'total_gaji' => $total_gaji
        ]);

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data penggajian berhasil ditambahkan.'];
        header('Location: index.php');
        exit;
    }
}

require_once '../includes/header.php';
?>

<div class="card">
    <form class="form-box" method="post" id="formGaji">
        <label>Pegawai</label>
        <select name="id_pegawai" id="id_pegawai" required onchange="tampilkanInfo()">
            <option value="">-- Pilih Pegawai --</option>
            <?php foreach ($daftar_pegawai as $p): ?>
                <option value="<?= $p['id_pegawai'] ?>"
                    data-jabatan="<?= htmlspecialchars($p['nama_jabatan'] ?? '-') ?>"
                    data-gaji="<?= $p['gaji_pokok'] ?? 0 ?>"
                    <?= (($_POST['id_pegawai'] ?? '') == $p['id_pegawai']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nama']) ?> (<?= htmlspecialchars($p['nip']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <p id="infoJabatan" style="margin:-10px 0 16px; font-size:13px; color:#7a8797;"></p>

        <label>Periode</label>
        <input type="date" name="periode" value="<?= htmlspecialchars($_POST['periode'] ?? date('Y-m-d')) ?>" required>

        <label>Tunjangan (Rp)</label>
        <input type="number" step="0.01" name="tunjangan" value="<?= htmlspecialchars($_POST['tunjangan'] ?? 0) ?>">

        <label>Potongan (Rp)</label>
        <input type="number" step="0.01" name="potongan" value="<?= htmlspecialchars($_POST['potongan'] ?? 0) ?>">

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </div>
    </form>
</div>

<script>
function tampilkanInfo() {
    const select = document.getElementById('id_pegawai');
    const opt = select.options[select.selectedIndex];
    const info = document.getElementById('infoJabatan');
    if (opt.value) {
        const jabatan = opt.getAttribute('data-jabatan');
        const gaji = parseFloat(opt.getAttribute('data-gaji')).toLocaleString('id-ID');
        info.textContent = 'Jabatan: ' + jabatan + ' | Gaji Pokok: Rp ' + gaji;
    } else {
        info.textContent = '';
    }
}
document.addEventListener('DOMContentLoaded', tampilkanInfo);
</script>

<?php require_once '../includes/footer.php'; ?>
