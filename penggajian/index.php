<?php
require_once '../config/koneksi.php';
$page_title = 'Data Penggajian';
$base = '../';

$keyword = trim($_GET['q'] ?? '');

$sql = "SELECT g.*, p.nama AS nama_pegawai, p.nip, j.nama_jabatan, j.gaji_pokok
        FROM penggajian g
        JOIN pegawai p ON g.id_pegawai = p.id_pegawai
        LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan";

if ($keyword !== '') {
    $sql .= " WHERE p.nama LIKE :kw OR p.nip LIKE :kw";
    $stmt = $pdo->prepare($sql . " ORDER BY g.periode DESC");
    $stmt->execute(['kw' => "%$keyword%"]);
} else {
    $stmt = $pdo->query($sql . " ORDER BY g.periode DESC");
}
$data = $stmt->fetchAll();

require_once '../includes/header.php';
?>

<div class="toolbar">
    <form class="search-form" method="get">
        <input type="text" name="q" placeholder="Cari nama / NIP pegawai..." value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-secondary" type="submit">Cari</button>
    </form>
    <a class="btn btn-primary" href="tambah.php">+ Tambah Penggajian</a>
</div>

<table>
    <thead>
        <tr>
            <th>NO</th>
            <th>ID Gaji</th>
            <th>Pegawai</th>
            <th>Jabatan</th>
            <th>Periode</th>
            <th>Gaji Pokok</th>
            <th>Tunjangan</th>
            <th>Potongan</th>
            <th>Total Gaji</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($data) === 0): ?>
            <tr><td colspan="10">Data tidak ditemukan.</td></tr>
        <?php endif; ?>
        <?php foreach ($data as $index => $row): ?>
            <tr>
                <td><?= $index +1 ?></td>
                <td><?= $row['id_gaji'] ?></td>
                <td><?= htmlspecialchars($row['nama_pegawai']) ?> (<?= htmlspecialchars($row['nip']) ?>)</td>
                <td><?= htmlspecialchars($row['nama_jabatan'] ?? '-') ?></td>
                <td><?= date('d-m-Y', strtotime($row['periode'])) ?></td>
                <td>Rp <?= number_format($row['gaji_pokok'] ?? 0, 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['tunjangan'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($row['potongan'], 0, ',', '.') ?></td>
                <td><strong>Rp <?= number_format($row['total_gaji'], 0, ',', '.') ?></strong></td>
                <td class="actions">
                    <a class="btn btn-warning" href="edit.php?id=<?= $row['id_gaji'] ?>">Edit</a>
                    <a class="btn btn-danger" href="hapus.php?id=<?= $row['id_gaji'] ?>"
                       onclick="return confirm('Hapus data penggajian ini?');">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
