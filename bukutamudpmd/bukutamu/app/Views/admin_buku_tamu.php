<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Daftar Tamu DPMD Kepri</title>

        <?php
        // Prefix assets dinamis → selalu mengarah ke /public/assets/
        $ASSET = rtrim(base_url('assets'), '/') . '/';

        // fallback jika controller belum kirim variabel filter
        $bulan_aktif = isset($bulan_aktif) ? (int) $bulan_aktif : (int) date('n');   // 1-12
        $tahun_aktif = isset($tahun_aktif) ? (int) $tahun_aktif : (int) date('Y');   // 4 digit
        
        // daftar tahun (fallback: 5 tahun terakhir s/d tahun ini)
        if (!isset($tahun_list) || !is_array($tahun_list) || empty($tahun_list)) {
            $tahun_list = [];
            $thisYear = (int) date('Y');
            for ($y = $thisYear; $y >= $thisYear - 4; $y--)
                $tahun_list[] = $y;
        }

        // nama bulan (fallback bila belum ada)
        if (!isset($nama_bulan) || $nama_bulan === '') {
            $bulanNamaMap = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
            $nama_bulan = $bulanNamaMap[$bulan_aktif] ?? 'Bulan';
        }
        ?>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- CSS lokal (FIXED PATH) -->
        <link rel="stylesheet" href="<?= $ASSET ?>css/style-admin.css">
    </head>

    <body class="bg-light">

        <nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4 navbar-admin">
            <div class="container-fluid px-4">

                <!-- LOGO ADMIN TIDAK DIUBAH -->
                <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= base_url('admin') ?>">
                    <div class="logo-icon-wrapper me-2">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                    <span class="brand-text">PANEL ADMIN DPMD</span>
                </a>

                <div class="d-flex gap-2">
                
                    <button type="button" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold"
                        data-bs-toggle="modal" data-bs-target="#modalQr">
                        <i class="bi bi-qr-code me-1"></i>
                        QR Code
                    </button>

                    <a href="<?= base_url('/') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">
                        Lihat Form
                    </a>

                    <a href="<?= base_url('auth/logout') ?>" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
                        Logout
                    </a>
                </div>

            </div>
        </nav>

        <div class="container-fluid px-4">

            <div class="row mb-4">

                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm card-stats card-blue">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase small fw-bold opacity-75">Tamu Hari Ini</h6>
                            <h2 class="display-5 fw-800 m-0"><?= $total_hari_ini ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm card-stats card-green">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase small fw-bold opacity-75">Tamu Bulan Ini</h6>
                            <h2 class="display-5 fw-800 m-0"><?= $total_bulan_ini ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm card-stats card-dark">
                        <div class="card-body p-4">
                            <h6 class="text-uppercase small fw-bold opacity-75">Total Seluruh Tamu</h6>
                            <h2 class="display-5 fw-800 m-0"><?= $total_semua ?></h2>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ================== GRAFIK + FILTER BULAN/TAHUN (BARU) ================== -->
            <div class="card shadow-sm border-0 mb-4 card-custom">
                <div
                    class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h6 class="m-0 fw-bold">
                        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
                        Grafik Kunjungan Bulan <?= esc($nama_bulan) ?>
                    </h6>

                    <!-- filter pakai GET agar sederhana & tidak merusak logic lama -->
                    <form method="get" action="<?= current_url() ?>" class="d-flex flex-wrap gap-2">
                        <div class="filter-row d-flex align-items-center gap-2">
                            <label class="small text-muted m-0">Bulan</label>
                            <select name="bulan" class="form-select form-select-sm rounded-pill"
                                style="min-width: 150px;">
                                <?php
                                $bulanMap = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember'
                                ];
                                foreach ($bulanMap as $num => $label):
                                    $sel = ($num === $bulan_aktif) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $num ?>" <?= $sel ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-row d-flex align-items-center gap-2">
                            <label class="small text-muted m-0">Tahun</label>
                            <select name="tahun" class="form-select form-select-sm rounded-pill"
                                style="min-width: 120px;">
                                <?php foreach ($tahun_list as $y): ?>
                                    <option value="<?= (int) $y ?>" <?= ((int) $y === $tahun_aktif) ? 'selected' : '' ?>>
                                        <?= (int) $y ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" type="submit">
                            <i class="bi bi-funnel me-1"></i> Tampilkan
                        </button>

                        <a class="btn btn-light btn-sm rounded-pill px-3" href="<?= current_url() ?>">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Bulan Ini
                        </a>
                    </form>
                </div>

                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chartTamu"></canvas>
                    </div>

                    <?php if (empty($grafik)): ?>
                        <div class="text-muted small mt-3">
                            <i class="bi bi-info-circle me-1"></i> Tidak ada data kunjungan pada periode yang dipilih.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (session()->getFlashdata('pesan')): ?>
                <div class="alert alert-success border-0 shadow-sm mb-4 alert-custom">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-5 card-custom">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-dark">Daftar Riwayat Kunjungan</h5>

                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle rounded-pill" type="button"
                            data-bs-toggle="dropdown">
                            Export Laporan
                        </button>

                        <ul class="dropdown-menu shadow border-0">
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/export/excel') ?>">
                                    <i class="bi bi-file-earmark-excel me-2"></i>Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url('admin/export/word') ?>">
                                    <i class="bi bi-file-earmark-word me-2"></i>Word
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">

                            <thead>
                                <tr class="small text-secondary text-uppercase">
                                    <th>NO</th>
                                    <th>WAKTU</th>
                                    <th>NAMA</th>
                                    <th>INSTANSI</th>
                                    <th>TUJUAN</th>
                                    <th>TTD</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($tamu)): ?>
                                    <?php $no = 1;
                                    foreach ($tamu as $row): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?>
                                            </td>
                                            <td class="fw-bold"><?= esc($row['nama']) ?></td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info px-3">
                                                    <?= esc($row['asal']) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($row['tujuan']) ?></td>
                                            <td>
                                                <img src="<?= $row['tanda_tangan'] ?>" class="border shadow-sm img-ttd">
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= base_url('admin/hapus/' . $row['id']) ?>"
                                                    class="btn btn-sm btn-light text-danger rounded-circle"
                                                    onclick="return confirm('Hapus data?')">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            Belum ada data kunjungan.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- ================= MODAL QR CODE ================= -->
        <div class="modal fade" id="modalQr" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-white">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-qr-code-scan me-2 text-primary"></i>QR Code Buku Tamu
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Silakan scan QR untuk membuka halaman buku tamu online DPMD KEPRI.
                        </p>

                        <div class="d-flex justify-content-center mb-3">
                            <div class="bg-white border rounded-4 p-3 shadow-sm" style="width: 280px;">
                                <div id="qrcode" class="d-flex justify-content-center"></div>
                            </div>
                        </div>

                        <label class="form-label small text-muted mb-1">Link yang dibuka saat scan</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="qrUrl" readonly>
                            <button class="btn btn-outline-secondary" type="button" id="copyBtn" title="Salin link">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                        <div class="form-text" id="copyHint">Klik tombol untuk menyalin link.</div>

                        <div class="d-flex gap-2 mt-3 flex-wrap">
                            <button type="button" class="btn btn-primary rounded-pill px-4" id="refreshBtn">
                                <i class="bi bi-arrow-repeat me-1"></i>Refresh QR
                            </button>

                            <a class="btn btn-outline-primary rounded-pill px-4" target="_blank" id="openBtn" href="#">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Buka Link
                            </a>
                        </div>

                        <div class="alert alert-info border-0 mt-3 mb-0 small">
                            <i class="bi bi-info-circle me-2"></i>
                             <b>Klik refresh jika QR tidak muncul atau gagal ditampilkan.</b>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- QRCode JS -->
        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

        <script>
            // ================== CHART ==================
            const ctx = document.getElementById('chartTamu').getContext('2d');
            const dataGrafik = <?= json_encode($grafik) ?>;

            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(52,152,219,0.4)');
            gradient.addColorStop(1, 'rgba(52,152,219,0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: (dataGrafik || []).map(i => i.tanggal),
                    datasets: [{
                        label: 'Jumlah Tamu',
                        data: (dataGrafik || []).map(i => i.jumlah),
                        borderColor: '#3498db',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // ================== QR CODE ==================
            (function () {
                const qrWrap = document.getElementById('qrcode');
                const qrUrlInput = document.getElementById('qrUrl');
                const openBtn = document.getElementById('openBtn');
                const copyBtn = document.getElementById('copyBtn');
                const copyHint = document.getElementById('copyHint');
                const refreshBtn = document.getElementById('refreshBtn');

                // PATH saja (tanpa host). Contoh: /BukuTamu/public
                let targetPath = "<?= parse_url(rtrim(base_url('/'), '/'), PHP_URL_PATH) ?? '/' ?>";

                function normalizePath(p) {
                    if (!p) return '';
                    if (!p.startsWith('/')) p = '/' + p;
                    return p.replace(/\/+$/, '');
                }

                targetPath = normalizePath(targetPath);

                function buildUrl() {
                    return window.location.origin + targetPath;
                }

                function renderQR() {
                    const url = buildUrl();

                    qrWrap.innerHTML = "";
                    qrUrlInput.value = url;
                    openBtn.href = url;

                    new QRCode(qrWrap, {
                        text: url,
                        width: 240,
                        height: 240
                    });

                    copyHint.textContent = "Klik tombol untuk menyalin link.";
                }

                const modal = document.getElementById('modalQr');
                modal.addEventListener('shown.bs.modal', renderQR);

                refreshBtn.addEventListener('click', renderQR);

                copyBtn.addEventListener('click', async () => {
                    try {
                        await navigator.clipboard.writeText(qrUrlInput.value);
                        copyHint.textContent = "Link berhasil disalin.";
                    } catch (e) {
                        qrUrlInput.select();
                        document.execCommand('copy');
                        copyHint.textContent = "Link berhasil disalin.";
                    }
                });
            })();
        </script>

    </body>

</html>