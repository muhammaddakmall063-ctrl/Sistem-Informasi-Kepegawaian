<?php
require_once '../config/koneksi.php';
$page_title = 'Data Departemen';
$base = '../';

$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM departemen WHERE nama_departemen LIKE :kw ORDER BY nama_departemen");
    $stmt->execute(['kw' => "%$keyword%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM departemen ORDER BY nama_departemen");
}
$data = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="toolbar">
    <form class="search-form" method="get">
        <input type="text" name="q" placeholder="Cari nama departemen..." value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-secondary" type="submit">Cari</button>
    </form>
    <a class="btn btn-primary" href="tambah.php">+ Tambah Departemen</a>
</div>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>ID</th>
            <th>Nama Departemen</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($data) === 0): ?>
            <tr><td colspan="4">Data tidak ditemukan.</td></tr>
        <?php endif; ?>
        <?php foreach ($data as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></th>
                <td><?= $row['id_departemen'] ?></td>
                <td><?= htmlspecialchars($row['nama_departemen']) ?></td>
                <td class="actions">
                    <a class="btn btn-warning" href="edit.php?id=<?= $row['id_departemen'] ?>">Edit</a>
                    <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_departemen'] ?>"
                       onclick="return confirm('Hapus departemen ini?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
