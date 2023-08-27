<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\ProgramStudiModel;
use App\Models\YudisiumModel;
use stdClass;

class Yudisium extends SuratController
{
    private $st;

    function __construct()
    {
        $this->st = new YudisiumModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        // $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'surat_bandos' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';
        $id = session('id');
        $gol_verifikator = session('gol_verifikator');

        $jenis_user = session('jenis_user');

        if (in_array(session('username'), ["adminlab@gmail.com", "rudhi-wj@ugm.ac.id"])) {
            $jenis_user = "lab";
        }

        if (in_array(session('username'), ["adminperpus@gmail.com"])) {
            $jenis_user = "perpus";
        }

        if (
            in_array(session('username'), [
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
            ])
        ) {
            $jenis_user = "prodi";
        }

        $id = session('id');
        $this->st->select('yudisium.*, mahasiswa.nama, mahasiswa.nim, program_studi.nama as prodi, program_studi.jenjang as jenjang')
            ->join('mahasiswa', 'mahasiswa.user_id = yudisium.user_id', 'left')
            ->join('program_studi', 'program_studi.id = yudisium.prodi_id', 'left')
            ->where("(yudisium.user_id = $id OR ('$jenis_user' IN ('admin')AND status > 0) OR ('$jenis_user' IN ('verifikator') AND '$gol_verifikator' = 7 AND status > 0) OR ('$jenis_user' IN ('perpus', 'lab') AND status > 0) OR ('$jenis_user' IN ('prodi') AND status > 0))")
            ->orderBy($sort_column, $sort_order);

        $rows = $this->st->paginate(10);

        foreach ($rows as $row) {
            $row->komentar = json_decode($row->komentar);
        }

        $data = [
            'rows' => $rows,
            'pager' => $this->st->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'preview_id' => isset($data['preview_id']) ? $data['preview_id'] : null,
        ];
        return view('yudisium/index', $data);
    }

    public function lihat($id)
    {
        $row = (new YudisiumModel)->where('id', $id)->first();
        $row->prodi = (new ProgramStudiModel())->where('id', $row->prodi_id)->first();
        $row->mahasiswa = (new MahasiswaModel())->where('user_id', $row->user_id)->first();
        $row->matakuliah = json_decode($row->pengajuan_penghapusan_matkul);
        if ($row->list_matkul)
            $row->listmatkul = json_decode($row->list_matkul);
        else
            $row->listmatkul = [];
        $row->komentar = json_decode($row->komentar);

        $row->jumlahSks = 0;

        foreach ($row->listmatkul as $listmat) {
            if ($listmat)
                $row->jumlahSks += $listmat;
        }

        $row->sebaranSetelahHapus = [];

        // Sebaran nilai setelah pembatalan
        if ($row->pengajuan_penghapusan_matkul_status == 1 && !empty($row->matakuliah) && !empty($row->listmatkul)) {
            $nilaiA_batal = 0;
            $nilaiAmin_batal = 0;
            $nilaiAperB_batal = 0;
            $nilaiBplus_batal = 0;
            $nilaiB_batal = 0;
            $nilaiBmin_batal = 0;
            $nilaiBperC_batal = 0;
            $nilaiCplus_batal = 0;
            $nilaiC_batal = 0;
            $nilaiCmin_batal = 0;
            $nilaiCperD_batal = 0;
            $nilaiDplus_batal = 0;
            $nilaiD_batal = 0;
            $nilaiE_batal = 0;

            $sksBatal = 0;

            foreach ($row->matakuliah as $matBatal) {
                if ($matBatal->status != 1) {
                    continue;
                }
                $sksBatal += $matBatal->sks;
                if ($matBatal->nilai == "A") {
                    $nilaiA_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "A-") {
                    $nilaiAmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "A/B") {
                    $nilaiAperB_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B+") {
                    $nilaiBplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B") {
                    $nilaiB_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B-") {
                    $nilaiBmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B/C") {
                    $nilaiBperC_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C+") {
                    $nilaiCplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C") {
                    $nilaiC_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C-") {
                    $nilaiCmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C/D") {
                    $nilaiCperD_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "D+") {
                    $nilaiDplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "D") {
                    $nilaiD_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "E") {
                    $nilaiE_batal += $matBatal->sks;
                }
            }

            $ipk = $row->listmatkul->ipk;
            $sks = $row->listmatkul->sks;
            $dLama = $row->listmatkul->D;
            $dPlusLama = $row->listmatkul->{'D+'};
            $eLama = $row->listmatkul->E;
            if ($sksBatal > 0)
                $ipkSetelahBatal = (($ipk * $sks) - ($nilaiA_batal * 4 + $nilaiAmin_batal * 3.75 + $nilaiAperB_batal * 3.5 + $nilaiBplus_batal * 3.25 + $nilaiB_batal * 3 + $nilaiBmin_batal * 2.75 + $nilaiBperC_batal * 2.5 + $nilaiCplus_batal * 2.25 + $nilaiC_batal * 2 + $nilaiCmin_batal * 1.75 + $nilaiCperD_batal * 1.5 + $nilaiDplus_batal * 1.25 + $nilaiD_batal * 1 + $nilaiE_batal * 0)) / ($sks - $sksBatal);
            else
                $ipkSetelahBatal = $ipk;

            $sksSetelahBatal = $sks - $sksBatal;
            $pelanggaranJumlahD = (((($dPlusLama ? $dPlusLama : 0) - $nilaiDplus_batal) + (($dLama ? $dLama : 0) - $nilaiD_batal)) / $sksSetelahBatal) > 0.25;
            $pelanggaranTotalSks = $sksSetelahBatal < 144;
            $pelanggaranJumlahPenghapusan = ($sksBatal / $sks) > 0.10;
            $pelanggaranJumlahE = (($eLama ? $eLama : 0) - $nilaiE_batal) > 0;

            $row->sebaranSetelahHapus = [
                'ipkSetelahBatal' => $ipkSetelahBatal,
                'sksSetelahBatal' => $sksSetelahBatal,
                'pelanggaranJumlahD' => $pelanggaranJumlahD,
                'pelanggaranTotalSks' => $pelanggaranTotalSks,
                'pelanggaranJumlahPenghapusan' => $pelanggaranJumlahPenghapusan,
                'pelanggaranJumlahE' => $pelanggaranJumlahE
            ];
        }

        $data = [
            'action' => 'update',
            'row' => $row,
        ];

        return view('yudisium/lihat', $data);
    }

