<?php
require_once '../config/koneksi.php';
$page_title = 'Edit Pegawai';
$base = '../';

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM pegawai WHERE id_pegawai = :id");
$stmt->execute(['id' => $id]);
$row = $stmt->fetch();

if (!$row) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Data pegawai tidak ditemukan.'];
    header('Location: index.php');
    exit;
}

$daftar_jabatan = $pdo->query("SELECT * FROM jabatan ORDER BY nama_jabatan")->fetchAll();
$daftar_departemen = $pdo->query("SELECT * FROM departemen ORDER BY nama_departemen")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $jk = $_POST['jenis_kelamin'] ?? '';
    $id_jabatan = $_POST['id_jabatan'] ?: null;
    $id_departemen = $_POST['id_departemen'] ?: null;

    if ($nip === '' || $nama === '' || !in_array($jk, ['L', 'P'])) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'NIP, Nama, dan Jenis Kelamin wajib diisi.'];
    } else {
        try {
            $stmt = $pdo->prepare(
                "UPDATE pegawai SET nip = :nip, nama = :nama, jenis_kelamin = :jk,
                 id_jabatan = :id_jabatan, id_departemen = :id_departemen
                 WHERE id_pegawai = :id"
            );
            $stmt->execute([
                'nip' => $nip, 'nama' => $nama, 'jk' => $jk,
                'id_jabatan' => $id_jabatan, 'id_departemen' => $id_departemen, 'id' => $id
            ]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pegawai berhasil diperbarui.'];
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'NIP sudah digunakan pegawai lain.'];
        }
    }
    // supaya tampilan form tetap menampilkan input terbaru jika gagal
    $row = array_merge($row, $_POST);
}

require_once '../includes/header.php';
?>

<div class="card">
    <form class="form-box" method="post">
        <label>NIP</label>
        <input type="text" name="nip" value="<?= htmlspecialchars($row['nip']) ?>" required autofocus>

        <label>Nama</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required>

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" required>
            <option value="L" <?= $row['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="P" <?= $row['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
        </select>

        <label>Jabatan</label>
        <select name="id_jabatan">
            <option value="">-- Tidak ada --</option>
            <?php foreach ($daftar_jabatan as $j): ?>
                <option value="<?= $j['id_jabatan'] ?>"
                    <?= ($row['id_jabatan'] == $j['id_jabatan']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($j['nama_jabatan']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Departemen</label>
        <select name="id_departemen">
            <option value="">-- Tidak ada --</option>
            <?php foreach ($daftar_departemen as $d): ?>
                <option value="<?= $d['id_departemen'] ?>"
                    <?= ($row['id_departemen'] == $d['id_departemen']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['nama_departemen']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="actions">
            <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
