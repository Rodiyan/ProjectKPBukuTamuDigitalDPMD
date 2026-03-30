<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users'; // Sesuaikan dengan nama tabel user Anda
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password']; // Sesuaikan field di tabel Anda
}