    public function prodiLihat($id)
    {
        $row = (new YudisiumModel)->where('id', $id)->first();
        $row->prodi = (new ProgramStudiModel())->where('id', $row->prodi_id)->first();
        $row->mahasiswa = (new MahasiswaModel())->where('user_id', $row->user_id)->first();
        $row->matakuliah = json_decode($row->pengajuan_penghapusan_matkul);
        $row->komentar = json_decode($row->komentar);
        $row->listmatkul = json_decode($row->list_matkul ?? "");

        $data = [
            'action' => 'update',
            'row' => $row,
        ];

        return view('yudisium/prodiLihat', $data);
    }

    public function create()
    {
        $row = new YudisiumModel();
        $row->mahasiswa = (new MahasiswaModel())->where('user_id', session('id'))->first();
        $row->matakuliah = [];
        $row->prodi = (new ProgramStudiModel())->findAll();
        $data = [
            'action' => 'store',
            'row' => $row,
        ];
        return view('yudisium/form', $data);
    }

    public function store()
    {
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $data = $this->request->getPost();
        $data['id'] = $id;

        $files = $this->request->getFiles();

        foreach ($files as $key => $file) {
            if (!empty($file) && !empty($file->getFileName())) {
                $data[$key] = $id . '.pdf';
                $file->move("upload/yudisium/$key", $id . '.pdf');
            }
        }

        if (!empty($data['kode_matkul'])) {
            $mhs = [];
            foreach ($data['kode_matkul'] as $key => $v) {
                $mhs[$key] = [
                    'kode_matkul' => $v,
                    'nama_matkul' => $data['nama_matkul'][$key],
                    'sks' => $data['sks'][$key],
                    'nilai' => $data['nilai'][$key],
                    'keterangan' => $data['keterangan'][$key],
                    'status' => 0
                ];
            }
            $data['pengajuan_penghapusan_matkul'] = json_encode($mhs);
        } else {
            $data['pengajuan_penghapusan_matkul'] = json_encode([]);
        }

        $komentar = [
            'akademik' => '',
            'prodi' => '',
            'perpus' => ''
        ];
        $data['komentar'] = json_encode($komentar);
        $data['user_id'] = session('id');
        $this->st->insert($data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('yudisium'));
    }

