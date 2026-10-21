<?php
require_once '../config/koneksi.php';
$page_title = 'Data Jabatan';
$base = '../';

$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM jabatan WHERE nama_jabatan LIKE :kw ORDER BY nama_jabatan");
    $stmt->execute(['kw' => "%$keyword%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM jabatan ORDER BY nama_jabatan");
}
$data = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="toolbar">
    <form class="search-form" method="get">
        <input type="text" name="q" placeholder="Cari nama jabatan..." value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-secondary" type="submit">Cari</button>
    </form>
    <a class="btn btn-primary" href="tambah.php">+ Tambah Jabatan</a>
</div>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>ID</th>
            <th>Nama Jabatan</th>
            <th>Gaji Pokok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($data) === 0): ?>
            <tr><td colspan="5">Data tidak ditemukan.</td></tr>
        <?php endif; ?>
        <?php foreach ($data as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $row['id_jabatan'] ?></td>
                <td><?= htmlspecialchars($row['nama_jabatan']) ?></td>
                <td>Rp <?= number_format($row['gaji_pokok'], 0, ',', '.') ?></td>
                <td class="actions">
                    <a class="btn btn-warning" href="edit.php?id=<?= $row['id_jabatan'] ?>">Edit</a>
                    <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_jabatan'] ?>"
                       onclick="return confirm('Hapus jabatan ini?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
