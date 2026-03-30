<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - QR Code Buku Tamu</title>

    <?php
    $ASSET = rtrim(base_url('assets'), '/') . '/';

    // Ambil URL utama aplikasi dari base_url
    // Ini akan otomatis mengikuti domain hosting, bukan IP local/wifi
    $QR_URL = rtrim(base_url('/'), '/');

    // Jika karena suatu alasan kosong, fallback ke base_url()
    if (empty($QR_URL)) {
        $QR_URL = rtrim(base_url(), '/');
    }
    ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= $ASSET ?>css/style-admin.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-4 navbar-admin">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="<?= base_url('admin') ?>">
                <div class="logo-icon-wrapper me-2">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <span class="brand-text">PANEL ADMIN DPMD</span>
            </a>

            <div class="d-flex gap-2">
                <a href="<?= base_url('/') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3">Lihat Form</a>
                <a href="<?= base_url('auth/logout') ?>"
                    class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container px-4 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <h5 class="m-0 fw-bold">
                            <i class="bi bi-qr-code-scan me-2 text-primary"></i>
                            QR Code Akses Buku Tamu
                        </h5>
                        <a href="<?= base_url('admin') ?>" class="btn btn-light btn-sm rounded-pill px-3">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <div class="d-flex flex-column align-items-center gap-3">
                            <div class="bg-white border rounded-4 p-3 shadow-sm" style="width: 280px;">
                                <div id="qrcode" class="d-flex justify-content-center"></div>
                            </div>

                            <div class="w-100">
                                <label class="form-label small text-muted mb-1">Link yang akan dibuka saat
                                    scan</label>
                                <div class="input-group input-group-login">
                                    <input type="text" class="form-control" id="qrUrl" readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="copyBtn">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <div class="form-text" id="copyHint">Klik tombol untuk menyalin link.</div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap justify-content-center">
                                <button class="btn btn-primary rounded-pill px-4" type="button" id="refreshBtn">
                                    <i class="bi bi-arrow-repeat me-1"></i> Refresh QR
                                </button>
                                <a class="btn btn-outline-primary rounded-pill px-4" target="_blank" id="openBtn"
                                    href="#">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Link
                                </a>
                            </div>

                            <div class="alert alert-info border-0 w-100 mt-2 mb-0 small">
                                <i class="bi bi-info-circle me-2"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <script>
        (function () {
            console.log("QR_SCRIPT_VERSION = v4_hosting_domain_fixed");

            const qrWrap = document.getElementById('qrcode');
            const qrUrlInput = document.getElementById('qrUrl');
            const openBtn = document.getElementById('openBtn');
            const copyBtn = document.getElementById('copyBtn');
            const copyHint = document.getElementById('copyHint');
            const refreshBtn = document.getElementById('refreshBtn');

            // SATU-SATUNYA SUMBER URL: DARI PHP (FULL URL DOMAIN HOSTING)
            const url = <?= json_encode($QR_URL ?? '') ?>;

            if (!url) {
                qrUrlInput.value = "URL QR kosong (cek $QR_URL di PHP).";
                copyHint.textContent = "URL tidak terbentuk. Cek konfigurasi server.";
                return;
            }

            function renderQR() {
                console.log("Render QR URL:", url);

                qrWrap.innerHTML = "";
                qrUrlInput.value = url;
                openBtn.href = url;

                new QRCode(qrWrap, { text: url, width: 240, height: 240 });
                copyHint.textContent = "Klik tombol untuk menyalin link.";
            }

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

            refreshBtn.addEventListener('click', renderQR);
            renderQR();
        })();
    </script>
</body>

</html>