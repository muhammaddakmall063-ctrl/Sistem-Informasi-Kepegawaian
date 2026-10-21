<?php
require_once '../config/koneksi.php';
$page_title = 'Edit Departemen';
$base = '../';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM departemen WHERE id_departemen = :id");
$stmt->execute(['id' => $id]);
$row = $stmt->fetch();

if (!$row) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data departemen tidak ditemukan.'];
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_departemen'] ?? '');

    if ($nama === '') {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Nama departemen wajib diisi.'];
    } else {
        $stmt = $pdo->prepare("UPDATE departemen SET nama_departemen = :nama WHERE id_departemen = :id");
        $stmt->execute(['nama' => $nama, 'id' => $id]);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Departemen berhasil diperbarui.'];
        header('Location: index.php');
        exit;
    }
}

require_once '../includes/header.php';
?>

<div class="card">
    <form class="form-box" method="post">
        <label>Nama Departemen</label>
        <input type="text" name="nama_departemen"
               value="<?= htmlspecialchars($_POST['nama_departemen'] ?? $row['nama_departemen']) ?>" required autofocus>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
