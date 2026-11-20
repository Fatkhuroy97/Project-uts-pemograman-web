
<?php
include_once("config.php");

$error = "";

// Proses update data
if (isset($_POST['update'])) {

    $id = (int) $_POST['id'];
    $nama_alat = trim($_POST['nama_alat']);
    $tahun = trim($_POST['tahun']);
    $merek = trim($_POST['merek']);
    $lokasi = trim($_POST['lokasi']);

    $stmt = mysqli_prepare($mysqli,
        "UPDATE alat SET nama_alat=?, tahun=?, merek=?, lokasi=? WHERE id=?"
    );

    mysqli_stmt_bind_param(
        $stmt, "ssssi",
        $nama_alat, $tahun, $merek, $lokasi, $id
    );

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal memperbarui data alat.";
    }
}

// Mengambil data berdasarkan ID
if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
} elseif (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
} else {
    die("ID alat tidak ditemukan.");
}

$stmt = mysqli_prepare($mysqli, "SELECT * FROM alat WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

if (!$user_data) {
    die("Data alat tidak ditemukan.");
}

$nama_alat = $user_data['nama_alat'];
$tahun = $user_data['tahun'];
$merek = $user_data['merek'];
$lokasi = $user_data['lokasi'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Data Alat - SIM RS</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Arial, sans-serif;
}

body {
    background: #f3f6fb;
    color: #263449;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    height: 100vh;
    background: #102d4f;
    position: fixed;
    left: 0;
    top: 0;
    padding: 25px 18px;
    color: white;
}

.logo {
    text-align: center;
    font-size: 25px;
    font-weight: bold;
    padding: 15px 0 35px;
}

.logo span {
    color: #38bdf8;
}

.menu-title {
    font-size: 11px;
    color: #91a9c4;
    margin: 15px 12px;
}

.sidebar a {
    display: block;
    padding: 14px;
    color: #dce8f5;
    text-decoration: none;
    border-radius: 9px;
    margin-bottom: 8px;
}

.sidebar a:hover,
.sidebar a.active {
    background: #2563eb;
}

/* MAIN */
.main {
    margin-left: 250px;
    padding: 35px;
}

/* HEADER */
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.topbar h1 {
    color: #102d4f;
    font-size: 27px;
}

.topbar p {
    color: #8794a8;
    margin-top: 6px;
}

.admin {
    background: white;
    padding: 12px 18px;
    border-radius: 9px;
    font-weight: 600;
}

/* FORM CARD */
.form-card {
    max-width: 850px;
    background: white;
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,.05);
}

.form-title {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #edf0f5;
}

.form-title h2 {
    color: #102d4f;
    font-size: 21px;
}

.form-title p {
    color: #8794a8;
    font-size: 14px;
    margin-top: 7px;
}

.form-group {
    margin-bottom: 22px;
}

.form-group label {
    display: block;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 9px;
    color: #334155;
}

.form-group input {
    width: 100%;
    padding: 13px 15px;
    border: 1px solid #dbe2ea;
    border-radius: 8px;
    outline: none;
    font-size: 14px;
    transition: .3s;
}

.form-group input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
}

.form-group input::placeholder {
    color: #a0aaba;
}

/* BUTTON */
.buttons {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}

.btn {
    padding: 13px 22px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
}

.btn-save {
    background: #2563eb;
    color: white;
}

.btn-save:hover {
    background: #1d4ed8;
}

.btn-back {
    background: #e2e8f0;
    color: #334155;
}

.btn-back:hover {
    background: #cbd5e1;
}

.error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 13px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.footer {
    margin-top: 35px;
    text-align: center;
    color: #94a3b8;
    font-size: 12px;
}

/* RESPONSIVE */
@media(max-width: 768px) {
    .sidebar {
        width: 180px;
    }

    .main {
        margin-left: 180px;
        padding: 18px;
    }

    .topbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .form-card {
        padding: 20px;
    }
}

@media(max-width: 480px) {
    .sidebar {
        width: 65px;
        padding: 15px 7px;
    }

    .logo {
        font-size: 14px;
    }

    .sidebar a {
        font-size: 10px;
        padding: 12px 5px;
        text-align: center;
    }

    .menu-title {
        display: none;
    }

    .main {
        margin-left: 65px;
        padding: 12px;
    }

    .buttons {
        flex-direction: column;
    }

    .btn {
        text-align: center;
    }
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="logo">
        SIM <span>RS</span>
    </div>

    <div class="menu-title">MENU UTAMA</div>

    <a href="index.php">Dashboard</a>
    <a href="index.php">Data Alat</a>
    <a href="add.php">+ Tambah Alat</a>

    <div class="menu-title">PENGELOLAAN</div>

    <a href="index.php" class="active">Edit Data Alat</a>

</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="topbar">
        <div>
            <h1>Edit Data Alat</h1>
            <p>Perbarui informasi inventaris alat elektromedis</p>
        </div>

        <div class="admin">
            Administrator
        </div>
    </div>

    <div class="form-card">

        <div class="form-title">
            <h2>Formulir Edit Alat Elektromedis</h2>
            <p>Silakan ubah informasi alat sesuai data terbaru.</p>
        </div>

        <?php if ($error != "") { ?>
            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php } ?>

        <form method="POST" action="edit.php">

            <input type="hidden" name="id"
                value="<?php echo (int)$id; ?>">

            <div class="form-group">
                <label>Nama Alat Elektromedis</label>
                <input type="text"
                    name="nama_alat"
                    value="<?php echo htmlspecialchars($nama_alat); ?>"
                    placeholder="Masukkan nama alat"
                    required>
            </div>

            <div class="form-group">
                <label>Tahun Pengadaan</label>
                <input type="text"
                    name="tahun"
                    value="<?php echo htmlspecialchars($tahun); ?>"
                    placeholder="Contoh: 2025"
                    required>
            </div>

            <div class="form-group">
                <label>Merek Alat</label>
                <input type="text"
                    name="merek"
                    value="<?php echo htmlspecialchars($merek); ?>"
                    placeholder="Masukkan merek alat"
                    required>
            </div>

            <div class="form-group">
                <label>Lokasi Alat</label>
                <input type="text"
                    name="lokasi"
                    value="<?php echo htmlspecialchars($lokasi); ?>"
                    placeholder="Contoh: Ruang ICU"
                    required>
            </div>

            <div class="buttons">

                <button type="submit" name="update"
                    class="btn btn-save">
                    Simpan Perubahan
                </button>

                <a href="index.php" class="btn btn-back">
                    Kembali
                </a>

            </div>

        </form>

    </div>

    <div class="footer">
        © 2026 SIM RS | Sistem Informasi Inventaris Alat Elektromedis
    </div>

</div>

</body>
</html>
