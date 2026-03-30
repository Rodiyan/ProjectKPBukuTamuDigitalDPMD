<!-- login.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - DPMD Kepri</title>

  <?php $ASSET = rtrim(base_url('assets'), '/') . '/'; ?>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= $ASSET ?>css/style-login.css">
</head>

<body class="captive-bg">

  <!-- NAVBAR ATAS (SINGLE LOGO SAJA) -->
  <header class="top-nav">
    <div class="nav-left">
      <img class="nav-logo" src="<?= $ASSET ?>img/logo-kepri.png" alt="Logo Kepri">
      <span class="nav-title">PEMERINTAH PROVINSI<br>KEPULAUAN RIAU</span>
    </div>
  </header>

  <!-- KONTEN UTAMA -->
  <main class="portal-wrap">
    <section class="portal-left">

      <h1 class="portal-title">Login Admin</h1>
      <p class="portal-subtitle">Selamat Datang, Silahkan Login</p>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm small portal-alert">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('auth/login') ?>" method="post" class="portal-form">
        <?= csrf_field() ?>

        <div class="portal-grid">
          <div class="portal-field">
            <label class="portal-label">Username <span class="req">*</span></label>
            <input type="text" name="username" class="portal-input" placeholder="Username" required autofocus>
          </div>

          <div class="portal-field">
            <label class="portal-label">Password <span class="req">*</span></label>

            <div class="portal-password">
              <input type="password" name="password" id="password" class="portal-input"
                placeholder="Password" required aria-label="Password" aria-describedby="togglePasswordBtn">
              <button class="portal-eye" type="button" id="togglePasswordBtn"
                aria-label="Tampilkan password" title="Tampilkan/Sembunyikan password">
                <i class="bi bi-eye" id="togglePasswordIcon"></i>
              </button>
            </div>
          </div>
        </div>

        <button type="submit" class="portal-btn">
          <i class="bi bi-send me-2"></i> LOGIN
        </button>

        <div class="mt-3">
          <a href="<?= base_url('/') ?>" class="portal-back">← Kembali ke Halaman Utama</a>
        </div>
      </form>

    </section>

    <!-- SEBELAH FORM LOGIN (DOUBLE LOGO + GARIS PEMBATAS PUTIH) -->
    <aside class="portal-right">
      <div class="logo-panel">
        <img class="side-logo" src="<?= $ASSET ?>img/logo-kepri.png" alt="Logo Kepri">
        <span class="side-divider" aria-hidden="true"></span>
        <img class="side-logo side-logo-dpmd" src="<?= $ASSET ?>img/logo-dpmd.png" alt="Logo DPMD">
      </div>
    </aside>
  </main>

  <script>
    (function () {
      const passwordInput = document.getElementById('password');
      const toggleBtn = document.getElementById('togglePasswordBtn');
      const toggleIcon = document.getElementById('togglePasswordIcon');

      toggleBtn.addEventListener('click', function () {
        const isHidden = passwordInput.type === 'password';
        passwordInput.type = isHidden ? 'text' : 'password';

        toggleIcon.classList.toggle('bi-eye', !isHidden);
        toggleIcon.classList.toggle('bi-eye-slash', isHidden);

        toggleBtn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
      });
    })();
  </script>

</body>
</html>