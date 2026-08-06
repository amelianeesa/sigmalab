<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PersonilModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        helper(['form']);
        return view('auth/login');
    }

    public function processLogin()
    {
        $session = session();
        $model = new UserModel();
        
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        
        $user = $model->getUserWithRole($username);
        
        if ($user) {
            if (password_verify($password, $user['password'])) {
                if ($user['status_aktif'] == 1) {
                    $sesData = [
                        'users_id'   => $user['users_id'],
                        'username'   => $user['username'],
                        'email'      => $user['email'],
                        'role_id'    => $user['role_id'],
                        'nama_role'  => $user['nama_role'],
                        'logged_in'  => TRUE
                    ];
                    $session->set($sesData);
                    return redirect()->to('/dashboard');
                } else {
                    $session->setFlashdata('msg', 'Akun Anda non-aktif. Hubungi Admin.');
                    return redirect()->to('/login');
                }
            } else {
                $session->setFlashdata('msg', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Username atau Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }

    public function register()
    {
        helper(['form']);
        $db = \Config\Database::connect();
        $data['roles'] = $db->table('roles')->get()->getResultArray();
        $data['personil'] = $db->table('personil')->get()->getResultArray();
        return view('auth/register', $data);
    }

    public function processRegister()
    {
        helper(['form']);
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'    => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password' => 'required|min_length[6]|max_length[255]',
            'role_id'  => 'required'
        ];

        if ($this->validate($rules)) {
            $userModel = new UserModel();
            
            $data = [
                'personil_id' => $this->request->getVar('personil_id') ?: NULL,
                'username'    => $this->request->getVar('username'),
                'email'       => $this->request->getVar('email'),
                'password'    => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'role_id'     => $this->request->getVar('role_id'),
                'status_aktif'=> 1
            ];
            
            $userModel->save($data);
            session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
            return redirect()->to('/login');
        } else {
            session()->setFlashdata('validation', $this->validator);
            return redirect()->back()->setInputData($this->request->getPost());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}