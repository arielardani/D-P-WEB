
<?php
session_start();
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

// Ambil ID anggota dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID anggota tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

// Ambil data anggota yang akan diedit
$stmt = $pdo->prepare(
    'SELECT * FROM anggota WHERE id = :id'
);
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data anggota tidak ditemukan.'
    ];

    header('Location: list.php');
    exit;
}

$page_title = 'Edit Anggota';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Edit Anggota</h2>

    <?php if ($flash): ?>
        <p class="flash flash-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($flash['pesan'], ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="post" action="proses_edit.php">
        <input type="hidden" name="id" value="<?= (int) $anggota['id'] ?>">

        <p>
            <label for="nama">Nama</label><br>
            <input
                type="text"
                id="nama"
                name="nama"
                required
                value="<?= htmlspecialchars($anggota['nama'], ENT_QUOTES, 'UTF-8') ?>"
            >
        </p>

        <p>
            <label for="no_anggota">No. Anggota</label><br>
            <input
                type="text"
                id="no_anggota"
                name="no_anggota"
                required
                value="<?= htmlspecialchars((string) $anggota['no_anggota'], ENT_QUOTES, 'UTF-8') ?>"
            >
        </p>

        <p>
            <label for="alamat">Alamat</label><br>
            <input
                type="text"
                id="alamat"
                name="alamat"
                value="<?= htmlspecialchars($anggota['alamat'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </p>

        <p>
            <label for="no_hp">No. HP</label><br>
            <input
                type="text"
                id="no_hp"
                name="no_hp"
                value="<?= htmlspecialchars($anggota['no_hp'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            >
        </p>

        <p>
            <button type="submit">Simpan Perubahan</button>
            <a href="list.php">Batal</a>
        </p>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