    public function edit($id)
    {
        $row = (new YudisiumModel)->where('id', $id)->first();
        $row->prodi = (new ProgramStudiModel())->where('id', $row->prodi_id)->first();
        $row->mahasiswa = (new MahasiswaModel())->where('user_id', session('id'))->first();
        $row->matakuliah = json_decode($row->pengajuan_penghapusan_matkul);

        $data = [
            'action' => 'update',
            'row' => $row,
        ];

        return view('yudisium/form', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();

        $files = $this->request->getFiles();


        foreach ($files as $key => $file) {
            if (!empty($file) && !empty($file->getFileName())) {
                $data[$key] = $id . '.pdf';
                $file->move("upload/yudisium/$key", $id . '.pdf');
            }
        }

        if (!empty($data['kode_matkul'])) {
            $mhs = [];
            foreach ($data['kode_matkul'] as $key => $v) {
                $mhs[$key] = [
                    'kode_matkul' => $v,
                    'nama_matkul' => $data['nama_matkul'][$key],
                    'sks' => $data['sks'][$key],
                    'nilai' => $data['nilai'][$key],
                    'keterangan' => $data['keterangan'][$key],
                    'status' => $data['status'][$key]
                ];
            }
            $data['pengajuan_penghapusan_matkul'] = json_encode($mhs);
        }

        $this->st->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil diupdate');
        return $this->response->redirect(site_url('yudisium'));
    }

    public function komentar($id)
    {
        $jenis_user = session('jenis_user');

        if (in_array(session('username'), ["adminlab@gmail.com", "rudhi-wj@ugm.ac.id"])) {
            $jenis_user = "lab";
        }

        if (in_array(session('username'), ["adminperpus@gmail.com"])) {
            $jenis_user = "perpus";
        }

        if (
            in_array(session('username'), [
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
            ])
        ) {
            $jenis_user = "prodi";
        }

        if (!in_array($jenis_user, ['admin', 'verifikator', "perpus", "lab", "prodi"])) {
            return redirect()->to(base_url());
        }
        $data = $this->request->getPost();

        if (!empty($data) && $data["aksi"] == "komentar") {
            $currKomentar = (new YudisiumModel)->where('id', $id)->first()->komentar;
            $decodeKomentar = json_decode($currKomentar);
            $pengirim = $data['pengirim'];

            if ($pengirim == 'akademik') {
                $decodeKomentar->admin = $data['komentar'];
            } else if ($pengirim == 'prodi') {
                $decodeKomentar->prodi = $data['komentar'];
            } else {
                $decodeKomentar->perpus = $data['komentar'];
            }

            $data['komentar'] = json_encode($decodeKomentar);
            $this->st->update($id, $data);
            session()->setFlashdata('success', 'Data berhasil diupdate');
        }

        return $this->response->redirect(site_url('yudisium'));
    }

    public function uploadAdmin($id)
    {
        $files = $this->request->getFiles();
        $data = $this->request->getPost();
        $jenis_user = session('jenis_user');

        if (!in_array($jenis_user, ['admin', 'verifikator'])) {
            return redirect()->to(base_url());
        }

        if (!empty($data) && $data["aksi"] == "komentar") {
            $currKomentar = (new YudisiumModel)->where('id', $id)->first()->komentar;
            $decodeKomentar = json_decode($currKomentar);
            $pengirim = $data['pengirim'];

            if ($pengirim == 'admin') {
                $decodeKomentar->admin = $data['komentar'];
            } else if ($pengirim == 'prodi') {
                $decodeKomentar->prodi = $data['komentar'];
            } else {
                $decodeKomentar->perpus = $data['komentar'];
            }

            $data['komentar'] = json_encode($decodeKomentar);
        }

        if (!empty($data) && $data["aksi"] == "upload") {
            foreach ($files as $key => $file) {
                if (!empty($file) && !empty($file->getFileName())) {
                    $data[$key] = $id . '.pdf';
                    $file->move("upload/yudisium/$key", $id . '.pdf');
                }
            }
        }

        if (!empty($data) && $data["aksi"] == "simpan") {
            $arr = ["sertifikat_ppsmb_status", "sertifikat_pengurus_ukm_status", "surat_bebas_pinjam_perpus_ugm_status", "dokumen_skripsi_final_status", "lembar_pengesahan_skripsi_status", "pernah_internasional_exposure_status"];

            foreach ($arr as $x => $val) {
                if (!empty($data[$val]))
                    $data[$val] = $data[$val] == "on" ? true : false;
            }
        }

        if (!in_array($data['aksi'], ['ajukan'])) {
            $data['status'] = 3;
            $this->st->update($id, $data);
            session()->setFlashdata('success', 'Data berhasil diupdate');
            return $this->response->redirect(site_url('yudisium/lihat/' . $id));
        }

        $this->st->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil diupdate');
        return $this->response->redirect(site_url('yudisium'));
    }

    public function approvebebaspinjam()
    {
        $data = $this->request->getPost();

        if (!$data) {
            return $this->response->redirect(site_url('yudisium'));
        }

        if (in_array(session("username"), ["adminlab@gmail.com", "rudhi-wj@ugm.ac.id"])) {
            $jenis_user = "lab";
        }

        if (in_array(session('username'), ["adminperpus@gmail.com"])) {
            $jenis_user = "perpus";
        }

        if (!in_array($jenis_user, ["perpus", "lab"])) {
            return $this->response->redirect(site_url('yudisium'));
        }

        foreach ($data["id"] as $id) {
            if ($jenis_user == "perpus") {
                // perpus
                $datas["status_pengajuan_surat_bebas_pinjam_perpus"] = 1;
            } else {
                // lab
                $datas["status_pengajuan_surat_bebas_pinjam_lab"] = 1;
            }
            $this->st->update($id, $datas);
        }
        session()->setFlashdata('success', 'Data berhasil diupdate');
        return $this->response->redirect(site_url('yudisium'));
    }

    public function cancelapprovebebaspinjam($id)
    {
        if (in_array(session("username"), ["adminlab@gmail.com", "rudhi-wj@ugm.ac.id"])) {
            $jenis_user = "lab";
        }

        if (!in_array($jenis_user, ["perpus", "lab"])) {
            return $this->response->redirect(site_url('yudisium'));
        }

        if ($jenis_user == "perpus") {
            // perpus
            $data["status_pengajuan_surat_bebas_pinjam_perpus"] = 0;
        } else {
            // lab
            $data["status_pengajuan_surat_bebas_pinjam_lab"] = 0;
        }
        $this->st->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil diupdate');
        return $this->response->redirect(site_url('yudisium'));
    }

    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));

        (new YudisiumModel)->where('id', $id)->delete();
        // check folder inside yudisium folder
        $folders = [
            'sertifikat_ppsmb',
            'sertifikat_pengurus_ukm',
            'surat_bebas_pinjam_perpus_ugm',
            'dokumen_skripsi_final',
            'lembar_pengesahan_skripsi',
            'profil_mahasiswa',
            'pernah_internasional_exposure'
        ];

        foreach ($folders as $folder) {
            if (file_exists(FCPATH . "upload/yudisium/$folder/$id.pdf"))
                unlink(FCPATH . "upload/yudisium/$folder/$id.pdf");
        }

        return $this->response->redirect(site_url('yudisium'));
    }

    public function approvehapusmatkul($id)
    {
        $data = $this->request->getPost();

        // dd($data);

        if (!$data) {
            return $this->response->redirect(site_url('yudisium'));
        }

        // if ($data['finalsks'] < 144) {
        //     session()->setFlashdata('danger', 'Total SKS akhir kurang dari 144');
        //     return $this->response->redirect(site_url('yudisium/prodiLihat/' . $id));
        // }

        // if ($data['finale'] > 0) {
        //     session()->setFlashdata('danger', 'Masih terdapat nilai E pada Mata Kuliah yang diambil');
        //     return $this->response->redirect(site_url('yudisium/prodiLihat/' . $id));
        // }

        // $jmld = ($data['finald'] + $data['finaldplus']) / $data['finalsks'];

        // if ($jmld > 0.25) {
        //     session()->setFlashdata('danger', 'Jumlah SKS D dan D+ melebihi 25% dari total SKS');
        //     return $this->response->redirect(site_url('yudisium/prodiLihat/' . $id));
        // }

        $matkul = json_decode($data['jsonmatkul']);
        $matkul_hapus = $data['matkul'];

        for ($i = 0; $i < count($matkul); $i++) {
            for ($j = 0; $j < count($matkul_hapus); $j++) {
                $temp = explode(',', $matkul_hapus[$j]);
                if ($matkul[$i]->kode_matkul == $temp[0]) {
                    $matkul[$i]->status = 1;
                }
            }
        }

        $data['pengajuan_penghapusan_matkul'] = json_encode($matkul);
        $data["pengajuan_penghapusan_matkul_status"] = 1;
        $this->st->update($id, $data);

        session()->setFlashdata('success', 'Approved');
        return $this->response->redirect(site_url('yudisium'));
    }

    public function cancelapprovehapusmatkul($id)
    {
        $data = $this->request->getPost();

        if (!$data) {
            return $this->response->redirect(site_url('yudisium'));
        }

        $matkul = json_decode($data['jsonmatkul']);

        for ($i = 0; $i < count($matkul); $i++) {
            $matkul[$i]->status = 0;
        }

        $data['pengajuan_penghapusan_matkul'] = json_encode($matkul);
        $data["pengajuan_penghapusan_matkul_status"] = 0;
        $this->st->update($id, $data);

        session()->setFlashdata('success', 'Penghapusan matkul berhasil dibatalkan');
        return $this->response->redirect(site_url('yudisium/prodilihat/' . $id));
    }

    public function viewPdf($folder, $id)
    {
        $data = file_get_contents(FCPATH . "upload/yudisium/$folder/$id.pdf");
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->appendHeader('Content-Disposition', 'inline; filename="' . $id . '"')
            ->appendHeader('Content-Transfer-Encoding', 'binary')
            ->appendHeader('Accept-Ranges', 'bytes')
            ->setBody($data)->send();
    }

    public function cetak($id)
    {
        $data = file_get_contents(FCPATH . "upload/yudisium/hasil/$id.pdf");
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->appendHeader('Content-Disposition', 'inline; filename="' . $id . '"')
            ->appendHeader('Content-Transfer-Encoding', 'binary')
            ->appendHeader('Accept-Ranges', 'bytes')
            ->setBody($data)->send();
    }

    public function simpanmatkul($id)
    {
        $data = $this->request->getPost();
        if (!empty($data)) {
            $mhs = [
                "ipk" => $data["ipk"] ?? 0,
                "sks" => $data["sks"] ?? 0,
                "D+" => $data["D+"] ?? 0,
                "D" => $data["D"] ?? 0,
                "E" => $data["E"] ?? 0,
            ];
            $data['list_matkul'] = json_encode($mhs);
        } else {
            $data['list_matkul'] = json_encode([]);
        }

        $this->st->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil diupdate');
        if ($data["aksi"] == 'prodi')
            return $this->response->redirect(site_url('yudisium/prodiLihat/' . $id));
        else
            return $this->response->redirect(site_url('yudisium/lihat/' . $id));
    }

    public function ajukanyudisium($id)
    {
        $data = $this->request->getPost();

        $jenis_user = session('jenis_user');

        if (!in_array($jenis_user, ['admin', 'verifikator'])) {
            return redirect()->to(base_url());
        }

        $get_syarat = $this->st->select('*')->where('id', $id)->first();

        $datas = [
            'sertifikat_ppsmb_status',
            'sertifikat_pengurus_ukm_status',
            'surat_bebas_pinjam_perpus_ugm_status',
            'dokumen_skripsi_final_status',
            'lembar_pengesahan_skripsi_status',
            'status_pengajuan_surat_bebas_pinjam_lab',
            'pernah_internasional_exposure_status',
            'pengajuan_penghapusan_matkul_status'
        ];

        foreach ($datas as $d) {
            if ($get_syarat->$d == 0) {
                session()->setFlashdata('danger', 'Data gagal diupdate, cek semua persyaratan');
                return $this->response->redirect(site_url('yudisium/lihat/' . $id));
            }
        }



        $row = (new YudisiumModel)->where('id', $id)->first();
        $row->matakuliah = json_decode($row->pengajuan_penghapusan_matkul);
        if ($row->list_matkul)
            $row->listmatkul = json_decode($row->list_matkul);
        else
            $row->listmatkul = [];

        if (empty($row->listmatkul)) {
            session()->setFlashdata('danger', 'Data gagal diupdate, nilai sebelum pembatalan tidak ditemukan');
            return $this->response->redirect(site_url('yudisium/lihat/' . $id));
        }

        if ($row->pengajuan_penghapusan_matkul_status == 1 && !empty($row->matakuliah)) {
            $nilaiA_batal = 0;
            $nilaiAmin_batal = 0;
            $nilaiAperB_batal = 0;
            $nilaiBplus_batal = 0;
            $nilaiB_batal = 0;
            $nilaiBmin_batal = 0;
            $nilaiBperC_batal = 0;
            $nilaiCplus_batal = 0;
            $nilaiC_batal = 0;
            $nilaiCmin_batal = 0;
            $nilaiCperD_batal = 0;
            $nilaiDplus_batal = 0;
            $nilaiD_batal = 0;
            $nilaiE_batal = 0;

            $sksBatal = 0;

            foreach ($row->matakuliah as $matBatal) {
                if ($matBatal->status != 1) {
                    continue;
                }
                $sksBatal += $matBatal->sks;
                if ($matBatal->nilai == "A") {
                    $nilaiA_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "A-") {
                    $nilaiAmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "A/B") {
                    $nilaiAperB_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B+") {
                    $nilaiBplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B") {
                    $nilaiB_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B-") {
                    $nilaiBmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B/C") {
                    $nilaiBperC_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C+") {
                    $nilaiCplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C") {
                    $nilaiC_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C-") {
                    $nilaiCmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C/D") {
                    $nilaiCperD_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "D+") {
                    $nilaiDplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "D") {
                    $nilaiD_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "E") {
                    $nilaiE_batal += $matBatal->sks;
                }
            }

            $sks = $row->listmatkul->sks;
            $dLama = $row->listmatkul->D;
            $dPlusLama = $row->listmatkul->{'D+'};
            $eLama = $row->listmatkul->E;

            $sksSetelahBatal = $sks - $sksBatal;
            $pelanggaranJumlahD = (((($dPlusLama ? $dPlusLama : 0) - $nilaiDplus_batal) + (($dLama ? $dLama : 0) - $nilaiD_batal)) / $sksSetelahBatal) > 0.25;
            $pelanggaranTotalSks = $sksSetelahBatal < 144;
            $pelanggaranJumlahPenghapusan = ($sksBatal / $sks) > 0.10;
            $pelanggaranJumlahE = (($eLama ? $eLama : 0) - $nilaiE_batal) > 0;

            $sebaranSetelahHapus = [
                'pelanggaranJumlahD' => $pelanggaranJumlahD,
                'pelanggaranTotalSks' => $pelanggaranTotalSks,
                'pelanggaranJumlahPenghapusan' => $pelanggaranJumlahPenghapusan,
                'pelanggaranJumlahE' => $pelanggaranJumlahE
            ];

            foreach ($sebaranSetelahHapus as $sebaran) {
                if ($sebaran) {
                    session()->setFlashdata('danger', 'Data gagal diupdate, cek pelanggaran');
                    return $this->response->redirect(site_url('yudisium/lihat/' . $id));
                }
            }
        }

        $data["lama_studi"] = $this->lamaStudi($data["mulai_studi"], $data["tanggal_yudisium"]);
        
        $this->st->update($id, $data);
        return $this->response->redirect(site_url('yudisium'));
    }

    private function lamaStudi($startDate, $endDate)
    {
        $ts1 = strtotime($startDate);
        $ts2 = strtotime($endDate);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        return $diff;
    }

    public function buatsheet()
    {
        $data = $this->request->getPost();
        // findall data with date tanggal_yudisium
        $tanggal_yudisium = $data['tanggal_yudisium'];
        $rows = $this->st->select('yudisium.*, mahasiswa.nama, mahasiswa.nim, program_studi.nama as prodi, program_studi.jenjang as jenjang')
            ->join('mahasiswa', 'mahasiswa.user_id = yudisium.user_id', 'left')
            ->join('program_studi', 'program_studi.id = yudisium.prodi_id', 'left')
            ->where("tanggal_yudisium = '$tanggal_yudisium'")
            ->orderBy('created_at', 'asc')
            ->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'UNIVERSITAS GADJAH MADA');
        $sheet->setCellValue('A2', 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM');
        $sheet->setCellValue('A4', 'Yudisium Tanggal: ' . $tanggal_yudisium);

        $sheet->setCellValue('A6', 'No')->setCellValue('B6', 'Nama')->setCellValue('C6', 'No. Mhs')->setCellValue('D6', 'Angkatan')->setCellValue('E6', 'Prodi')->setCellValue('F6', 'SKS Sebelum Pembatalan')->setCellValue('G6', 'SKS Dibatalkan')->setCellValue('H6', 'SKS Setelah Pembatalan')->setCellValue('I6', 'SKS Dengan Nilai D dan D+')->setCellValue('J6', 'IPK Sebelum Pembatalan')->setCellValue('K6', 'IPK Setelah Pembatalan')->setCellValue('L6', 'Predikat (C/SM/M)')->setCellValue('M6', 'Keterangan');
        // loop from rows length
        $length = 0;
        $jumlahIpk = 0;


        $jumlahLulusanToday = [
            "fisika" => 0,
            "geofisika" => 0,
            "elins" => 0,
            "kimia" => 0,
            "matematika" => 0,
            "statistika" => 0,
            "ilkom" => 0,
            "ilmu aktuaria" => 0,
            "jumlah" => 0
        ];
        $jumlahIpkPerProdi = [
            "fisika" => 0,
            "geofisika" => 0,
            "elins" => 0,
            "kimia" => 0,
            "matematika" => 0,
            "statistika" => 0,
            "ilkom" => 0,
            "ilmu aktuaria" => 0,
            "jumlah" => 0
        ];
        foreach ($rows as $row) {
            $row->matakuliahBatal = json_decode($row->pengajuan_penghapusan_matkul);
            $row->listMatkul = json_decode($row->list_matkul);

            $nilaiA_batal = 0;
            $nilaiAmin_batal = 0;
            $nilaiAperB_batal = 0;
            $nilaiBplus_batal = 0;
            $nilaiB_batal = 0;
            $nilaiBmin_batal = 0;
            $nilaiBperC_batal = 0;
            $nilaiCplus_batal = 0;
            $nilaiC_batal = 0;
            $nilaiCmin_batal = 0;
            $nilaiCperD_batal = 0;
            $nilaiDplus_batal = 0;
            $nilaiD_batal = 0;
            $nilaiE_batal = 0;

            $sksBatal = 0;

            foreach ($row->matakuliahBatal as $matBatal) {
                if ($matBatal->status != 1) {
                    continue;
                }
                $sksBatal += $matBatal->sks;
                if ($matBatal->nilai == "A") {
                    $nilaiA_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "A-") {
                    $nilaiAmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "A/B") {
                    $nilaiAperB_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B+") {
                    $nilaiBplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B") {
                    $nilaiB_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B-") {
                    $nilaiBmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "B/C") {
                    $nilaiBperC_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C+") {
                    $nilaiCplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C") {
                    $nilaiC_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C-") {
                    $nilaiCmin_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "C/D") {
                    $nilaiCperD_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "D+") {
                    $nilaiDplus_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "D") {
                    $nilaiD_batal += $matBatal->sks;
                } else if ($matBatal->nilai == "E") {
                    $nilaiE_batal += $matBatal->sks;
                }
            }

            $ipk = $row->listMatkul->ipk;
            $sks = $row->listMatkul->sks;
            if ($sksBatal > 0)
                $ipkSetelahBatal = (($ipk * $sks) - ($nilaiA_batal * 4 + $nilaiAmin_batal * 3.75 + $nilaiAperB_batal * 3.5 + $nilaiBplus_batal * 3.25 + $nilaiB_batal * 3 + $nilaiBmin_batal * 2.75 + $nilaiBperC_batal * 2.5 + $nilaiCplus_batal * 2.25 + $nilaiC_batal * 2 + $nilaiCmin_batal * 1.75 + $nilaiCperD_batal * 1.5 + $nilaiDplus_batal * 1.25 + $nilaiD_batal * 1 + $nilaiE_batal * 0)) / ($sks - $sksBatal);
            // $ipkSetelahBatal = ((floatval($row->listMatkul->A) - $nilaiA_batal) * 4 + (floatval($row->listMatkul->{"A-"}) - $nilaiAmin_batal) * 3.75 + (floatval($row->listMatkul->{"A/B"}) - $nilaiAperB_batal) * 3.5 + (floatval($row->listMatkul->{"B+"}) - $nilaiBplus_batal) * 3.25 + (floatval($row->listMatkul->{"B"}) - $nilaiB_batal) * 3 + (floatval($row->listMatkul->{"B-"}) - $nilaiBmin_batal) * 2.75 + (floatval($row->listMatkul->{"B/C"}) - $nilaiBperC_batal) * 2.5 + (floatval($row->listMatkul->{"C+"}) - $nilaiCplus_batal) * 2.25 + (floatval($row->listMatkul->{"C"}) - $nilaiC_batal) * 2 + (floatval($row->listMatkul->{"C-"}) - $nilaiCmin_batal) * 1.75 + (floatval($row->listMatkul->{"C/D"}) - $nilaiCperD_batal) * 1.5 + (floatval($row->listMatkul->{"D+"}) - $nilaiDplus_batal) * 1.25 + (floatval($row->listMatkul->{"D"}) - $nilaiD_batal) * 1 + (floatval($row->listMatkul->{"E"}) - $nilaiE_batal) * 0) / ($sks - $sksBatal);
            else
                $ipkSetelahBatal = $ipk;

            $predikat = "";

            if ($ipk >= 3.51 && $row->lama_studi <= 60) {
                $predikat = "C";
            } else if ($ipk >= 3.01) {
                $predikat = "SM";
            } else {
                $predikat = "M";
            }


            $sheet->setCellValue('A' . strval($length + 7), $length + 1);
            $sheet->setCellValue('B' . strval($length + 7), $row->nama);
            $sheet->setCellValue('C' . strval($length + 7), $row->nim);
            $sheet->setCellValue('D' . strval($length + 7), "20" . substr($row->nim, 0, 2));
            $sheet->setCellValue('E' . strval($length + 7), $row->prodi);
            $sheet->setCellValue('F' . strval($length + 7), $sks);
            $sheet->setCellValue('G' . strval($length + 7), $sksBatal);
            $sheet->setCellValue('H' . strval($length + 7), $sks - $sksBatal);
            $sheet->setCellValue('I' . strval($length + 7), floatval($row->listMatkul->{"D"}) - $nilaiD_batal + floatval($row->listMatkul->{"D+"}) - $nilaiDplus_batal);
            $sheet->setCellValue('J' . strval($length + 7), $ipk);
            $sheet->setCellValue('K' . strval($length + 7), $ipkSetelahBatal);
            $sheet->setCellValue('L' . strval($length + 7), $predikat);
            $sheet->setCellValue('M' . strval($length + 7), "Lengkap");

            $jumlahIpk += $ipkSetelahBatal;
            $length++;

            if ($row->prodi == "Fisika") {
                $jumlahLulusanToday["fisika"]++;
                $jumlahIpkPerProdi["fisika"] += $ipkSetelahBatal;
            } else if ($row->prodi == "Geofisika") {
                $jumlahLulusanToday["geofisika"]++;
                $jumlahIpkPerProdi["geofisika"] += $ipkSetelahBatal;
            } else if ($row->prodi == "Elektronika & Instrumentasi") {
                $jumlahLulusanToday["elins"]++;
                $jumlahIpkPerProdi["elins"] += $ipkSetelahBatal;
            } else if ($row->prodi == "Kimia") {
                $jumlahLulusanToday["kimia"]++;
                $jumlahIpkPerProdi["kimia"] += $ipkSetelahBatal;
            } else if ($row->prodi == "Matematika") {
                $jumlahLulusanToday["matematika"]++;
                $jumlahIpkPerProdi["matematika"] += $ipkSetelahBatal;
            } else if ($row->prodi == "Statistika") {
                $jumlahLulusanToday["statistika"]++;
                $jumlahIpkPerProdi["statistika"] += $ipkSetelahBatal;
            } else if (in_array($row->prodi, ["Ilmu Komputer", "Artificial Intelligence"])) {
                $jumlahLulusanToday["ilkom"]++;
                $jumlahIpkPerProdi["ilkom"] += $ipkSetelahBatal;
            } else if ($row->prodi == "Aktuaria") {
                $jumlahLulusanToday["ilmu aktuaria"]++;
                $jumlahIpkPerProdi["ilmu aktuaria"] += $ipkSetelahBatal;
            }
        }

        $jumlahLulusan = [
            "fisika" => 666,
            "geofisika" => 638,
            "elins" => 794,
            "kimia" => 1499,
            "matematika" => 594,
            "statistika" => 624,
            "ilkom" => 872,
            "ilmu aktuaria" => 5,
            "jumlah" => 5687
        ];

        // get last month and year
        $lastMonth = date("M", strtotime("-1 month"));
        $monthInt = date("m", strtotime("-1 month"));
        $year = date("Y", strtotime("-1 month"));
        $days = cal_days_in_month(CAL_GREGORIAN, $monthInt, $year);

        $yudisium = $this->st->select('yudisium.*, mahasiswa.nama, mahasiswa.nim, program_studi.nama as prodi, program_studi.jenjang as jenjang')
            ->join('mahasiswa', 'mahasiswa.user_id = yudisium.user_id', 'left')
            ->join('program_studi', 'program_studi.id = yudisium.prodi_id', 'left')
            ->where("MONTH(tanggal_yudisium) = '$monthInt' AND YEAR(tanggal_yudisium) = '$year'")
            ->orderBy('created_at', 'asc')
            ->findAll();

        $yudisiumAll = $this->st->select('yudisium.*, mahasiswa.nama, mahasiswa.nim, program_studi.nama as prodi, program_studi.jenjang as jenjang')
            ->join('mahasiswa', 'mahasiswa.user_id = yudisium.user_id', 'left')
            ->join('program_studi', 'program_studi.id = yudisium.prodi_id', 'left')
            ->where("MONTH(tanggal_yudisium) >= '01' AND YEAR(tanggal_yudisium) >= '2013' AND MONTH(tanggal_yudisium) < '$monthInt' AND YEAR(tanggal_yudisium) <= '$year'")
            ->orderBy('created_at', 'asc')
            ->findAll();

        foreach ($yudisiumAll as $yudAll) {
            if ($yudAll->prodi == "Fisika") {
                $jumlahLulusan["fisika"]++;
            } else if ($yudAll->prodi == "Geofisika") {
                $jumlahLulusan["geofisika"]++;
            } else if ($yudAll->prodi == "Elektronika & Instrumentasi") {
                $jumlahLulusan["elins"]++;
            } else if ($yudAll->prodi == "Kimia") {
                $jumlahLulusan["kimia"]++;
            } else if ($yudAll->prodi == "Matematika") {
                $jumlahLulusan["matematika"]++;
            } else if ($yudAll->prodi == "Statistika") {
                $jumlahLulusan["statistika"]++;
            } else if (in_array($yudAll->prodi, ["Ilmu Komputer", "Artificial Intelligence"])) {
                $jumlahLulusan["ilkom"]++;
            } else if ($yudAll->prodi == "Aktuaria") {
                $jumlahLulusan["ilmu aktuaria"]++;
            }
        }

        $jumlahLulusanLastMonth = [
            "fisika" => 0,
            "geofisika" => 0,
            "elins" => 0,
            "kimia" => 0,
            "matematika" => 0,
            "statistika" => 0,
            "ilkom" => 0,
            "ilmu aktuaria" => 0,
            "jumlah" => 0
        ];

        foreach ($yudisium as $yud) {
            if ($yud->prodi == "Fisika") {
                $jumlahLulusanLastMonth["fisika"]++;
            } else if ($yud->prodi == "Geofisika") {
                $jumlahLulusanLastMonth["geofisika"]++;
            } else if ($yud->prodi == "Elektronika & Instrumentasi") {
                $jumlahLulusanLastMonth["elins"]++;
            } else if ($yud->prodi == "Kimia") {
                $jumlahLulusanLastMonth["kimia"]++;
            } else if ($yud->prodi == "Matematika") {
                $jumlahLulusanLastMonth["matematika"]++;
            } else if ($yud->prodi == "Statistika") {
                $jumlahLulusanLastMonth["statistika"]++;
            } else if (in_array($yud->prodi, ["Ilmu Komputer", "Artificial Intelligence"])) {
                $jumlahLulusanLastMonth["ilkom"]++;
            } else if ($yud->prodi == "Aktuaria") {
                $jumlahLulusanLastMonth["ilmu aktuaria"]++;
            }
        }

        $sheet->setCellValue('A' . strval($length + 7 + 2), "REKAPITULASI YUDISIUM :");
        $sheet->setCellValue('A' . strval($length + 7 + 3), "PROGRAM STUDI")->setCellValue('D' . strval($length + 7 + 3), "Fisika")->setCellValue('E' . strval($length + 7 + 3), "Geofisika")->setCellValue('F' . strval($length + 7 + 3), "Elins")->setCellValue('G' . strval($length + 7 + 3), "Kimia")->setCellValue('H' . strval($length + 7 + 3), "Matematika")->setCellValue('I' . strval($length + 7 + 3), "Statistika")->setCellValue('J' . strval($length + 7 + 3), "Ilkom")->setCellValue('K' . strval($length + 7 + 3), "Ilmu Aktuaria")->setCellValue('L' . strval($length + 7 + 3), "Jumlah");
        $sheet->setCellValue('A' . strval($length + 7 + 4), "Jumlah lulusan  Tgl 21 Jan 2013 s/d tgl $days $lastMonth $year")->setCellValue('D' . strval($length + 7 + 4), $jumlahLulusan["fisika"] + $jumlahLulusanLastMonth["fisika"])->setCellValue('E' . strval($length + 7 + 4), $jumlahLulusan["geofisika"] + $jumlahLulusanLastMonth["geofisika"])->setCellValue('F' . strval($length + 7 + 4), $jumlahLulusan["elins"] + $jumlahLulusanLastMonth["elins"])->setCellValue('G' . strval($length + 7 + 4), $jumlahLulusan["kimia"] + $jumlahLulusanLastMonth["kimia"])->setCellValue('H' . strval($length + 7 + 4), $jumlahLulusan["matematika"] + $jumlahLulusanLastMonth["matematika"])->setCellValue('I' . strval($length + 7 + 4), $jumlahLulusan["statistika"] + $jumlahLulusanLastMonth["statistika"])->setCellValue('J' . strval($length + 7 + 4), $jumlahLulusan["ilkom"] + $jumlahLulusanLastMonth["ilkom"])->setCellValue('K' . strval($length + 7 + 4), $jumlahLulusan["ilmu aktuaria"] + $jumlahLulusanLastMonth["ilmu aktuaria"])->setCellValue('L' . strval($length + 7 + 4), $jumlahLulusan["jumlah"] + count($yudisium) + count($yudisiumAll));
        $sheet->setCellValue('A' . strval($length + 7 + 5), "Jumlah yudisium tgl $days $lastMonth $year")->setCellValue('D' . strval($length + 7 + 5), $jumlahLulusanLastMonth["fisika"])->setCellValue('E' . strval($length + 7 + 5), $jumlahLulusanLastMonth["geofisika"])->setCellValue('F' . strval($length + 7 + 5), $jumlahLulusanLastMonth["elins"])->setCellValue('G' . strval($length + 7 + 5), $jumlahLulusanLastMonth["kimia"])->setCellValue('H' . strval($length + 7 + 5), $jumlahLulusanLastMonth["matematika"])->setCellValue('I' . strval($length + 7 + 5), $jumlahLulusanLastMonth["statistika"])->setCellValue('J' . strval($length + 7 + 5), $jumlahLulusanLastMonth["ilkom"])->setCellValue('K' . strval($length + 7 + 5), $jumlahLulusanLastMonth["ilmu aktuaria"])->setCellValue('L' . strval($length + 7 + 5), count($yudisium));
        $sheet->setCellValue('A' . strval($length + 7 + 6), "Jumlah yudisium hari ini")->setCellValue('D' . strval($length + 7 + 6), $jumlahLulusanToday["fisika"])->setCellValue('E' . strval($length + 7 + 6), $jumlahLulusanToday["geofisika"])->setCellValue('F' . strval($length + 7 + 6), $jumlahLulusanToday["elins"])->setCellValue('G' . strval($length + 7 + 6), $jumlahLulusanToday["kimia"])->setCellValue('H' . strval($length + 7 + 6), $jumlahLulusanToday["matematika"])->setCellValue('I' . strval($length + 7 + 6), $jumlahLulusanToday["statistika"])->setCellValue('J' . strval($length + 7 + 6), $jumlahLulusanToday["ilkom"])->setCellValue('K' . strval($length + 7 + 6), $jumlahLulusanToday["ilmu aktuaria"])->setCellValue('L' . strval($length + 7 + 6), count($rows));
        $sheet->setCellValue('A' . strval($length + 7 + 7), "Jumlah lulusan termasuk yudisium hari ini")->setCellValue('D' . strval($length + 7 + 7), $jumlahLulusan["fisika"] + $jumlahLulusanLastMonth["fisika"] + $jumlahLulusanToday["fisika"])->setCellValue('E' . strval($length + 7 + 7), $jumlahLulusan["geofisika"] + $jumlahLulusanLastMonth["geofisika"] + $jumlahLulusanToday["geofisika"])->setCellValue('F' . strval($length + 7 + 7), $jumlahLulusan["elins"] + $jumlahLulusanLastMonth["elins"] + $jumlahLulusanToday["elins"])->setCellValue('G' . strval($length + 7 + 7), $jumlahLulusan["kimia"] + $jumlahLulusanLastMonth["kimia"] + $jumlahLulusanToday["kimia"])->setCellValue('H' . strval($length + 7 + 7), $jumlahLulusan["matematika"] + $jumlahLulusanLastMonth["matematika"] + $jumlahLulusanToday["matematika"])->setCellValue('I' . strval($length + 7 + 7), $jumlahLulusan["statistika"] + $jumlahLulusanLastMonth["statistika"] + $jumlahLulusanToday["statistika"])->setCellValue('J' . strval($length + 7 + 7), $jumlahLulusan["ilkom"] + $jumlahLulusanLastMonth["ilkom"] + $jumlahLulusanToday["ilkom"])->setCellValue('K' . strval($length + 7 + 7), $jumlahLulusan["ilmu aktuaria"] + $jumlahLulusanLastMonth["ilmu aktuaria"] + $jumlahLulusanToday["ilmu aktuaria"])->setCellValue('L' . strval($length + 7 + 7), count($rows) + $jumlahLulusan["jumlah"] + count($yudisium) + count($yudisiumAll));
        $sheet->setCellValue('A' . strval($length + 7 + 8), "Rata-rata IPK lulusan Yudisium hari ini")->setCellValue('D' . strval($length + 7 + 8), $jumlahLulusanToday["fisika"] != 0 ? ($jumlahIpkPerProdi["fisika"] / $jumlahLulusanToday["fisika"]) : "")->setCellValue('E' . strval($length + 7 + 8), $jumlahLulusanToday["geofisika"] != 0 ? ($jumlahIpkPerProdi["geofisika"] / $jumlahLulusanToday["geofisika"]) : "")->setCellValue('F' . strval($length + 7 + 8), $jumlahLulusanToday["elins"] != 0 ? ($jumlahIpkPerProdi["elins"] / $jumlahLulusanToday["elins"]) : "")->setCellValue('G' . strval($length + 7 + 8), $jumlahLulusanToday["kimia"] != 0 ? ($jumlahIpkPerProdi["kimia"] / $jumlahLulusanToday["kimia"]) : "")->setCellValue('H' . strval($length + 7 + 8), $jumlahLulusanToday["matematika"] != 0 ? ($jumlahIpkPerProdi["matematika"] / $jumlahLulusanToday["matematika"]) : "")->setCellValue('I' . strval($length + 7 + 8), $jumlahLulusanToday["statistika"] != 0 ? ($jumlahIpkPerProdi["statistika"] / $jumlahLulusanToday["statistika"]) : "")->setCellValue('J' . strval($length + 7 + 8), $jumlahLulusanToday["ilkom"] != 0 ? ($jumlahIpkPerProdi["ilkom"] / $jumlahLulusanToday["ilkom"]) : "")->setCellValue('K' . strval($length + 7 + 8), $jumlahLulusanToday["ilmu aktuaria"] != 0 ? ($jumlahIpkPerProdi["ilmu aktuaria"] / $jumlahLulusanToday["ilmu aktuaria"]) : "")->setCellValue('L' . strval($length + 7 + 8), $jumlahIpk / count($rows));

        $sheet->setCellValue('A' . strval($length + 7 + 10), "M")->setCellValue('B' . strval($length + 7 + 10), ": Memuaskan")->setCellValue('C' . strval($length + 7 + 10), ": 2.76 - 3.00");
        $sheet->setCellValue('A' . strval($length + 7 + 11), "SM")->setCellValue('B' . strval($length + 7 + 11), ": Sangat Memuaskan")->setCellValue('C' . strval($length + 7 + 11), ": 3.01 - 3.50");
        $sheet->setCellValue('A' . strval($length + 7 + 12), "C")->setCellValue('B' . strval($length + 7 + 12), ": cumlaude")->setCellValue('C' . strval($length + 7 + 12), ": 3.51 - 4.00")->setCellValue('C' . strval($length + 7 + 13), "dan masa studi tidak lebih")->setCellValue('D' . strval($length + 7 + 13), "60")->setCellValue('E' . strval($length + 7 + 13), "bulan");

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode("yud-" . $tanggal_yudisium . ".xlsx") . '"');
        $writer->save('php://output');
        exit();
    }
}