<?php

namespace App\Controllers;

use App\Models\TamuModel;

class Home extends BaseController
{
    public function index()
    {
        return view('buku_tamu'); // Sesuaikan dengan nama file view Anda
    }

    public function simpan()
    {
        $model = new TamuModel();

        // Ambil data dari form
        $data = [
            'nama'          => $this->request->getPost('nama'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'asal'          => $this->request->getPost('asal'),
            'tujuan'        => $this->request->getPost('tujuan'),
            'tanda_tangan'  => $this->request->getPost('tanda_tangan'),
        ];

        // Proses Simpan
        if ($model->insert($data)) {
            return redirect()->to(base_url('/'))->with('pesan', 'Terima kasih! Data kunjungan Anda telah tersimpan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data.');
        }
    }
}