<?php
require_once '../config/koneksi.php';
$page_title = 'Data Pegawai';
$base = '../';

$keyword = trim($_GET['q'] ?? '');

$sql = "SELECT p.*, j.nama_jabatan, d.nama_departemen
        FROM pegawai p
        LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
        LEFT JOIN departemen d ON p.id_departemen = d.id_departemen";

if ($keyword !== '') {
    $sql .= " WHERE p.nama LIKE :kw OR p.nip LIKE :kw";
    $stmt = $pdo->prepare($sql . " ORDER BY p.nama");
    $stmt->execute(['kw' => "%$keyword%"]);
} else {
    $stmt = $pdo->query($sql . " ORDER BY p.nama");
}
$data = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="toolbar">
    <form class="search-form" method="get">
        <input type="text" name="q" placeholder="Cari nama / NIP..." value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-secondary" type="submit">Cari</button>
    </form>
    <a class="btn btn-primary" href="tambah.php">+ Tambah Pegawai</a>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Jabatan</th>
            <th>Departemen</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($data) === 0): ?>
            <tr><td colspan="8">Data tidak ditemukan.</td></tr>
        <?php endif; ?>
        <?php foreach ($data as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $row['id_pegawai'] ?></td>
                <td><?= htmlspecialchars($row['nip']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                <td><?= htmlspecialchars($row['nama_jabatan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['nama_departemen'] ?? '-') ?></td>
                <td class="actions">
                    <a class="btn btn-warning" href="edit.php?id=<?= $row['id_pegawai'] ?>">Edit</a>
                    <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_pegawai'] ?>"
                       onclick="return confirm('Hapus pegawai ini?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
