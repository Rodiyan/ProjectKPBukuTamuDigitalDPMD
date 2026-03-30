<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        try {
            $session = session();
            $model   = new UserModel();

            $username = trim((string) $this->request->getPost('username'));
            $password = trim((string) $this->request->getPost('password'));

            if ($username === '' || $password === '') {
                $session->setFlashdata('error', 'Username dan password wajib diisi!');
                return redirect()->to(base_url('admin/login'));
            }

            $user = $model->where('username', $username)->first();

            if (!$user) {
                $session->setFlashdata('error', 'Username tidak ditemukan!');
                return redirect()->to(base_url('admin/login'));
            }

            $passwordDb = isset($user['password']) ? (string) $user['password'] : '';

            if ($passwordDb === '') {
                $session->setFlashdata('error', 'Data password user tidak valid!');
                return redirect()->to(base_url('admin/login'));
            }

            $isPasswordValid = false;

            if (password_verify($password, $passwordDb)) {
                $isPasswordValid = true;
            } elseif ($password === $passwordDb) {
                $isPasswordValid = true;
            }

            if (!$isPasswordValid) {
                $session->setFlashdata('error', 'Password salah!');
                return redirect()->to(base_url('admin/login'));
            }

            $sessData = [
                'id'         => $user['id'] ?? null,
                'username'   => $user['username'] ?? $username,
                'isLoggedIn' => true,
            ];

            $session->set($sessData);

            return redirect()->to(base_url('admin'));
        } catch (\Throwable $e) {
            log_message('error', 'Auth login error: ' . $e->getMessage());
            log_message('error', 'Auth login trace: ' . $e->getTraceAsString());

            session()->setFlashdata('error', 'Terjadi error saat login: ' . $e->getMessage());
            return redirect()->to(base_url('admin/login'));
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login'));
    }
}