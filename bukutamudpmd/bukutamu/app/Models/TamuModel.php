<?php

namespace App\Models;

use CodeIgniter\Model;

class TamuModel extends Model
{
    protected $table         = 'tamu';
    protected $primaryKey    = 'id';
    
    // Hapus 'created_at' dari allowedFields jika menggunakan useTimestamps = true
    protected $allowedFields = ['nama', 'asal', 'tujuan', 'no_hp', 'tanda_tangan'];
    
    // Aktifkan Fitur Timestamps CI4
    protected $useTimestamps = true; 
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Kosongkan jika tabel Anda tidak punya kolom updated_at
}