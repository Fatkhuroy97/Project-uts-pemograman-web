
<?php
session_start();

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include_once("config.php");

// Mengambil data alat dari database
$result = mysqli_query($mysqli, "SELECT * FROM alat ORDER BY id DESC");

// Menghitung total alat
$total_query = mysqli_query($mysqli, "SELECT COUNT(*) AS total FROM alat");
$total_data = mysqli_fetch_assoc($total_query);
$total_alat = $total_data['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SIMRS</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f7fb;
            color: #1e293b;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #102a43;
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px 18px;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: #0ea5e9;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 23px;
        }

        .logo h2 {
            font-size: 19px;
            font-weight: 700;
        }

        .logo p {
            font-size: 10px;
            color: #94a3b8;
        }

        .menu-title {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
            margin: 20px 10px 10px;
            letter-spacing: 1px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: #cbd5e1;
            padding: 13px 15px;
            margin-bottom: 7px;
            border-radius: 9px;
            font-size: 13px;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #0ea5e9;
            color: white;
        }

        .sidebar a span {
            margin-right: 10px;
            font-size: 17px;
        }

        /* LOGOUT */
        .sidebar .logout {
            margin-top: auto;
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
            font-weight: 600;
        }

        .sidebar .logout:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
        }

        /* MAIN CONTENT */
        .main {
            margin-left: 250px;
            padding: 30px;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 25px;
            font-weight: 700;
            color: #102a43;
        }

        .header p {
            font-size: 13px;
            color: #64748b;
            margin-top: 5px;
        }

        .admin {
            background: white;
            padding: 10px 17px;
            border-radius: 30px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
            font-size: 12px;
            font-weight: 600;
            color: #0ea5e9;
        }

        /* WELCOME */
        .welcome {
            background: linear-gradient(120deg, #0c4a6e, #0284c7);
            color: white;
            padding: 27px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(2,132,199,0.15);
        }

        .welcome h2 {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .welcome p {
            font-size: 13px;
            color: #e0f2fe;
        }

        /* STATISTICS */
        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 23px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        .card-icon {
            width: 43px;
            height: 43px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 21px;
            margin-bottom: 15px;
            background: #e0f2fe;
        }

        .card h3 {
            font-size: 25px;
            color: #102a43;
        }

        .card p {
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }

        /* TABLE SECTION */
        .table-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .table-header h2 {
            font-size: 18px;
            color: #102a43;
        }

        .btn-add {
            background: #0284c7;
            color: white;
            text-decoration: none;
            padding: 10px 17px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-add:hover {
            background: #0369a1;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th {
            background: #f1f5f9;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 14px;
            font-size: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .btn-edit,
        .btn-delete {
            display: inline-block;
            text-decoration: none;
            padding: 7px 11px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin: 2px;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-edit:hover {
            background: #bfdbfe;
        }

        .btn-delete:hover {
            background: #fecaca;
        }

        .empty {
            text-align: center;
            padding: 30px;
            color: #94a3b8;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 75px;
                padding: 20px 8px;
            }

            .logo {
                justify-content: center;
                padding: 0;
                margin-bottom: 30px;
            }

            .logo-icon {
                width: 42px;
                height: 42px;
            }

            .logo h2,
            .logo p,
            .menu-title {
                display: none;
            }

            .sidebar a {
                text-align: center;
                padding: 13px 5px;
                font-size: 10px;
            }

            .sidebar a span {
                display: block;
                margin: 0 0 4px;
                font-size: 19px;
            }

            .main {
                margin-left: 75px;
                padding: 18px 12px;
            }

            .header h1 {
                font-size: 20px;
            }

            .cards {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .welcome {
                padding: 20px;
            }

            .table-section {
                padding: 15px;
            }

            .sidebar .logout {
                font-size: 10px;
                padding: 12px 5px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="logo">
            <div class="logo-icon">✚</div>
            <div>
                <h2>SIM RS</h2>
                <p>Sistem Informasi Rumah Sakit</p>
            </div>
        </div>

        <div class="menu-title">MENU UTAMA</div>

        <a href="dashboard.php" class="active">
            <span>▦</span> Dashboard
        </a>

        <a href="index.php">
            <span>▤</span> Data Alat
        </a>

        <a href="add.php">
            <span>＋</span> Tambah Alat
        </a>

        <div class="menu-title">SISTEM</div>

        <a href="index.php">
            <span>▣</span> Inventaris Alat
        </a>

        <div class="menu-title">AKUN</div>

        <a href="logout.php" class="logout"
           onclick="return confirm('Apakah Anda yakin ingin logout?')">
            <span>↪</span> Logout
        </a>

    </div>

    <!-- MAIN CONTENT -->
    <div class="main">

        <!-- HEADER -->
        <div class="header">
            <div>
                <h1>Dashboard</h1>
                <p>Selamat datang di Sistem Informasi Manajemen Rumah Sakit</p>
            </div>

            <div class="admin">
                ● Administrator
            </div>
        </div>

        <!-- WELCOME -->
        <div class="welcome">
            <h2>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>! 👋</h2>
            <p>
                Selamat datang di halaman pengelolaan inventaris alat elektromedis.
                Kelola dan pantau data alat rumah sakit dengan mudah.
            </p>
        </div>

        <!-- STATISTICS -->
        <div class="cards">

            <div class="card">
                <div class="card-icon">🩺</div>
                <h3><?php echo $total_alat; ?></h3>
                <p>Total Alat Elektromedis</p>
            </div>

            <div class="card">
                <div class="card-icon">🏥</div>
                <h3>SIM RS</h3>
                <p>Sistem Informasi Rumah Sakit</p>
            </div>

            <div class="card">
                <div class="card-icon">✅</div>
                <h3>Aktif</h3>
                <p>Status Sistem</p>
            </div>

        </div>

        <!-- DATA ALAT -->
        <div class="table-section">

            <div class="table-header">
                <h2>Data Inventaris Alat</h2>

                <a href="add.php" class="btn-add">
                    + Tambah Alat
                </a>
            </div>

            <div class="table-responsive">

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Alat</th>
                            <th>Tahun</th>
                            <th>Merek</th>
                            <th>Lokasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {

                        $no = 1;

                        while ($data = mysqli_fetch_assoc($result)) {
                    ?>

                        <tr>
                            <td><?php echo $no++; ?></td>

                            <td>
                                <?php echo htmlspecialchars($data['nama_alat']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($data['tahun']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($data['merek']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($data['lokasi']); ?>
                            </td>

                            <td>
                                <a href="edit.php?id=<?php echo urlencode($data['id']); ?>"
                                   class="btn-edit">
                                    Edit
                                </a>

                                <a href="delete.php?id=<?php echo urlencode($data['id']); ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>

                    <?php
                        }

                    } else {
                    ?>

                        <tr>
                            <td colspan="6" class="empty">
                                Belum ada data alat yang tersedia.
                            </td>
                        </tr>

                    <?php } ?>

                    </tbody>
                </table>

            </div>
        </div>

    </div>

</body>
</html>
