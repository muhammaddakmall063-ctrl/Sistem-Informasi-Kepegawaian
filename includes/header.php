<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - SIMPEG' : 'SIMPEG' ?></title>
    <link rel="stylesheet" href="<?= $base ?? '' ?>assets/css/style.css">
</head>
<body>
<div class="wrapper">
    <aside class="sidebar">
        <h2 class="brand">SISTEM INFORMASI KEPEGAWAIAN</h2>
        <nav>
            <a href="<?= $base ?? '' ?>index.php"
   class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['PHP_SELF'], '/jabatan/') === false && strpos($_SERVER['PHP_SELF'], '/departemen/') === false && strpos($_SERVER['PHP_SELF'], '/pegawai/') === false && strpos($_SERVER['PHP_SELF'], '/penggajian/') === false ? 'active' : '' ?>">
    Dashboard
</a>

<a href="<?= $base ?? '' ?>jabatan/index.php"
   class="<?= strpos($_SERVER['PHP_SELF'], '/jabatan/') !== false ? 'active' : '' ?>">
    Data Jabatan
</a>

<a href="<?= $base ?? '' ?>departemen/index.php"
   class="<?= strpos($_SERVER['PHP_SELF'], '/departemen/') !== false ? 'active' : '' ?>">
    Data Departemen
</a>

<a href="<?= $base ?? '' ?>pegawai/index.php"
   class="<?= strpos($_SERVER['PHP_SELF'], '/pegawai/') !== false ? 'active' : '' ?>">
    Data Pegawai
</a>

<a href="<?= $base ?? '' ?>penggajian/index.php"
   class="<?= strpos($_SERVER['PHP_SELF'], '/penggajian/') !== false ? 'active' : '' ?>">
    Penggajian
</a>
        </nav>
    </aside>
    <main class="content">
        <header class="topbar">
            <h1><?= isset($page_title) ? htmlspecialchars($page_title) : 'SIMPEG' ?></h1>
        </header>
        <div class="page-body">
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
