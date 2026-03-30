<?php

namespace App\Controllers;

use App\Models\TamuModel;

class Admin extends BaseController
{
    public function __construct()
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to(base_url('auth'))->send();
            exit;
        }
    }

    public function index()
    {
        $model = new TamuModel();

        // filter bulan & tahun (GET)
        $bulan = (int) ($this->request->getGet('bulan') ?? date('n'));
        $tahun = (int) ($this->request->getGet('tahun') ?? date('Y'));

        if ($bulan < 1 || $bulan > 12)
            $bulan = (int) date('n');
        if ($tahun < 2000 || $tahun > 2100)
            $tahun = (int) date('Y');

        // TABEL: ikut filter
        $data['tamu'] = $model
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // CARD statistik (tetap realtime)
        $data['total_hari_ini'] = $model
            ->where('DATE(created_at)', date('Y-m-d'))
            ->countAllResults();

        $data['total_bulan_ini'] = $model
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->countAllResults();

        $data['total_semua'] = $model->countAllResults();

        // GRAFIK: sesuai bulan dipilih (zero-fill)
        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

        $rawGrafik = $model
            ->select("COUNT(id) as jumlah, DATE(created_at) as tanggal")
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->groupBy("DATE(created_at)")
            ->orderBy("tanggal", "ASC")
            ->findAll();

        $grafikMapped = [];
        for ($d = 1; $d <= $jumlahHari; $d++) {
            $tgl = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
            $index = array_search($tgl, array_column($rawGrafik, 'tanggal'));

            $grafikMapped[] = [
                'tanggal' => $d,
                'jumlah' => ($index !== false) ? (int) $rawGrafik[$index]['jumlah'] : 0
            ];
        }
        $data['grafik'] = $grafikMapped;

        // nama bulan Indonesia
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

        $data['nama_bulan'] = ($bulanMap[$bulan] ?? 'Bulan') . ' ' . $tahun;
        $data['bulan_aktif'] = $bulan;
        $data['tahun_aktif'] = $tahun;

        // list tahun dropdown
        $tahunDB = $model
            ->select("YEAR(created_at) as tahun")
            ->groupBy("YEAR(created_at)")
            ->orderBy("tahun", "DESC")
            ->findAll();

        $tahunList = [];
        foreach ($tahunDB as $row)
            $tahunList[] = (int) $row['tahun'];
        if (empty($tahunList))
            $tahunList = [(int) date('Y')];

        $data['tahun_list'] = $tahunList;

        return view('admin_buku_tamu', $data);
    }

    public function hapus($id)
    {
        $model = new TamuModel();
        $cekData = $model->find($id);

        if ($cekData) {
            $model->delete($id);
            return redirect()->to('/admin')->with('pesan', 'Data kunjungan berhasil dihapus!');
        }

        return redirect()->to('/admin')->with('error', 'Data tidak ditemukan!');
    }

    public function export($type)
    {
        $model = new TamuModel();

        // ambil filter jika ada
        $bulan = (int) ($this->request->getGet('bulan') ?? 0);
        $tahun = (int) ($this->request->getGet('tahun') ?? 0);

        $query = $model->orderBy('created_at', 'DESC');

        $periodeLabel = '';

        if ($bulan >= 1 && $bulan <= 12 && $tahun >= 2000 && $tahun <= 2100) {

            $query->where('MONTH(created_at)', $bulan)
                  ->where('YEAR(created_at)', $tahun);

            $filename = "Rekap_Tamu_" . sprintf('%04d-%02d', $tahun, $bulan);
            $periodeLabel = sprintf('%02d-%04d', $bulan, $tahun);

        } else {

            $filename = "Rekap_Tamu_" . date('Y-m-d');

        }

        $tamu = $query->findAll();

        // PERBAIKAN: ubah tanda tangan menjadi file/URL yang aman untuk Word & Excel
        $tamu = $this->prepareSignaturesForExport($tamu);

        $data = [
            'tamu' => $tamu,
            'tanggal_now' => date('d-m-Y H:i'),
            'periode' => $periodeLabel
        ];

        if ($type === 'excel') {

            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename={$filename}.xls");

        } elseif ($type === 'word') {

            header("Content-type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename={$filename}.doc");

        } else {

            return redirect()->to('/admin');

        }

        return view('admin_export', $data);
    }

    public function qr()
    {
        return view('admin/qr');
    }

    /**
     * Siapkan field tanda_tangan_export agar gambar bisa muncul di export Word/Excel.
     * Tidak mengubah field asli, hanya menambah field baru: tanda_tangan_export
     */
    private function prepareSignaturesForExport(array $rows): array
    {
        foreach ($rows as &$row) {
            $signature = $row['tanda_tangan'] ?? '';
            $row['tanda_tangan_export'] = $this->resolveSignatureForExport($signature, $row['id'] ?? uniqid());
        }

        return $rows;
    }

    /**
     * Konversi tanda tangan menjadi URL gambar yang bisa dibaca Word/Excel.
     */
    private function resolveSignatureForExport(?string $signature, $id = null): ?string
    {
        if (empty($signature)) {
            return null;
        }

        $signature = trim($signature);

        // 1. Jika data base64 dari canvas
        if (preg_match('/^data:image\/(\w+);base64,/', $signature, $matches)) {
            $extension = strtolower($matches[1]);

            if (!in_array($extension, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                $extension = 'png';
            }

            $imageData = substr($signature, strpos($signature, ',') + 1);
            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return null;
            }

            $folder = FCPATH . 'uploads/signatures/';
            if (!is_dir($folder)) {
                mkdir($folder, 0775, true);
            }

            $filename = 'ttd_' . $id . '_' . time() . '.' . $extension;
            $fullPath = $folder . $filename;

            if (file_put_contents($fullPath, $imageData) !== false) {
                return base_url('uploads/signatures/' . $filename);
            }

            return null;
        }

        // 2. Jika sudah berupa URL penuh
        if (filter_var($signature, FILTER_VALIDATE_URL)) {
            return $signature;
        }

        // 3. Jika path relatif dari folder public
        $cleanPath = ltrim($signature, '/');
        if (is_file(FCPATH . $cleanPath)) {
            return base_url($cleanPath);
        }

        // 4. Jika tersimpan misalnya "uploads/signatures/xxx.png" atau nama file saja
        if (is_file(FCPATH . 'uploads/signatures/' . basename($signature))) {
            return base_url('uploads/signatures/' . basename($signature));
        }

        return null;
    }
}