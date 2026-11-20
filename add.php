
<?php
include_once("config.php");

$pesan = "";
$status = "";

// Proses tambah data
if (isset($_POST['Submit'])) {

    $nama_alat = trim($_POST['nama_alat']);
    $tahun = trim($_POST['tahun']);
    $merek = trim($_POST['merek']);
    $lokasi = trim($_POST['lokasi']);

    if ($nama_alat == "" || $tahun == "" ||
        $merek == "" || $lokasi == "") {

        $pesan = "Semua kolom wajib diisi!";
        $status = "error";

    } else {

        // Menggunakan prepared statement
        $stmt = mysqli_prepare(
            $mysqli,
            "INSERT INTO alat (nama_alat, tahun, merek, lokasi)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $nama_alat,
            $tahun,
            $merek,
            $lokasi
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Gagal menyimpan data alat!";
            $status = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tambah Alat - SIM RS</title>

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
    letter-spacing: 1px;
}

.sidebar a {
    display: block;
    padding: 14px;
    color: #dce8f5;
    text-decoration: none;
    border-radius: 9px;
    margin-bottom: 8px;
    transition: .3s;
}

.sidebar a:hover,
.sidebar a.active {
    background: #2563eb;
    color: white;
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
    box-shadow: 0 3px 12px rgba(0,0,0,.04);
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
    margin-bottom: 28px;
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

/* INPUT */
.form-group {
    margin-bottom: 23px;
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
    padding: 14px 15px;
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
    padding: 13px 23px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: .3s;
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

/* ALERT */
.alert {
    padding: 14px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
}

.alert-error {
    background: #fee2e2;
    color: #b91c1c;
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
    <a href="add.php" class="active">+ Tambah Alat</a>

    <div class="menu-title">PENGELOLAAN</div>

    <a href="index.php">Inventaris Alat</a>

</div>

<!-- MAIN CONTENT -->
<div class="main">

    <div class="topbar">
        <div>
            <h1>Tambah Alat</h1>
            <p>Tambahkan data inventaris alat elektromedis rumah sakit</p>
        </div>

        <div class="admin">
            Administrator
        </div>
    </div>

    <!-- FORM -->
    <div class="form-card">

        <div class="form-title">
            <h2>Formulir Data Alat Elektromedis</h2>
            <p>Silakan lengkapi informasi alat di bawah ini.</p>
        </div>

        <?php if ($pesan != "") { ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($pesan); ?>
            </div>
        <?php } ?>

        <form action="add.php" method="POST">

            <div class="form-group">
                <label>Nama Alat Elektromedis</label>
                <input
                    type="text"
                    name="nama_alat"
                    placeholder="Contoh: Patient Monitor"
                    value="<?php echo isset($_POST['nama_alat']) ? htmlspecialchars($_POST['nama_alat']) : ''; ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Tahun Pengadaan</label>
                <input
                    type="text"
                    name="tahun"
                    placeholder="Contoh: 2025"
                    value="<?php echo isset($_POST['tahun']) ? htmlspecialchars($_POST['tahun']) : ''; ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Merek Alat</label>
                <input
                    type="text"
                    name="merek"
                    placeholder="Contoh: Mindray"
                    value="<?php echo isset($_POST['merek']) ? htmlspecialchars($_POST['merek']) : ''; ?>"
                    required>
            </div>

            <div class="form-group">
                <label>Lokasi Alat</label>
                <input
                    type="text"
                    name="lokasi"
                    placeholder="Contoh: Ruang ICU"
                    value="<?php echo isset($_POST['lokasi']) ? htmlspecialchars($_POST['lokasi']) : ''; ?>"
                    required>
            </div>

            <div class="buttons">

                <button type="submit" name="Submit"
                    class="btn btn-save">
                    + Simpan Data
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
