<!DOCTYPE html>
<html lang="id" xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns:w="urn:schemas-microsoft-com:office:word">
<head>
    <meta charset="utf-8">
    <title>Export Buku Tamu</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 12px; }
        th { text-align: center; }
    </style>
</head>
<body>

    <h3>Data Buku Tamu Digital - DPMD Kepri</h3>

    <?php if (!empty($periode)): ?>
        <p>Periode: <?= esc($periode) ?></p>
    <?php endif; ?>

    <p>Tanggal Export: <?= esc($tanggal_now) ?></p>

    <table>
        <tr>
            <th width="40">No</th>
            <th width="120">Waktu</th>
            <th width="140">Nama</th>
            <th width="140">Instansi</th>
            <th width="180">Tujuan</th>
            <th width="110">No HP</th>
            <th width="90" style="width:90px; text-align:center;">Tanda Tangan</th>
        </tr>

        <?php if (!empty($tamu)): ?>
            <?php foreach ($tamu as $i => $row): ?>
                <tr>
                    <td align="center"><?= $i + 1 ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                    <td><?= esc($row['nama']) ?></td>
                    <td><?= esc($row['asal']) ?></td>
                    <td><?= esc($row['tujuan']) ?></td>
                    <td style='mso-number-format:"\@";'><?= esc($row['no_hp']) ?></td>
                    <td width="90" height="50" align="center" valign="middle" style="width:90px; height:50px; text-align:center; vertical-align:middle; overflow:hidden; padding:2px;">
                        <?php if (!empty($row['tanda_tangan_export'])): ?>
                            <img src="<?= esc($row['tanda_tangan_export']) ?>"
                                 alt="Tanda Tangan"
                                 width="80"
                                 height="35"
                                 style="display:block; width:80px; height:35px; margin:0 auto; border:0;">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" align="center">Tidak ada data</td>
            </tr>
        <?php endif; ?>
    </table>

</body>
</html>