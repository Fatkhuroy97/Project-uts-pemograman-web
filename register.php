
<?php
include_once "config.php";

$pesan = "";
$jenisPesan = "";

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {

        $pesan = "Semua kolom harus diisi!";
        $jenisPesan = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $pesan = "Format email tidak valid!";
        $jenisPesan = "error";

    } elseif (strlen($password) < 8) {

        $pesan = "Password minimal 8 karakter!";
        $jenisPesan = "error";

    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        mysqli_begin_transaction($mysqli);

        try {

            // Insert ke tabel register
            $sql = "INSERT INTO register
                    (username, email, password)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($mysqli, $sql);

            mysqli_stmt_bind_param(
                $stmt, "sss", $username, $email, $hash
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);


            // Insert ke tabel login
            $sql2 = "INSERT INTO `login`
                     (username, password)
                     VALUES (?, ?)";

            $stmt2 = mysqli_prepare($mysqli, $sql2);

            mysqli_stmt_bind_param(
                $stmt2, "ss", $username, $hash
            );

            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);


            // Simpan transaksi
            mysqli_commit($mysqli);

            header("Location: login.php?status=success");
            exit();

        } catch (mysqli_sql_exception $e) {

            mysqli_rollback($mysqli);

            $pesan = "Registrasi gagal. Username atau email mungkin sudah digunakan.";
            $jenisPesan = "error";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register SIMRS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            background: #eef4fb;
        }

        .register-container {
            width: 100%;
            max-width: 1050px;
            min-height: 650px;
            display: flex;
            background: #ffffff;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(30, 64, 120, 0.13);
        }

        /* BAGIAN KIRI */

        .left-section {
            width: 50%;
            background: linear-gradient(145deg, #0755a5, #0d8bdc);
            padding: 55px 45px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .left-section::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
            top: -120px;
            right: -130px;
        }

        .left-section::after {
            content: "";
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
            bottom: -100px;
            left: -80px;
        }

        .brand {
            position: relative;
            z-index: 2;
        }

        .brand h2 {
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .brand p {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 5px;
        }

        .illustration {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .illustration img {
            width: 100%;
            max-width: 320px;
            height: 250px;
            object-fit: contain;
        }

        .welcome-text {
            position: relative;
            z-index: 2;
        }

        .welcome-text h1 {
            font-size: 27px;
            line-height: 1.4;
            margin-bottom: 12px;
        }

        .welcome-text p {
            font-size: 13px;
            line-height: 1.9;
            opacity: 0.85;
        }

        .footer-left {
            position: relative;
            z-index: 2;
            margin-top: 25px;
            font-size: 11px;
            opacity: 0.7;
        }

        /* BAGIAN KANAN */

        .right-section {
            width: 50%;
            padding: 50px 55px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 28px;
        }

        .small-title {
            color: #1685d5;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .form-header h2 {
            font-size: 30px;
            color: #17365d;
            font-weight: 700;
        }

        .form-header p {
            font-size: 13px;
            color: #8795a8;
            margin-top: 8px;
        }

        /* ALERT */

        .alert {
            padding: 13px 15px;
            border-radius: 10px;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .alert.error {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* INPUT */

        .input-group {
            margin-bottom: 19px;
        }

        .input-group label {
            display: block;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            height: 50px;
            padding: 0 45px 0 16px;
            border: 1px solid #dce5ef;
            background: #f8fafc;
            border-radius: 11px;
            outline: none;
            font-size: 13px;
            color: #334155;
            transition: 0.3s;
        }

        .input-wrapper input:focus {
            border-color: #1685d5;
            background: white;
            box-shadow: 0 0 0 4px rgba(22,133,213,0.09);
        }

        .input-wrapper input::placeholder {
            color: #aab6c5;
        }

        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8da0b5;
            font-size: 17px;
        }

        /* TOMBOL */

        .register-button {
            width: 100%;
            height: 53px;
            border: none;
            border-radius: 11px;
            background: linear-gradient(135deg, #0879ce, #0755a5);
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 5px;
            box-shadow: 0 8px 18px rgba(7, 85, 165, 0.2);
        }

        .register-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(7, 85, 165, 0.3);
        }

        /* LOGIN LINK */

        .login-text {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #8795a8;
        }

        .login-text a {
            color: #0879ce;
            font-weight: 600;
            text-decoration: none;
        }

        .login-text a:hover {
            text-decoration: underline;
        }

        .form-footer {
            text-align: center;
            margin-top: 35px;
            font-size: 10px;
            color: #a3afbf;
        }

        /* RESPONSIVE */

        @media(max-width: 768px) {

            .register-container {
                max-width: 450px;
            }

            .left-section {
                display: none;
            }

            .right-section {
                width: 100%;
                padding: 45px 30px;
            }

        }

        @media(max-width: 400px) {

            body {
                padding: 12px;
            }

            .right-section {
                padding: 35px 22px;
            }

            .form-header h2 {
                font-size: 25px;
            }

        }

    </style>

</head>

<body>

<div class="register-container">

    <!-- BAGIAN KIRI -->

    <div class="left-section">

        <div class="brand">

            <h2>SIMRS.</h2>

            <p>SISTEM INFORMASI MANAJEMEN RUMAH SAKIT</p>

        </div>

        <div class="illustration">

            <img
                src="https://cdn-icons-png.flaticon.com/512/2785/2785482.png"
                alt="Ilustrasi Kesehatan"
            >

        </div>

        <div class="welcome-text">

            <h1>Bergabung Bersama Sistem Pelayanan Kesehatan</h1>

            <p>
                Buat akun baru untuk mulai menggunakan
                Sistem Informasi Manajemen Rumah Sakit.
                Isi data diri Anda dengan benar dan lengkap.
            </p>

        </div>

        <div class="footer-left">
            © 2026 SIMRS. All Rights Reserved.
        </div>

    </div>


    <!-- BAGIAN KANAN -->

    <div class="right-section">

        <div class="form-header">

            <div class="small-title">
                CREATE ACCOUNT
            </div>

            <h2>Daftar Akun</h2>

            <p>
                Silakan lengkapi formulir untuk membuat akun baru.
            </p>

        </div>


        <?php if ($pesan != ""): ?>

            <div class="alert <?= htmlspecialchars($jenisPesan) ?>">
                <?= htmlspecialchars($pesan) ?>
            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="input-group">

                <label>Username</label>

                <div class="input-wrapper">

                    <input
                        type="text"
                        name="username"
                        placeholder="Buat username Anda"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                        autocomplete="username"
                        required
                    >

                    <span class="input-icon">♙</span>

                </div>

            </div>


            <div class="input-group">

                <label>Email</label>

                <div class="input-wrapper">

                    <input
                        type="email"
                        name="email"
                        placeholder="Masukkan email aktif"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        autocomplete="email"
                        required
                    >

                    <span class="input-icon">✉</span>

                </div>

            </div>


            <div class="input-group">

                <label>Password</label>

                <div class="input-wrapper">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Minimal 8 karakter"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >

                    <span
                        class="input-icon"
                        onclick="togglePassword()"
                        style="cursor:pointer"
                        id="eye"
                    >◉</span>

                </div>

            </div>


            <button
                type="submit"
                name="register"
                class="register-button"
            >
                Buat Akun Sekarang →
            </button>

        </form>


        <div class="login-text">

            Sudah memiliki akun?

            <a href="login.php">
                Login Sekarang
            </a>

        </div>


        <div class="form-footer">

            SIMRS | Sistem Informasi Manajemen Rumah Sakit

        </div>

    </div>

</div>


<script>

function togglePassword() {

    const password = document.getElementById("password");

    if (password.type === "password") {

        password.type = "text";

    } else {

        password.type = "password";

    }

}

</script>

</body>
</html>
