<?php
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';
?>

<section>

    <h2>Daftar Anggota</h2>

    <p>
        <a href="tambah.php">
            + Tambah Anggota
        </a>
    </p>

    <div class="search-box">

        <input
            type="text"
            id="search-input"
            placeholder="Cari anggota...">

    </div>

    <?php if (!empty($_SESSION['anggota'])): ?>

        <div class="table-responsive">

            <table>

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($_SESSION['anggota'] as $anggota): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars($anggota['nama']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($anggota['email']) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <p>
            Belum ada data anggota.
        </p>

    <?php endif; ?>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>