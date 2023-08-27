<?php

namespace App\Controllers;

use App\Models\ProgramStudiModel;
use App\Models\MataKuliahModel;
use stdClass;

class Matakuliah extends SuratController
{
    private $st;

    function __construct()
    {
        $this->st = new MataKuliahModel();
        $this->ps = new ProgramStudiModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        // $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'surat_bandos' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        // dd($data);
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';
        $id = session('id');

        $jenis_user = session('jenis_user');

        if (in_array(session('username'), ["adminlab@gmail.com"])) {
            $jenis_user = "lab";
        }

        if (in_array(session('username'), ["adminperpus@gmail.com"])) {
            $jenis_user = "perpus";
        }

        if (in_array(session('username'), [
            'kaprodi-s1-ilkom.mipa@ugm.ac.id',
            'sekprodi-s1-ilkom.mipa@ugm.ac.id',
            'kaprodi-s1-elins.mipa@ugm.ac.id',
            'sekprodi-s1-elins.mipa@ugm.ac.id',
            'kaprodi-s1-matematika.mipa@ugm.ac.id',
            'sekprodi-s1-matematika.mipa@ugm.ac.id',
            'kaprodi-s1-statistika.mipa@ugm.ac.id',
            'sekprodi-s1-statistika.mipa@ugm.ac.id',
            'kaprodi-s1-aktuaria.mipa@ugm.ac.id',
            'sekprodi-s1-aktuaria.mipa@ugm.ac.id',
            'kaprodi-s1-kimia.mipa@ugm.ac.id',
            'sekprodi-s1-kimia.mipa@ugm.ac.id',
            'kaprodi-s1-fisika.mipa@ugm.ac.id',
            'sekprodi-s1-fisika.mipa@ugm.ac.id',
            'kaprodi-s1-geofisika.mipa@ugm.ac.id',
            'sekprodi-s1-geofisika.mipa@ugm.ac.id',
            'kaprodi-s2-ilkom.mipa@ugm.ac.id',
            'sekprodi-s2-ilkom.mipa@ugm.ac.id',
            'kaprodi-s2-matematika.mipa@ugm.ac.id',
            'sekprodi-s2-matematika.mipa@ugm.ac.id',
            'kaprodi-s2-kimia.mipa@ugm.ac.id',
            'sekprodi-s2-kimia.mipa@ugm.ac.id',
            'kaprodi-s2-fisika.mipa@ugm.ac.id',
            'sekprodi-s2-fisika.mipa@ugm.ac.id',
            'kaprodi-s3-ilkom.mipa@ugm.ac.id',
            'sekprodi-s3-ilkom.mipa@ugm.ac.id',
            'kaprodi-s3-matematika.mipa@ugm.ac.id',
            'sekprodi-s3-matematika.mipa@ugm.ac.id',
            'kaprodi-s3-kimia.mipa@ugm.ac.id',
            'sekprodi-s3-kimia.mipa@ugm.ac.id',
            'kaprodi-s3-fisika.mipa@ugm.ac.id',
            'sekprodi-s3-fisika.mipa@ugm.ac.id',
        ])) {
            $jenis_user = "prodi";
        }

        if (in_array(session('username'), ["ksak.mipa@ugm.ac.id"])) {
            $jenis_user = "akademik";
        }

        $id = session('id');
        $this->st->select('mata_kuliah.*')
            ->where("CONCAT(kode, nama, prodi) LIKE '%$q%'")
            ->where("keterangan LIKE '%$status%'")
            ->orderBy($sort_column, $sort_order);

        $rows = $this->st->paginate(10);

        $this->ps->select('program_studi.*');

        $prodi = $this->ps->findAll();

        $data = [
            'rows' => $rows,
            'pager' => $this->st->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'prodi' => $prodi,
        ];
        return view('matakuliah/index', $data);
    }

    public function tambah()
    {
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $data = $this->request->getPost();
        $matkul['id'] = $id;
        $matkul['kode'] = $data['kodematkul'];
        $matkul['nama'] = $data['namamatkul'];
        $matkul['sks'] = (int) $data['sksmatkul'];
        $matkul['keterangan'] = $data['keterangan'];
        $matkul['prodi'] = $data['prodi'];
        $matkul['created_at'] = date('Y-m-d H:i:s');
        $this->st->insert($matkul);
        session()->setFlashdata('success', 'Matkul berhasil ditambahkan');
        return $this->response->redirect(site_url('matakuliah'));
    }

    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));

        (new MataKuliahModel)->where('id', $id)->delete();

        return $this->response->redirect(site_url('matakuliah'));
    }

    public function findmatkul()
    {
        $matkuls = $this->st->like('nama', $this->request->getGet('term'))->orLike('kode', $this->request->getGet('term'))->orLike('prodi', $this->request->getGet('term'))->findAll(3);
        ;

        $data = $matkuls;
        return $this->response->setJSON($data);
    }
}