<?php
// Inisialisasi variabel awal
$nama   = '';
$email  = '';
$nilai  = '';
$hasil  = null;

// Form Processing: menangani kiriman data via metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses'])) {
    $nama  = isset($_POST['nama']) ? htmlspecialchars(trim($_POST['nama'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $nilaiRaw = isset($_POST['nilai']) ? trim($_POST['nilai']) : '';

    if ($nama !== '' && $email !== '' && is_numeric($nilaiRaw)) {
        $nilai = floatval($nilaiRaw);

        // Struktur Kendali Percabangan (If - Else)
        // - Jika nilai ujian > 70, maka Lulus.
        // - Jika nilai ujian <= 70 (termasuk < 70), maka Remedial.
        if ($nilai > 70) {
            $status     = "Lulus";
            $tipeStatus = "lulus";
            $pesan      = "Selamat! Anda dinyatakan <strong>LULUS</strong> dalam ujian ini.";
        } else {
            $status     = "Remedial";
            $tipeStatus = "remedial";
            $pesan      = "Nilai Anda belum mencapai kriteria kelulusan (> 70). Anda harus mengikuti ujian <strong>REMEDIAL</strong>.";
        }

        $hasil = [
            'nama'       => $nama,
            'email'      => $email,
            'nilai'      => $nilai,
            'status'     => $status,
            'tipeStatus' => $tipeStatus,
            'pesan'      => $pesan,
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas 1 - Form Processing & Struktur Kendali Nilai Ujian</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            padding: 40px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            max-width: 900px;
            width: 100%;
        }

        header {
            text-align: center;
            margin-bottom: 28px;
        }

        .badge-pill {
            display: inline-block;
            background-color: #e0e7ff;
            color: #4338ca;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 12px;
            border-radius: 9999px;
            margin-bottom: 10px;
        }

        header h1 {
            font-size: 26px;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 8px;
        }

        header p {
            font-size: 14px;
            color: #64748b;
        }

        .main-layout {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 24px;
            align-items: start;
        }

        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px 14px;
            font-size: 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            color: #1e293b;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .input-hint {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 11px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
            flex: 1;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        /* Result Card Styles */
        .table-result {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .table-result tr {
            border-bottom: 1px solid #f1f5f9;
        }

        .table-result tr:last-child {
            border-bottom: none;
        }

        .table-result td {
            padding: 10px 0;
        }

        .table-result td.label-col {
            width: 35%;
            color: #64748b;
            font-weight: 500;
        }

        .table-result td.val-col {
            width: 65%;
            color: #0f172a;
            font-weight: 600;
        }

        .result-message {
            font-size: 13px;
            line-height: 1.5;
            padding: 12px;
            border-radius: 8px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
        }

        .empty-placeholder {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 12px;
            color: #cbd5e1;
        }

        footer {
            margin-top: 36px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <header>
            <h1>Form Evaluasi Nilai Ujian Siswa</h1>
            <p>Implementasi Form Processing (Metode POST) dan Struktur Kendali Percabangan (If - Else)</p>
        </header>

        <div class="main-layout">
            <!-- Formulir Input -->
            <div class="card">
                <div class="card-title">
                    <span>Input Data Ujian</span>
                </div>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input 
                            type="text" 
                            id="nama" 
                            name="nama" 
                            placeholder="Contoh: Haydar" 
                            value="<?= $nama !== '' ? $nama : '' ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Contoh: haydar@gmail.com" 
                            value="<?= $email !== '' ? $email : '' ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="nilai">Nilai Ujian (0 - 100)</label>
                        <input 
                            type="number" 
                            id="nilai" 
                            name="nilai" 
                            min="0" 
                            max="100" 
                            step="any" 
                            placeholder="Contoh: 75" 
                            value="<?= $nilai !== '' ? $nilai : '' ?>" 
                            required
                        >
                        <div class="input-hint">Kriteria: Nilai &gt; 70 dinyatakan <strong>Lulus</strong>, nilai &le; 70 dinyatakan <strong>Remedial</strong>.</div>
                    </div>

                    <div class="button-group">
                        <button type="submit" name="proses" class="btn btn-primary">
                            Proses Nilai
                        </button>
                        <?php if ($hasil !== null): ?>
                            <a href="index.php" class="btn btn-secondary">Input Ulang</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Tampilan Output / Hasil Penilaian -->
            <div class="card">
                <div class="card-title">
                    <span>Hasil Penilaian</span>
                </div>

                <?php if ($hasil !== null): ?>
                    <!-- Detail Data Siswa -->
                    <table class="table-result">
                        <tr>
                            <td class="label-col">Nama Lengkap</td>
                            <td class="val-col"><?= $hasil['nama'] ?></td>
                        </tr>
                        <tr>
                            <td class="label-col">Alamat Email</td>
                            <td class="val-col"><?= $hasil['email'] ?></td>
                        </tr>
                        <tr>
                            <td class="label-col">Nilai Ujian</td>
                            <td class="val-col">
                                <span style="font-size: 16px;"><?= $hasil['nilai'] ?></span> / 100
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">Hasil Akhir</td>
                            <td class="val-col">
                                <strong style="color: <?= $hasil['tipeStatus'] === 'lulus' ? '#10b981' : '#ef4444' ?>;">
                                    <?= $hasil['status'] ?>
                                </strong>
                            </td>
                        </tr>
                    </table>

                    <div class="result-message">
                        <?= $hasil['pesan'] ?>
                    </div>
                <?php else: ?>
                    <!-- Tampilan Awal sebelum form disubmit -->
                    <div class="empty-placeholder">
                        <div class="empty-icon">&#128196;</div>
                        <p style="font-size: 14px; font-weight: 500; color: #64748b; margin-bottom: 6px;">Belum Ada Data Diproses</p>
                        <p style="font-size: 12px; color: #94a3b8;">Silakan isi formulir di sebelah kiri dan klik tombol <strong>Proses Nilai</strong> untuk melihat hasil evaluasi ujian.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <footer>
            &copy; <?= date('Y') ?> &bull; SIB Pemrograman PHP &bull; Haydar Ali Ayyubi
        </footer>
    </div>

</body>
</html>
