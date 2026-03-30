<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <!-- penting: user-scalable=no membantu mencegah gesture zoom saat tanda tangan -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buku Tamu Digital - DPMD Kepri</title>

        <?php
        // Prefix assets yang aman: .../BukuTamu/public/assets/
        $ASSET = rtrim(base_url('assets'), '/') . '/';
        ?>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <link rel="stylesheet" href="<?= $ASSET ?>css/style-tamu.css">
    </head>

    <body class="bg-light">

        <header class="header-metalic">
            <div class="header-inner">

                <!-- kiri: logo + divider -->
                <div class="header-logos">
                    <img src="<?= $ASSET ?>img/logo-dpmd.png" alt="Logo DPMD" class="header-logo"
                        onerror="this.style.display='none'">

                    <span class="logo-divider" aria-hidden="true"></span>

                    <img src="<?= $ASSET ?>img/logo-kepri.png" alt="Logo Kepri" class="header-logo header-logo-kepri"
                        onerror="this.style.display='none'">
                </div>

                <!-- kanan: text -->
                <div class="header-text">
                    <h3 class="m-0 text-dark">Buku Tamu Digital</h3>
                    <p class="m-0 text-secondary">DPMD Provinsi Kepulauan Riau</p>
                </div>

            </div>
        </header>

        <div class="container mt-5 form-page">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h4 class="card-title mb-4">Formulir Pengisian Tamu</h4>

                    <?php if (session()->getFlashdata('pesan')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('pesan') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('home/simpan') ?>" method="POST" id="formTamu">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor HP</label>
                                <input type="number" name="no_hp" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Instansi / Asal</label>
                            <input type="text" name="asal" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tujuan / Keperluan</label>
                            <textarea name="tujuan" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanda Tangan</label>

                            <div class="signature-wrap">
                                <canvas id="signature-pad"></canvas>
                            </div>

                            <input type="hidden" name="tanda_tangan" id="tanda_tangan" required>

                            <div class="d-flex gap-2 flex-wrap mt-2">
                                <button type="button" class="btn btn-sm btn-warning" id="clear-signature">
                                    Hapus Tanda Tangan
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Kirim Buku Tamu
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= FOOTER ================= -->
        <footer class="footer-dpmd mt-5">
            <div class="container footer-wrap">
                <div class="row g-4 align-items-stretch footer-top">

                    <!-- Brand (lebih lebar) -->
                    <div class="col-lg-5">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="<?= $ASSET ?>img/logo-dpmd.png" alt="Logo DPMD" style="height:50px;"
                                onerror="this.style.display='none'">
                            <div>
                                <h5 class="mb-0 fw-bold">DPMD Kepri</h5>
                                <small class="text-white-50">Buku Tamu Digital</small>
                            </div>
                        </div>

                        <p class="text-white-75 small mb-3">
                            Ikuti sosial media kami untuk mendapatkan informasi terbaru seputar layanan dan kegiatan
                            DPMD.
                        </p>

                        <div class="social-group">
                            <a href="https://www.instagram.com/dpmddukcapil.provkepri?igsh=MWF1eHdhZzV5aXNoZw=="
                                class="btn-social instagram">
                                <i class="bi bi-instagram"></i> @dpmd_kepri
                            </a>

                            <a href="https://www.facebook.com/share/1821sLwG1K/" class="btn-social facebook">
                                <i class="bi bi-facebook"></i> DPMD Kepri
                            </a>

                            <a href="https://pmddukcapil.kepriprov.go.id/?fbclid=IwVERDUAQLULdleHRuA2FlbQIxMABzcnRjBmFwcF9pZAwzNTA2ODU1MzE3MjgAAR5U3CmsPSEwiOEEJT-a01OxQeqfTlwjLBR0iTF3YOzElbTzNTCvLqrbNaVSuQ_aem_rL6WKa4Qc_kQASVZNPWg_A"
                                class="btn-social email">
                                <i class="bi bi-envelope"></i> dpmddukcapil.provkepri
                            </a>
                        </div>
                    </div>

                    <!-- Informasi -->
                    <div class="col-lg-3">
                        <h6 class="fw-bold mb-3">Informasi</h6>
                        <ul class="list-unstyled text-white-75 small m-0">
                            <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>Dompak, Tanjungpinang</li>
                            <li class="mb-2"><i class="bi bi-clock me-2"></i>Senin - Jumat (08.00 - 16.00 WIB)</li>
                            <li><i class="bi bi-info-circle me-2"></i>Harap mengisi buku tamu sebelum bertemu petugas.
                            </li>
                        </ul>
                    </div>

                    <!-- Map -->
                    <div class="col-lg-4">
                        <h6 class="fw-bold mb-3">Lokasi Kantor</h6>
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.310237198263!2d104.48208367465682!3d0.8805210991109747!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d965d77a1512f3%3A0xc6e4c896c4178e09!2sDinas%20Pemberdayaan%20Masyarakat%20dan%20Desa%20Provinsi%20Kepulauan%20Riau!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>

                <hr class="footer-line">

                <div class="footer-bottom text-center">
                    © <?= date('Y') ?> DPMD Provinsi Kepulauan Riau
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS (optional) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- ================== SIGNATURE PAD (FIX) ================== -->
        <script>
            (function () {
                const canvas = document.getElementById('signature-pad');
                const hidden = document.getElementById('tanda_tangan');
                const btnClear = document.getElementById('clear-signature');
                const form = document.getElementById('formTamu');

                if (!canvas || !hidden || !btnClear || !form) return;

                const ctx = canvas.getContext('2d', { willReadFrequently: true });

                let drawing = false;
                let hasSignature = false;

                function resizeCanvas() {
                    const rect = canvas.getBoundingClientRect();
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);

                    canvas.width = Math.floor(rect.width * ratio);
                    canvas.height = Math.floor(rect.height * ratio);

                    ctx.setTransform(1, 0, 0, 1, 0, 0);
                    ctx.scale(ratio, ratio);

                    ctx.lineWidth = 2;
                    ctx.lineCap = 'round';
                    ctx.lineJoin = 'round';
                    ctx.strokeStyle = '#111827';
                }

                function getPos(e) {
                    const rect = canvas.getBoundingClientRect();
                    return { x: e.clientX - rect.left, y: e.clientY - rect.top };
                }

                function startDraw(e) {
                    drawing = true;
                    hasSignature = true;
                    const p = getPos(e);
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                }

                function draw(e) {
                    if (!drawing) return;
                    const p = getPos(e);
                    ctx.lineTo(p.x, p.y);
                    ctx.stroke();
                }

                function endDraw() {
                    drawing = false;
                }

                function clearSignature() {
                    const rect = canvas.getBoundingClientRect();
                    ctx.clearRect(0, 0, rect.width, rect.height);
                    hidden.value = '';
                    hasSignature = false;
                }

                function exportToHidden() {
                    hidden.value = canvas.toDataURL('image/png');
                }

                resizeCanvas();
                window.addEventListener('resize', () => {
                    if (!drawing) resizeCanvas();
                });

                canvas.addEventListener('pointerdown', (e) => {
                    e.preventDefault();
                    canvas.setPointerCapture(e.pointerId);
                    startDraw(e);
                });
                canvas.addEventListener('pointermove', (e) => {
                    e.preventDefault();
                    draw(e);
                });
                canvas.addEventListener('pointerup', (e) => {
                    e.preventDefault();
                    endDraw();
                });
                canvas.addEventListener('pointercancel', (e) => {
                    e.preventDefault();
                    endDraw();
                });

                btnClear.addEventListener('click', clearSignature);

                form.addEventListener('submit', function (e) {
                    if (!hasSignature) {
                        e.preventDefault();
                        alert('Tanda tangan wajib diisi.');
                        return;
                    }
                    exportToHidden();
                    if (!hidden.value) {
                        e.preventDefault();
                        alert('Gagal menyimpan tanda tangan. Coba ulangi.');
                    }
                });
            })();
        </script>

    </body>

</html>