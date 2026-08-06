<?php namespace App\Controllers;

use App\Models\PersonilModel;
use App\Models\KompetensiModel;
use CodeIgniter\Controller;

class SdmController extends Controller
{
    public function index()
    {
        if (!session()->get('logged_in')) { return redirect()->to('/login'); }
        
        $personilModel = new PersonilModel();
        $data['personil'] = $personilModel->findAll();
        
        return view('sdm/index', $data);
    }

    public function kompetensi()
    {
        if (!session()->get('logged_in')) { return redirect()->to('/login'); }
        
        $kompetensiModel = new KompetensiModel();
        $data['kompetensi'] = $kompetensiModel->getKompetensiWithPersonil();
        
        return view('sdm/kompetensi', $data);
    }

    public function storePersonil()
    {
        $personilModel = new PersonilModel();
        
        $fileCv = $this->request->getFile('file_cv');
        $fileName = null;
        if ($fileCv && $fileCv->isValid() && !$fileCv->hasMoved()) {
            $fileName = $fileCv->getRandomName();
            $fileCv->move(ROOTPATH . 'public/uploads/cv', $fileName);
        }

        $personilModel->save([
            'nama'       => $this->request->getPost('nama'),
            'jabatan'    => $this->request->getPost('jabatan'),
            'unit_kerja' => $this->request->getPost('unit_kerja'),
            'no_induk'   => $this->request->getPost('no_induk'),
            'file_cv'    => $fileName,
            'status_aktif'=> 1
        ]);

        return redirect()->to('/sdm')->with('success', 'Data personil berhasil ditambahkan.');
    }
}