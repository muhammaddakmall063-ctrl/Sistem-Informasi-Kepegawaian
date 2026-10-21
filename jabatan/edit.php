<?php
require_once '../config/koneksi.php';
$page_title = 'Edit Jabatan';
$base = '../';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM jabatan WHERE id_jabatan = :id");
$stmt->execute(['id' => $id]);
$row = $stmt->fetch();

if (!$row) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data jabatan tidak ditemukan.'];
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_jabatan'] ?? '');
    $gaji = $_POST['gaji_pokok'] ?? '';

    if ($nama === '' || $gaji === '' || !is_numeric($gaji)) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama jabatan dan gaji pokok wajib diisi dengan benar.'];
    } else {
        $stmt = $pdo->prepare("UPDATE jabatan SET nama_jabatan = :nama, gaji_pokok = :gaji WHERE id_jabatan = :id");
        $stmt->execute(['nama' => $nama, 'gaji' => $gaji, 'id' => $id]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Jabatan berhasil diperbarui.'];
        header('Location: index.php');
        exit;
    }
}

require_once '../includes/header.php';
?>

<div class="card">
    <form class="form-box" method="post">
        <label>Nama Jabatan</label>
        <input type="text" name="nama_jabatan"
               value="<?= htmlspecialchars($_POST['nama_jabatan'] ?? $row['nama_jabatan']) ?>" required autofocus>

        <label>Gaji Pokok (Rp)</label>
        <input type="number" step="0.01" name="gaji_pokok"
               value="<?= htmlspecialchars($_POST['gaji_pokok'] ?? $row['gaji_pokok']) ?>" required>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
