<?php

namespace App\Controllers;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile as GoogleDriveFile;
use Google_Http_Batch as GoogleServiceBatch;
use App\Models\PerjanjianModel;
use App\Models\InstansiMitraModel;
use App\Models\PICMitraModel;
use App\Models\PICUGMModel;
use App\Models\DetailNomorSuratModel;
use App\Models\NomorSuratModel;
use App\Models\DepartemenModel;
use Dompdf\Dompdf;
use chillerlan\QRCode\{QRCode, QROptions};
use App\Models\TodoModel;
use App\Models\UserModel;
use App\Models\PegawaiModel;
use App\Models\PerjanjianDokumenModel;
use App\Models\PerjanjianLuaranModel;
use App\Models\PerjanjianMonevModel;
use App\Models\ListPICModel;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\xlsx;
use App\Filters\PerjanjianFilter; //Filter jangan dihapus
use Kreait\Firebase\RemoteConfig\Template;

class Perjanjian extends BaseController
{
    private $pjs;
    private $perjanjian;
    private $options;

    function __construct()
    {
        // $this->filter('myfilter');
        $this->perjanjian = new PerjanjianModel();
        $this->options = new \stdClass();
        $this->options->akademik = array('Penyaluran lulusan', 'Beasiswa', 'Gelar ganda (Dual Degree)', 'Gelar bersama (Joint Degree)', 'Pertukaran mahasiswa', 'Magang', 'Pertukaran dosen', 'Transfer kredit', 'Asistensi mengajar di satuan pendidikan- Kampus Merdeka', 'Kegiatan wirausaha – Kampus Merdeka', 'Magang/praktik kerja – Kampus Merdeka', 'Membangun Desa / KKN tematik – Kampus Merdeka', 'Pertukaran pelajar – Kampus Merdeka', 'Proyek kemanusiaan – Kampus Merdeka', 'Studi/Proyek independen – Kampus Merdeka');
        $this->options->non_gelar = array('Pengembangan kurikulum/ Program bersama', 'Pelatihan dosen dan instruktur', 'Pengembangan pusat penelitian dan pengembangan keilmuan');
        $this->options->penelitian = array('Penerbitan berkala ilmuah', 'Penyelenggaraan seminar/konferensi ilmiah hasil penelitian', 'Pengembangan sistem / produk', 'Penelitian bersama – artikel / jurnal ilmiah', 'Penelitian bersama – paten / kekayaan intelektual', 'Penelitian bersama – prototipe / purwa rupa / teknologi tepat guna', 'Visiting professor', 'Penelitian bersama', 'Penelitian bersama – desain /karya seni / rekayasa social', 'Penelitian bersama – produk rekomendasi kebijakan', 'Penelitian bersama – infrastruktur', 'Penelitian bersama – jumlah mobilitas mahasiswa / dosen / peneliti internasional', 'Penelitian / riset – Kampus Merdeka', 'Sitasi per dosen sesuai ketentuan yang berlaku', 'Pemakalah forum ilmiah dan makalah terpublikasi dalam proceding terindeks buku ajar / teks / modul ber ISBN', 'Keynote speaker / invited dalam temu ilmiah');
        $this->options->pengabdian = array('Publikasi jurnal nasional terakreditasi / internasional bereputasi', 'Sitasi per dosen sesuai ketentuan yang berlaku', 'Artikel di media masa cetak  / elektronik', 'Pemakalah forum ilmiah dan makalah terpublikasi dalam proceding terindeks', 'Buku ajar / teks / modul ber ISBN', 'Keynote speaker / invited dalam temu ilmiah', 'Visiting lecturer pada perguruan tinggi mitra luar negeri', 'PKM – paten / kekayaan intelektual', 'PKM – prototype / kekayaan intelektual', 'PKM – Desain / karya senin / rekayasa social', 'PKM – Produk rekomendasi kebijaksanaan', 'PKM – Infrastruktur', 'PKM – Jumlah mobilitas mahasiswa / dosen / peneliti internasional', 'PKM – Luaran yang mendukung IKU');
        $this->options->lain = array('Sewa asset', 'Sponsor', 'Hibah asset');
        $this->options->umum = array('Pendidikan dan Pengajaran', 'Penelitian dan Pengembangan', 'Pengabdian Kepada Masyarakat');
        $this->options->program_studi = array("S1 FISIKA", "S1 KIMIA", "S1 MATEMATIKA", "S1 ILMU KOMPUTER", "S1 STATISTIKA", "S1 GEOFISIKA", "S1 ELEKTRONIKA DAN INSTRUMENTASI", "MAGISTER FISIKA");
    }
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        // Call the parent initController method
        parent::initController($request, $response, $logger);
        // Check the value of session('gol_pic_mou')
        if (session('gol_pic_mou') == 0) {
            // Redirect to the root URL
            $response->redirect(base_url('home'));
        }
    }



    function getServiceClient()
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=google-service-account.json');
        $client = new GoogleClient();
        $client->setScopes('https://www.googleapis.com/auth/drive');
        $client->useApplicationDefaultCredentials();

        return $client;
    }

    function initGoogleDriveService()
    {
        $googleClient = $this->getServiceClient();
        $googleDrive = new GoogleDrive($googleClient);

        return $googleDrive;
    }

    function getDefaultFolderId()
    {
        $defaultFolderId = "11zavq7XwAC9fedX4TtzXtF6h8tVAppkV";
        return $defaultFolderId;
    }

    function getMoUAccessEmails($departemen_mou)
    {
        $model = (new ListPICModel())->distinct()->select("users.username")
            ->join("users", "list_pic_ugm.id_user_pic = users.id")
            ->where("list_pic_ugm.departemen_ugm = '$departemen_mou' AND (users.jenis_user = 'departemen' OR list_pic_ugm.tipe_pic = 2)")
            ->findAll();

        // dump($model);

        $mou_access_emails = [];
        foreach ($model as $row) {
            array_push($mou_access_emails, $row->username);
        }

        return $mou_access_emails;
    }

    function getDefaultShareDocsAccessEmails($departemen_mou = '')
    {
        $defaultEmails = [
            "dekan.mipa@ugm.ac.id",
            "wd1.mipa@ugm.ac.id",
            "wd2.mipa@ugm.ac.id",
            "wd3.mipa@ugm.ac.id",
            "wd4.mipa@ugm.ac.id",
            "kka.mipa@ugm.ac.id",
            "oia.mipa@ugm.ac.id",
            "setdekan.mipa@ugm.ac.id",
            "sri_retno@ugm.ac.id",
            "pic2fmipatest@gmail.com", //temporary
        ];

        if ($departemen_mou != '') {
            $mou_access_emails = $this->getMoUAccessEmails($departemen_mou);
            $defaultEmails = array_merge($defaultEmails, $mou_access_emails);
        }

        return $defaultEmails;
    }

    function batchExecution($request_data)
    {

        try {
            $googleDrive = $this->initGoogleDriveService();
            $googleDrive->getClient()->setUseBatch(true);

            $batch = $googleDrive->createBatch();

            foreach ($request_data as $request) {
                $batch->add($request);
            }

            $response_data = $batch->execute();
            return $response_data;
        } finally {
            $googleDrive->getClient()->setUseBatch(false);
        }
    }

    function create_folder($folder_name, $parentsId = "")
    {
        $googleDrive = $this->initGoogleDriveService();

        try {
            if ($parentsId == "") {
                $defaultFolderId = $this->getDefaultFolderId();
                $folder = new GoogleDriveFile(
                    array(
                        'name' => "$folder_name",
                        'parents' => array($defaultFolderId),
                        'mimeType' => 'application/vnd.google-apps.folder'
                    )
                );
                $createFolder = $googleDrive->files->create(
                    $folder,
                    array(
                        'fields' => 'id'
                    )
                );
                $folderId = $createFolder['id'];
                return "$folderId";
            } else {
                $folderId = $parentsId;
                $folder = new GoogleDriveFile(
                    array(
                        'name' => "$folder_name",
                        'parents' => array($folderId),
                        'mimeType' => 'application/vnd.google-apps.folder'

                    )
                );
                $createFolder = $googleDrive->files->create(
                    $folder,
                    array(
                        'fields' => 'id'
                    )
                );
                $folderId = $createFolder['id'];
                return "$folderId";
            }
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    function share_drive_access($id_gdrive_dokumen, $target_emails, $access_type, $pesan = '')
    {
        try {

            $access = [
                1 => 'reader',
                2 => 'commenter',
                3 => 'writer',
            ];

            $googleDrive = $this->initGoogleDriveService();

            $googleDrive->getClient()->setUseBatch(true);

            $batch = $googleDrive->createBatch();

            // $target_emails = ["pic2fmipatest@gmail.com"];

            foreach ($target_emails as $target_email) {
                $permission = new GoogleDrive\Permission(
                    array(
                        'type' => 'user',
                        'role' => $access[$access_type],
                        'emailAddress' => $target_email
                    )
                );
                $request = $googleDrive->permissions->create(
                    $id_gdrive_dokumen,
                    $permission,
                    array(
                        'fields' => 'id',
                        'emailMessage' => $pesan == '' ? 'MOHON ABAIKAN (TESTING DOKUMEN KERJASAMA)' : $pesan,
                        'sendNotificationEmail' => 'true',
                    )
                );
                $batch->add($request, 'user-' . $target_email);
            }
            $batch->execute();
        } finally {
            $googleDrive->getClient()->setUseBatch(false);
        }
    }

    function share_access_metadata($id_gdrive_dokumen, $target_emails, $access_type, $pesan = '')
    {
        $metadata = [
            'id_gdrive_dokumen' => $id_gdrive_dokumen,
            'target_emails' => $target_emails,
            'access_type' => $access_type,
            'pesan' => $pesan
        ];

        return $metadata;
    }

    function multi_share_drive_access($share_access_metadatas)
    {
        try {

            $access = [
                1 => 'reader',
                2 => 'commenter',
                3 => 'writer',
            ];

            $googleDrive = $this->initGoogleDriveService();

            $googleDrive->getClient()->setUseBatch(true);

            $batch = $googleDrive->createBatch();

            $itr = 0;
            foreach ($share_access_metadatas as $share) {
                $id_gdrive_dokumen = $share['id_gdrive_dokumen'];
                $pesan = $share['pesan'] ?? '';

                $target_emails = $share['target_emails'];
                // dump($target_emails);
                $target_emails = ["pic1fmipatest@gmail.com"]; //hardcode sementara

                $access_type = $share['access_type'];
                foreach ($target_emails as $target_email) {
                    $permission = new GoogleDrive\Permission(
                        array(
                            'type' => 'user',
                            'role' => $access[$access_type],
                            'emailAddress' => $target_email
                        )
                    );
                    $request = $googleDrive->permissions->create(
                        $id_gdrive_dokumen,
                        $permission,
                        array(
                            'fields' => 'id',
                            'emailMessage' => $pesan == '' ? 'MOHON ABAIKAN (TESTING DOKUMEN KERJASAMA)' : $pesan,
                            'sendNotificationEmail' => 'true',
                        )
                    );
                    $batch->add($request, 'user-' . $target_email . '-' . "$itr");
                    $itr++;
                }
            }

            $batch->execute();
        } finally {
            $googleDrive->getClient()->setUseBatch(false);
        }
    }

    function share_access_to_email()
    {
        try {
            $data = $this->request->getPost();
            $id_gdrive_dokumen = $data['id_gdrive_dokumen'];
            $target_emails = [$data['email-shared']];
            $pesan = $data['pesan-shared'];
            $access_type = $data['access-type'];

            $this->share_drive_access($id_gdrive_dokumen, $target_emails, $access_type, $pesan);

            echo json_encode('success');
        } catch (\Exception $e) {
            echo json_encode('error', $e->getMessage());
        }
    }

    //fungsi upload file perjanjian ke GDrive
    function uploadMoUToDrive($id_mou, $departemen_ugm, $file_mou, $judul_kerjasama, $parentsId = '', $id_revisi_dokumen = 1, $pic_ugm_email = '', $type = 'docx')
    {

        try {
            $googleDrive = $this->initGoogleDriveService();

            if ($parentsId == '') {
                $defaultFolderId = $this->getDefaultFolderId();
                $folder = new GoogleDriveFile(
                    array(
                        'name' => 'Kerjasama-' . $judul_kerjasama . '-' . $id_mou,
                        'parents' => array($defaultFolderId),
                        'mimeType' => 'application/vnd.google-apps.folder'

                    )
                );
                $createFolder = $googleDrive->files->create(
                    $folder,
                    array(
                        'fields' => 'id,createdTime,name,parents,webViewLink'
                    )
                );
                $folderId = "$createFolder->id";
            } else {
                $folderId = "$parentsId";
            }

            $target_emails = [$pic_ugm_email];

            $defaultEmails = $this->getDefaultShareDocsAccessEmails($departemen_ugm);

            $target_emails = array_merge($target_emails, $defaultEmails);


            if ($type == 'pdf') {
                $file_name = 'Perjanjian-' . $judul_kerjasama . "-versi-" . $id_revisi_dokumen;
                $result = $this->uploadPDFToDrive($file_mou, $file_name, $folderId);

                $docs_id = "$result->id";

                $this->share_drive_access($docs_id, $target_emails, 2);

                return $result;
            } else {

                $file_metadata_ori = new GoogleDriveFile(
                    array(
                        'mimeType' => 'application/vnd.google-apps.document',
                    )
                );
                $file_metadata_ori->setName('Dokumen Kerjasama-' . $judul_kerjasama . "-versi-" . $id_revisi_dokumen . "-Original");
                $file_metadata_ori->setParents(array($folderId));


                $file_metadata = new GoogleDriveFile(
                    array(
                        'mimeType' => 'application/vnd.google-apps.document',
                    )
                );
                $file_metadata->setName('Dokumen Kerjasama-' . $judul_kerjasama . "-versi-" . $id_revisi_dokumen);
                $file_metadata->setParents(array($folderId));

                // proses upload file ke Google Drive dg multipart

                $result_ori = $googleDrive->files->create(
                    $file_metadata_ori,
                    array(
                        'data' => file_get_contents($file_mou),
                        'mimeType' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'supportsAllDrives' => true,
                        'uploadType' => 'multipart',
                        'fields' => 'id,createdTime,name,parents,webViewLink',
                    )
                );

                $id_gdrive_dokumen_ori = $result_ori->id;

                if ($id_revisi_dokumen == 'final') {
                } else {
                    $result = $googleDrive->files->create(
                        $file_metadata,
                        array(
                            'data' => file_get_contents($file_mou),
                            'mimeType' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'supportsAllDrives' => true,
                            'uploadType' => 'multipart',
                            'fields' => 'id,createdTime,name,parents,webViewLink',
                        )
                    );

                    $id_gdrive_dokumen = $result->id;
                }

                if ($id_revisi_dokumen == 'final') {
                    $share1 = $this->share_access_metadata($id_gdrive_dokumen_ori, $target_emails, 1);
                    $this->multi_share_drive_access([$share1]);
                } else {
                    $share1 = $this->share_access_metadata($id_gdrive_dokumen_ori, $target_emails, 1);
                    $share2 = $this->share_access_metadata($id_gdrive_dokumen, $target_emails, 2);
                    $this->multi_share_drive_access([$share1, $share2]);
                }

                $data = [
                    'ori' => $result_ori,
                ];

                if ($id_revisi_dokumen == 'final') {
                } else {
                    $data['comment'] = $result;
                }

                return $data;
            }
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }


    function databaseToExcel()
    {
        $data = $this->request->getGet();

        if ($data['excelType'] == 'monev') {
            $smt = $data['smt'];
            if ($smt == "0") {
                $tahun = $data['tahun2'];
            } else {
                $tahun = $data['tahun1'];
            }
            $monev = new PerjanjianMonevModel();
            $query = $monev->select("mou.judul_kerjasama, no_dokumen_ugm, no_dokumen_mitra,nama_mitra, email_mitra, mou_monev.evaluator, mou_monev.status_kegiatan,
            mou_monev.aktifitas_sudah, mou_monev.aktifitas_belum, mou_monev.kendala, mou_monev.solusi,
            mou_monev.perpanjangan, mou_monev.created_at")
                ->join('mou', 'mou.id_mou = mou_monev.id_mou')
                ->join('mou_instansi_mitra', 'mou.id_instansi_mitra = mou_instansi_mitra.id_instansi_mitra')
                ->where("mou_monev.periode = '$tahun' AND mou_monev.semester = '$smt'")
                ->get();
            $result = $query->getResultArray();
            // dump($result);
            foreach ($result as &$row) {
                // dump($row);
                if ($row['status_kegiatan'] !== NULL) {
                    if ($row['status_kegiatan'] == "1") {
                        $row['status_kegiatan'] = "Aktif";
                    } elseif ($row['status_kegiatan'] == "0") {
                        $row['status_kegiatan'] = "Pasif";
                    }
                }
                if ($row['perpanjangan'] !== NULL) {
                    if ($row['perpanjangan'] == "1") {
                        $row['perpanjangan'] = "Iya";
                    } elseif ($row['perpanjangan'] == "0") {
                        $row['perpanjangan'] = "Tidak";
                    }
                }
            }

            unset($row);
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Monitor dan evaluasi');

            // $header = $monev->getHeaderNames();
            $header = $query->getFieldNames();
            // dump($header);
            $headername = [
                'Judul Kerjasama',
                'No Dokumen UGM',
                'No Dokumen Mitra',
                'Nama Mitra',
                'Email Mitra',
                'Evaluator',
                'Status Kegiatan',
                'Aktifitas Sudah',
                'Aktifitas Belum',
                'Kendala',
                'Solusi',
                'Perpanjangan',
                'Tanggal Input'
            ];
            if ($smt == "0") {
                $tahuns = $data['tahun2'];
                $text = ($tahuns - 1) . "/" . $tahuns . " Semester Genap";
            } else {
                $tahuns = $data['tahun1'];
                $text = $tahuns . "/" . ($tahuns + 1) . " Semester Genap";
            }

            $filename = "Monitor dan Evaluasi Kerjasama Tahun Akademik " . $text . ".xlsx";
            foreach ($header as $column) {
                $sheet->getColumnDimensionByColumn(array_search($column, $header))->setWidth(30);
            }
        } elseif ($data['excelType'] == 'kerjasama') {
            $kerjasama = new PerjanjianModel();
            $start_year = $data['start_year'];
            $end_year = $data['end_year'];
            $query = $kerjasama->select("mou.judul_kerjasama,nama_mitra,alamat_mitra, no_dokumen_ugm,
                                        no_dokumen_mitra, program_studi_terlibat, negara, tipe_dokumen,
                                        tanggal_penandatanganan, tanggal_mulai_kerjasama, tanggal_akhir_kerjasama,
                                        pejabat_penandatanganan_ugm, pejabat_penandatanganan_mitra, bidang_kerjasama,
                                        currency, nominal_kerjasama, keterangan_dpi, dpi, url_dokumen, 
                                        nama_pic_ugm, jabatan_pic_ugm,
                                        email_pic_ugm,no_telp_pic_ugm, nama_pic_mitra, jabatan_pic_mitra,
                                        email_pic_mitra, no_telp_pic_mitra, tanggal_pengajuan")
                ->join('mou_pic_ugm', 'mou.id_mou = mou_pic_ugm.id_mou')
                ->join('mou_instansi_mitra', 'mou.id_instansi_mitra = mou_instansi_mitra.id_instansi_mitra')
                ->join('mou_pic_mitra', 'mou.id_mou = mou_pic_mitra.id_mou')
                ->where("mou.status_mou = '3' AND YEAR(tanggal_mulai_kerjasama) >= $start_year AND YEAR(tanggal_akhir_kerjasama) >= $end_year")
                ->get();
            $result = $query->getResultArray();
            $currencyMap = [
                0 => 'Rp',
                1 => '$',
                2 => '€',
                3 => '¥',
            ];
            foreach ($result as &$row) {
                $row['program_studi_terlibat'] = json_decode($row['program_studi_terlibat'], true) ?? [];
                $row['program_studi_terlibat'] = implode(",", $row['program_studi_terlibat']);
                $row['tanggal_penandatanganan'] = date("d-m-Y", strtotime($row['tanggal_penandatanganan']));
                $row['tanggal_mulai_kerjasama'] = date("d-m-Y", strtotime($row['tanggal_mulai_kerjasama']));
                $row['tanggal_akhir_kerjasama'] = date("d-m-Y", strtotime($row['tanggal_akhir_kerjasama']));
                $row['bidang_kerjasama'] = json_decode($row['bidang_kerjasama'], true) ?? [];
                $row['bidang_kerjasama'] = implode(",", $row['bidang_kerjasama']);
                $row['currency'] = isset($currencyMap[$row['currency']]) ? $currencyMap[$row['currency']] : '';
                $row['tanggal_pengajuan'] = date("d-m-Y", strtotime($row['tanggal_pengajuan']));
            }
            unset($row);
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Kerjasama periode ' . $start_year . '-' . $end_year);
            $header = $query->getFieldNames();
            // dump($header);
            $headername = [
                'Judul Kerjasama',
                'Mitra',
                'Alamat Mitra',
                'No Dokumen UGM',
                'No Dokumen Mitra',
                'Program Studi',
                'Negara',
                'Tipe Dokumen',
                'Tanggal Tanda Tangan',
                'Mulai',
                'Selesai',
                'Pejabat Penandatangan UGM',
                'Pejabat Penandatangan Mitra',
                'Bidang Kerjasama',
                'Kurs',
                'Nominal Kerjasama',
                'Keterangan DPI',
                'DPI (%)',
                'URL Dokumen',
                'Nama PIC UGM',
                'Jabatan PIC UGM',
                'Email PIC UGM',
                'No Telp PIC UGM',
                'Nama PIC Mitra',
                'Jabatan PIC Mitra',
                'Email PIC Mitra',
                'No Telp PIC Mitra',
                'Tanggal Pengajuan'
            ];
            $filename = "Data Kerjasama periode " . $start_year . "-" . $end_year . "_FMIPA.xlsx";
            $columnWidths = [];
            $columnName = range('A', 'Z');
            foreach ($columnName as $column) {
                $columnWidths[$column] = 35;
            }
            $columnWidths['G'] = 18;
            $columnWidths['H'] = 18;
            $columnWidths['I'] = 22;
            $columnWidths['J'] = 16;
            $columnWidths['K'] = 16;
            $columnWidths['O'] = 5;
            $columnWidths['P'] = 18;
            $columnWidths['R'] = 8;
            $columnWidths['W'] = 20;
            $columnWidths['AA'] = 20;
            $columnWidths['AB'] = 21;



            foreach ($columnWidths as $column => $width) {
                $sheet->getColumnDimension($column)->setWidth($width);
            }
        }

        $row = 1;
        $col = 1;

        foreach ($headername as $column) {
            $sheet->setCellValueByColumnAndRow($col, $row, $column);
            $col++;
        }
        // $theArray = $monev->getRows();
        // dump($theArray);
        foreach ($result as $datas) {
            $row++;
            $col = 1;

            foreach ($header as $column) {
                $sheet->setCellValueByColumnAndRow($col, $row, $datas[$column]);
                $col++;
            }
        }

        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => ['type' => 'solid', 'startColor' => ['rgb' => 'EEEEEE']]
        ];
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($headerStyle);
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getAlignment()->setHorizontal('center');

        $sheet->getStyle('A1:Z999')
            ->getAlignment()->setWrapText(true);

        // Create a new Xlsx writer object and save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        $writer->save('TempTable.xlsx');
        $tempfile = tmpfile();
        $writer->save(stream_get_meta_data($tempfile)['uri']);
        // Set headers to force download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: 0');
        header('Pragma: public');

        // Output the file contents to the browser
        readfile(stream_get_meta_data($tempfile)['uri']);

        fclose($tempfile);
        unlink("TempTable.xlsx");

        exit;
    }

    function download_pdf_mou($id_gdrive_dokumen)
    {
        $download_data = $this->download_pdf_from_drive($id_gdrive_dokumen);
        $filePath = $download_data['filePath'];
        $folderPath = $download_data['folderPath'];

        if (file_exists($filePath)) {
            $response = $this->response->download($filePath, null);

            $response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->setHeader('Pragma', 'no-cache');
            $response->setHeader('Expires', '0');

            $response->send();

            $this->delete_dokumen($folderPath);

            exit;
            // return $this->response->download($filePath, null);
        } else {
            echo 'The file does not exist.';
        }
    }

    function download($type, $id_gdrive_dokumen)
    {
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);

        $fileid = $id_gdrive_dokumen;
        $googleDrive = $this->initGoogleDriveService();
        // membaca data file di Google Drive berdasarkan ID
        $file = $googleDrive->files->get($fileid);
        // membaca nama file
        $name = $file->getName();
        $folderName = "$id-$name";

        $downloadType = [
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'pdf' => 'application/pdf',
        ];

        $response = $googleDrive->files->export($fileid, $downloadType["$type"], array("alt" => "media"));
        $content = $response->getBody()->getContents();

        mkdir('dokumen_mou/' . $folderName, 0777, true);
        $folderPath = 'dokumen_mou/' . $folderName . '/';
        $filePath = $folderPath . $name . '.' . $type;

        file_put_contents("$filePath", $content);

        $fileName = "$name.$type";

        $download_data = [
            'filePath' => $filePath,
            'folderPath' => $folderPath,
        ];

        $filePath = $download_data['filePath'];
        $folderPath = $download_data['folderPath'];

        if (file_exists($filePath)) {
            $response = $this->response->download($filePath, null);

            $response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->setHeader('Pragma', 'no-cache');
            $response->setHeader('Expires', '0');

            $response->send();

            $this->delete_dokumen($folderPath);

            exit;
        } else {
            echo 'The file does not exist.';
        }
        // membaca content isi file dan menampilkan content
    }



    //Ketika tampilan awal menu surat tugas
    public function index($count = false)
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'perjanjian' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $db->escapeLikeString(htmlspecialchars($data['q'] ?? ''));
        $filter_status = $db->escape(htmlspecialchars($data['filter_status'] ?? ''));
        $filter_jenis = $db->escape(htmlspecialchars($data['filter_jenis'] ?? ''));
        $status = $db->escape(htmlspecialchars($data['status'] ?? ''));
        $sort_column = $db->escape(htmlspecialchars($data['sort_column'] ?? 'created_at'));
        $sort_order = $db->escape(htmlspecialchars($data['sort_order'] ?? 'desc'));

        $jenis_user = session('jenis_user');
        $id = session('id');
        $gol_pic_mou = session('gol_pic_mou');

        if ($gol_pic_mou == 2) {
            $main_query = $this->perjanjian->select('mou.*, mou_pic_ugm.nama_pic_ugm as pic, id_user_pic')
                ->join('mou_pic_ugm', 'mou.id_pic_ugm = mou_pic_ugm.id_pic_ugm')
                // ->where("IF(id_user_pic = $id OR ('$jenis_user' IN('admin')) OR ('$gol_pic_mou' = 2) OR ('$gol_pic_mou' = 1 AND status_mou >= 3) , true, false )")
                ->where("CONCAT(judul_kerjasama, nama_pic_ugm) LIKE '%$q%'")
                ->where("status_mou LIKE '%$filter_status%'")
                ->where("tipe_dokumen LIKE '%$filter_jenis%'")
                ->orderBy("
                      CASE
                          WHEN 
                          mou.tanggal_akhir_kerjasama > NOW() AND mou.tanggal_akhir_kerjasama <= DATE_ADD(NOW(), INTERVAL 6 MONTH) AND DATE_FORMAT(mou.tanggal_akhir_kerjasama, '%Y-%m-%d') <> NULL THEN
                           -99999999 - mou.tanggal_akhir_kerjasama
                          WHEN 
                          mou.tanggal_akhir_kerjasama <= NOW() AND DATE_FORMAT(mou.tanggal_akhir_kerjasama, '%Y-%m-%d') <> NULL AND mou.tanggal_akhir_kerjasama IS NOT NULL THEN  
                              9999999999999999999999999999
                          WHEN
                          DATE_FORMAT(mou.tanggal_akhir_kerjasama, '%Y-%m-%d') <> NULL THEN 
                              999999 + mou.tanggal_akhir_kerjasama
                          WHEN
                            DATE_FORMAT(mou.tanggal_akhir_kerjasama, '%Y-%m-%d') <> NULL AND mou.tanggal_akhir_kerjasama IS NOT NULL THEN
                              mou.tanggal_akhir_kerjasama
                          ELSE 
                              mou.status_mou
                          END");
        } else {
            $id_user_pic = session('id');
            $pic_ugm = (new ListPICModel())->select("list_pic_ugm.*")->where("list_pic_ugm.id_user_pic = $id_user_pic")->paginate(10)[0];
            $is_admin_departemen = $pic_ugm->tipe_pic == 2 ? true : false;
            // echo $id_user_pic;
            $where_clause = "mou_pic_ugm.id_user_pic = '$id_user_pic'";
            $where_clause2 = "mou_pic_ugm.departemen_ugm = '$pic_ugm->departemen_ugm'";
            $where1 = $is_admin_departemen ? $where_clause2 : $where_clause;

            $main_query = $this->perjanjian->select('mou.*, mou_pic_ugm.nama_pic_ugm as pic, mou_pic_ugm.id_user_pic')
                ->join('mou_pic_ugm', "mou.id_mou = mou_pic_ugm.id_mou")
                // ->where("IF(id_user_pic = $id OR ('$jenis_user' IN('admin')) OR ('$gol_pic_mou' = 2) OR ('$gol_pic_mou' = 1 AND status_mou >= 3) , true, false )")
                ->where("$where1")
                ->where("CONCAT(judul_kerjasama, nama_pic_ugm) LIKE '%$q%'")
                ->where("status_mou LIKE '%$filter_status%'")
                ->where("tipe_dokumen LIKE '%$filter_jenis%'")
                ->orderBy("
                     CASE
                         WHEN 
                         mou.tanggal_akhir_kerjasama > NOW() AND mou.tanggal_akhir_kerjasama <= DATE_ADD(NOW(), INTERVAL 6 MONTH) AND DATE_FORMAT(mou.tanggal_akhir_kerjasama, '%Y-%m-%d') <> NULL THEN
                                                      -99999999 - mou.tanggal_akhir_kerjasama
                         WHEN 
                         mou.tanggal_akhir_kerjasama <= NOW() AND DATE_FORMAT(mou.tanggal_akhir_kerjasama, '%Y-%m-%d') <> NULL AND mou.tanggal_akhir_kerjasama IS NOT NULL THEN
                                                      9999999999999999999999999999
                         WHEN
                         DATE_FORMAT(mou.tanggal_akhir_kerjasama, '%Y-%m-%d') <> NULL THEN 
                             999999 + mou.tanggal_akhir_kerjasama
                         WHEN
                         DATE_FORMAT(mou.tanggal_akh
                         ir_kerjasama, '%Y-%m-%d') <> DATE_FORMAT(NULL) AND mou.tanggal_akhir_kerjasama IS NOT NULL THEN                             mou.tanggal_akhir_kerjasama
                         ELSE 
                             mou.status_mou
                         END");
        }

        if ($count) {
            echo $main_query->countAllResults();
            exit;
        }
        $picModel = new ListPICModel();
        $departemen = $picModel->select('departemen_ugm, id_user_pic')->findAll();
        // dump($departemen);
        foreach ($departemen as $row) {
            $anggota_departemen[$row->departemen_ugm][] = $row->id_user_pic;
        }
        $hirarki = [
            15 => $anggota_departemen['Departemen Fisika'],
            22 => $anggota_departemen['Departemen Fisika'],
            16 => $anggota_departemen['Departemen Ilmu Komputer dan Elektronika'],
            19 => $anggota_departemen['Departemen Ilmu Komputer dan Elektronika'],
            17 => $anggota_departemen['Departemen Kimia'],
            20 => $anggota_departemen['Departemen Kimia'],
            18 => $anggota_departemen['Departemen Matematika'],
            21 => $anggota_departemen['Departemen Matematika'],
        ];
        // dump($hirarki);
        if ($gol_pic_mou == '1') {
            $tipe_pic = $picModel->select('tipe_pic, departemen_ugm')->where('id_user_pic', $id)->first();
            if ($tipe_pic->tipe_pic == '2') {
                $hirarki[$id] = $anggota_departemen[$tipe_pic->departemen_ugm];
            }
        }
        $bawahan = [];
        foreach ($hirarki as $key => $value) {
            if ($key == $id) {
                $bawahan = $value;
            }
        }
        $rows = $main_query->paginate(10);
        // $rows = $main_query->paginate(10);
        // $rows = $main_query->getResultArray();

        $data = [
            'rows' => $rows,
            'filter_status' => 0,
            'filter_jenis' => 0,
            'filter_batas' => 0,
            'bawahan' => $bawahan,
            'pager' => $this->perjanjian->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'gol_pic_mou' => $gol_pic_mou,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'filter_status' => $filter_status,
            'filter_jenis' => $filter_jenis,
        ];
        // dump($data);
        return view('perjanjian/index', $data);
    }

    function docsGetComments($docs_id)
    {
        $googleDrive = $this->initGoogleDriveService();
        $results = $googleDrive->comments->listComments(
            $docs_id,
            array(
                'fields' => 'comments(*)'
            )
        );

        return $results;
    }

    function docsCheckComments($docs_id)
    {
        $results = $this->docsGetComments($docs_id);
        $hasComment = false;
        if (count($results['comments'])) {
            foreach ($results['comments'] as $comment) {
                if (!$comment['resolved']) {
                    $hasComment = true; # code...
                    break;
                }
            }
            // return true;
        }
        return $hasComment;
    }



    function reviewsData($id_mou, $count = false)
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'perjanjian' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'status_revisi, created_at';
        $sort_order = $data['sort_order'] ?? 'desc';
        // $sort_column2 = $data['sort_column2'] ?? 'created_at';
        // $sort_order2 = $data['sort_order2'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_pic_mou = session('gol_pic_mou');

        $status_mou = $this->perjanjian->select("mou.status_mou")->where("mou.id_mou = '$id_mou'")->paginate(10)[0]->status_mou;

        $final = new PerjanjianDokumenModel();
        $final->select(('mou_dokumen_revisi.*'))
            ->where("mou_dokumen_revisi.id_mou = '$id_mou' 
            AND (mou_dokumen_revisi.status_revisi = '4' 
            OR mou_dokumen_revisi.status_revisi = '4*')")
            ->orderBy($sort_column, $sort_order)->limit(1);
        $final = $final->paginate(1000000000) ?? '';
        $id_gdrive_dokumen_final = $final[0]->id_gdrive_dokumen_ori ?? '';

        $dokumen = new PerjanjianDokumenModel();

        $dokumen->select(('mou_dokumen_revisi.*'))
            ->where("mou_dokumen_revisi.id_mou = '$id_mou'")
            ->orderBy($sort_column, $sort_order);

        if ($count) {
            exit;
        }

        $rows = $dokumen->paginate(100000000);
        $id_gdrive_dokumen = $rows[0]->id_gdrive_dokumen_ori;
        $data['page'] = $data['page'] ?? '1';

        // foreach ($rows as $row) {
        //     if (in_array("$row->status_revisi", array('1', '0*', '4', '4*'))) {
        //         continue;
        //     } else {
        //         $hasComment = $this->docsCheckComments($row->id_gdrive_dokumen);
        //         if ($hasComment && $row->status_revisi == '0') {
        //             $row->status_revisi = '0**';
        //         } else if ($hasComment) {
        //             $row->status_revisi = '3';
        //         }
        //     }
        // }

        $data = [
            'id_mou' => $id_mou,
            'id_versi_asli' => $id_gdrive_dokumen,
            'id_versi_final' => $id_gdrive_dokumen_final,
            'status_mou' => $status_mou,
            'rows' => $rows,
            'pager' => $dokumen->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'gol_pic_mou' => $gol_pic_mou,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'active' => 2
        ];

        return $data;
    }

    function reviewsInit($id_mou, $count = false)
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'perjanjian' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'status_revisi';
        $sort_order = $data['sort_order'] ?? 'desc';
        $sort_column2 = $data['sort_column2'] ?? 'created_at';
        $sort_order2 = $data['sort_order2'] ?? 'desc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_pic_mou = session('gol_pic_mou');

        $perjanjian = $this->perjanjian->select(('*'))
            ->join('mou_pic_ugm', 'mou_pic_ugm.id_mou = mou.id_mou')
            ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];
        $email_pic_ugm = $perjanjian->email_pic_ugm;
        $nama_pic_ugm = $perjanjian->nama_pic_ugm;
        $departemen_ugm = $perjanjian->departemen_ugm;

        $emails_default = $this->getMoUAccessEmails($departemen_ugm);


        $judul_kerjasama = $perjanjian->judul_kerjasama;
        $status_mou = $perjanjian->status_mou;

        $dokumen = new PerjanjianDokumenModel();
        $dokumen->select(('mou_dokumen_revisi.*'))
            ->where("mou_dokumen_revisi.id_mou = '$id_mou' 
            AND (mou_dokumen_revisi.status_revisi = '0' 
            OR mou_dokumen_revisi.status_revisi = '0*' 
            OR mou_dokumen_revisi.status_revisi = '0**' 
            OR mou_dokumen_revisi.status_revisi = '4*')")
            ->orderBy($sort_column2, $sort_order2)->limit(1);
        $asli = $dokumen->paginate(1000000000);
        $dokumen->select(('mou_dokumen_revisi.*'))
            ->where("mou_dokumen_revisi.id_mou = '$id_mou' 
            AND (mou_dokumen_revisi.status_revisi = '4' 
            OR mou_dokumen_revisi.status_revisi = '4*')")
            ->orderBy($sort_column2, $sort_order2)->limit(1);
        $final = $dokumen->paginate(1000000000) ?? '';

        if ($count) {
            exit;
        }
        $id_gdrive_dokumen_asli = $asli[0]->id_gdrive_dokumen_ori ?? '';
        $id_gdrive_dokumen_final = $final[0]->id_gdrive_dokumen_ori ?? '';

        $data = [
            'id_mou' => $id_mou,
            'id_versi_asli' => $id_gdrive_dokumen_asli,
            'id_versi_final' => $id_gdrive_dokumen_final,
            'email_pic_ugm' => $email_pic_ugm,
            'nama_pic_ugm' => $nama_pic_ugm,
            'departemen_ugm' => $departemen_ugm,
            'emails_default' => $emails_default,
            'status_mou' => $status_mou,
            'judul_kerjasama' => $judul_kerjasama,
            'pager' => $dokumen->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'gol_pic_mou' => $gol_pic_mou,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'active' => 2
        ];

        return $data;
    }

    //still in progress.... untuk halaman review
    public function reviews_get_json($id_mou, $count = false)
    {
        try {
            $data = json_encode($this->reviewsData($id_mou, $count));

            echo $data;
        } catch (\Exception $e) {
            echo json_encode('error');
        }
    }

    public function reviews($id_mou, $count = false)
    {
        $data = $this->reviewsInit($id_mou, $count);

        if ($data['status_mou'] < 1) {
            return redirect()->to(base_url('perjanjian'));
        }

        return view('perjanjian/reviews', $data);
    }

    function upload_revisi2($id_mou, $finalisasi = 0)
    {
        try {
            $data = $this->request->getPost();

            $perjanjian = $this->perjanjian->select(('mou.*,mou_dokumen_revisi.*,mou_pic_ugm.*'))
                ->join('mou_dokumen_revisi', 'mou_dokumen_revisi.id_mou = mou.id_mou')
                ->join('mou_pic_ugm', 'mou_pic_ugm.id_mou = mou.id_mou')
                ->where("mou.id_mou = '$id_mou'")
                ->orderBy('mou_dokumen_revisi.id_revisi_mou', 'desc')
                ->limit(1)->paginate(10)[0];

            $id_gdrive_dokumen = $perjanjian->id_gdrive_dokumen;

            $jumlah_revisi = $perjanjian->id_revisi_mou;
            $id_revisi_dokumen = $jumlah_revisi;
            $id_revisi_dokumen++;

            if ($finalisasi == 1) {
                $id_revisi_dokumen = 'final';
            }

            $revisi_prev_update = [
                'status_revisi' => $jumlah_revisi == 1 ? '0*' : '1',
            ];

            $email_pic_ugm = $perjanjian->email_pic_ugm ?? '';
            $judul_kerjasama = $perjanjian->judul_kerjasama;
            $id_gdrive_folder = $perjanjian->id_gdrive_folder;
            $departemen_ugm = $perjanjian->departemen_ugm;

            if ($finalisasi) {
                if (!empty($this->request->getFile('dokumen-finalisasi-mou')) && !empty($this->request->getFile('dokumen-finalisasi-mou')->getFileName())) {
                    $file = $this->request->getFile('dokumen-finalisasi-mou');
                    $file_metadata = $this->uploadMoUToDrive($id_mou, $departemen_ugm, $file, $judul_kerjasama, $id_gdrive_folder, $id_revisi_dokumen, pic_ugm_email: $email_pic_ugm);
                }
            } else {
                if (!empty($this->request->getFile('dokumen-mou')) && !empty($this->request->getFile('dokumen-mou')->getFileName())) {
                    $file = $this->request->getFile('dokumen-mou');
                    $file_metadata = $this->uploadMoUToDrive($id_mou, $departemen_ugm, $file, $judul_kerjasama, $id_gdrive_folder, $id_revisi_dokumen, pic_ugm_email: $email_pic_ugm);
                }
            }

            $dataDokumen['id_mou'] = $id_mou;
            $dataDokumen['id_revisi_mou'] = $id_revisi_dokumen == 'final' ? -1 : $id_revisi_dokumen;
            $dataDokumen['id_gdrive_dokumen_ori'] = $file_metadata['ori']['id'];
            $dataDokumen['id_gdrive_dokumen'] = $finalisasi ? null : $file_metadata['comment']['id'];
            $dataDokumen['id_gdrive_folder'] = $file_metadata['ori']['parents'][0];
            $dataDokumen['url_view_dokumen_ori'] = $file_metadata['ori']['webViewLink'];
            $dataDokumen['url_view_dokumen'] = $finalisasi ? null : $file_metadata['comment']['webViewLink'];
            $dataDokumen['keterangan'] = $finalisasi ? $data['keterangan-finalisasi'] : $data['keterangan'];
            $dataDokumen['status_revisi'] = $finalisasi ? '4' : '2';
            $dataDokumen['tanggal_revisi'] = date('Y-m-d');

            (new PerjanjianDokumenModel())->update($id_gdrive_dokumen, $revisi_prev_update);
            (new PerjanjianDokumenModel())->insert($dataDokumen);

            if ($finalisasi == 1) {
                $mou_update = [
                    'status_mou' => 2,
                ];

                $this->perjanjian->update($id_mou, $mou_update);
            }

            // dump($dataDokumen);

            echo json_encode('success');
        } catch (\Exception $e) {
            echo json_encode('error', $e->getMessage());
        }
    }

    function finalisasi_review($id_mou, $id_revisi)
    {
        try {

            $dokumen = new PerjanjianDokumenModel();

            $revisi = $dokumen->select(('mou_dokumen_revisi.*'))
                ->where("mou_dokumen_revisi.id_mou = '$id_mou' AND mou_dokumen_revisi.id_revisi_mou = '$id_revisi'")->paginate(10)[0];
            $id_gdrive_dokumen = $revisi->id_gdrive_dokumen;

            $revisi_prev_update = [
                'status_revisi' => in_array($revisi->status_revisi, ["0", "0*", "0**"]) ? '4*' : '4',
            ];
            $mou_update = [
                'status_mou' => 2,
            ];

            (new PerjanjianDokumenModel())->update($id_gdrive_dokumen, $revisi_prev_update);
            $this->perjanjian->update($id_mou, $mou_update);

            echo json_encode('success');
        } catch (\Exception $e) {
            echo json_encode('error');
        }
    }

    //Proses input data surat baru
    public function create()
    {
        $row = new PerjanjianModel();
        $row->created_at = date('Y-m-d');
        $row->program_studi_terlibat = [];
        $row->bidang_kerjasama = [];
        $row->custom_option = [];
        $row->jangka_waktu = [
            'tahun' => "",
            'bulan' => "",
        ];
        $kurs =
            [
                '0' => 'Rp',
                '1' => '$',
                '2' => '€',
                '3' => '¥',
            ];
        $data = [
            'action' => 'store',
            'row' => $row,
            'details' => [],
            'options' => $this->options,
            'kurs' => $kurs,
            // 'nama_penandatangan' => null,
            // 'pjs' => $this->pjs,
            'departemens' => (new DepartemenModel)->join('pegawais', 'pegawais.id = departemen.kepala_pegawai_id')->where('departemen.id', '7')->get()->getResult(),
        ];
        return view('perjanjian/form', $data);
    }

    //Proses menyimpan hasil inputan data baru ke database
    public function store()
    {
        // $id = substr(md5(date('YmdHis')), 0, 6);
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $data = $this->request->getPost();
        $data['id_mou'] = $id;
        $departemen_ugm = $data['departemen_ugm'];
        $data['id_user_pic'] = $data['id_user_pic'] ?? session('id');
        //if (isset($data['no_surat'])) $data['no_surat'] = $this->create_no_surat(base64_encode($data['no_surat']));
        $data['jangka_waktu'] = [
            'tahun' => $data['tahun'] != "" ? $data['tahun'] : "0",
            'bulan' => $data['bulan'] != "" ? $data['bulan'] : "0",
        ];
        $data['jangka_waktu'] = json_encode($data['jangka_waktu']);
        $data['id_instansi_mitra'] = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $data['program_studi_terlibat'] = json_encode($data['program_studi_terlibat'] ?? []);
        $data['bidang_kerjasama'] = json_encode($data['bidang_kerjasama'] ?? []);
        // $data['tanggal_pengajuan'] = json_encode($data['tanggal_pengajuan'] ?? []);
        $data['id_pic_mitra'] = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $data['id_pic_ugm'] = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);

        // dump($data);

        if ($data['status'] == 1) {

            $data['status_mou'] = 1;
            $judul_kerjasama = $data['judul_kerjasama'];
            if (!empty($this->request->getFile('dokumen-mou')) && !empty($this->request->getFile('dokumen-mou')->getFileName())) {
                $file = $this->request->getFile('dokumen-mou');
                $file_metadata = $this->uploadMoUToDrive($id, $departemen_ugm, $file, $judul_kerjasama, pic_ugm_email: $data['email_pic_ugm'] ?? '');
                // $file_metadata = $this->perjanjianoreMoUToDrive($id, $file, $judul_kerjasama, pic_ugm_email: $data['email_pic_ugm'] ?? '');
            }

            $dataDokumen['id_mou'] = $id;
            $dataDokumen['id_revisi_mou'] = 1;
            $dataDokumen['id_gdrive_dokumen_ori'] = $file_metadata['ori']['id'];
            $dataDokumen['id_gdrive_dokumen'] = $file_metadata['comment']['id'];
            $dataDokumen['id_gdrive_folder'] = $file_metadata['ori']['parents'][0];
            $dataDokumen['url_view_dokumen_ori'] = $file_metadata['ori']['webViewLink'];
            $dataDokumen['url_view_dokumen'] = $file_metadata['comment']['webViewLink'];
            $dataDokumen['keterangan'] = "Dokumen pengajuan awal kerjasama $judul_kerjasama.";
            $dataDokumen['status_revisi'] = 0;
            $dataDokumen['tanggal_revisi'] = date('Y-m-d');

            (new PerjanjianDokumenModel())->insert($dataDokumen);
        }


        $this->perjanjian->insert($data);
        (new InstansiMitraModel())->insert($data);
        (new PICMitraModel())->insert($data);
        (new PICUGMModel())->insert($data);

        $db = \Config\Database::connect();
        $db->query("INSERT INTO notifications
            select null, id, 'perjanjian', 'surat', '0', now(), now() from users 
            where gol_pic_mou IN('2')
        ");

        // $this->topdf($id, true);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        // if ($data['status'] == 'preview') session()->setFlashdata('preview', $id);
        return $this->response->redirect(site_url('perjanjian'));
    }

    //Proses edit data surat baru
    public function edit($id)
    {
        $row = (new PerjanjianModel)
            ->join('mou_instansi_mitra', 'mou_instansi_mitra.id_instansi_mitra = mou.id_instansi_mitra')
            ->join('mou_pic_mitra', 'mou_pic_mitra.id_mou = mou.id_mou')
            ->join('mou_pic_ugm', 'mou_pic_ugm.id_mou = mou.id_mou')
            ->where('mou.id_mou', $id)
            ->first();
        $row->program_studi_terlibat = json_decode($row->program_studi_terlibat, true) ?? [];
        $row->bidang_kerjasama = json_decode($row->bidang_kerjasama, true) ?? [];
        $row->jangka_waktu = json_decode($row->jangka_waktu, true) ?? [];
        // dump($row);
        $kurs =
            [
                '0' => 'Rp',
                '1' => '$',
                '2' => '€',
                '3' => '¥',
            ];
        $row->custom_option = [];
        $selectedOptions = $row->bidang_kerjasama;
        foreach ($this->options as $optionArray) {
            // Iterate over each option in the current array
            foreach ($optionArray as $option) {
                // Check if the selected option exists in the current option array
                if (in_array($option, $selectedOptions)) {
                    // If the selected option is found, remove it from the selected options array
                    $selectedOptions = array_diff($selectedOptions, [$option]);
                }
            }
        }

        // The remaining options in $selectedOptions are the custom options
        $row->custom_option = $selectedOptions;
        $data = [
            'action' => 'update',
            'row' => $row,
            'options' => $this->options,
            'kurs' => $kurs,
            // 'nama_penandatangan' => $nama_penandatangan,
            // 'tembusans' => json_decode($row->tembusan),
            'pjs' => $this->pjs,
        ];
        // dump($data);
        return view('perjanjian/form', $data);
    }

    public function picsearch()
    {
        $model = new ListPICModel();
        if (empty($this->request->getGet('users'))) {
            $dosens = $model->select('users.id, users.username as email, users.nama as jabatan, id_user_pic ,id_list_pic, list_pic_ugm.nama_ugm, departemen_ugm, alamat_ugm, no_telp_ugm')
                ->join('users', 'list_pic_ugm.id_user_pic = users.id')
                ->like('nama_ugm', $this->request->getGet('term'))
                ->orLike('username', $this->request->getGet('term'))
                ->orLike('jabatan', $this->request->getGet('term'))
                ->findAll(3);
            $data = $dosens;
            return $this->response->setJSON($data);
        } else {
            $dosens = $model->like('nama', $this->request->getGet('term'))->where('jenis_user', $this->request->getGet('users'))->findAll();
            ;

            $data = [];
            foreach ($dosens as $dosen) {
                $data[] = [
                    'id' => $dosen->id,
                    'value' => $dosen->nama,
                    'label' => $dosen->nama
                ];
            }
            return $this->response->setJSON($data);
        }
    }

    public function mitrasearch()
    {
        $model = new InstansiMitraModel();
        if (empty($this->request->getGet('users'))) {
            $mitra = $model
                ->distinct()
                ->select(['nama_mitra', 'email_mitra', 'alamat_mitra', 'no_telp_mitra'])
                ->like('nama_mitra', $this->request->getGet('term'))
                ->orLike('email_mitra', $this->request->getGet('term'))->findAll(3);
            $data = $mitra;
            return $this->response->setJSON($data);
        } else {
            $dosens = $model->like('nama_mitra', $this->request->getGet('term'))->where('jenis_user', $this->request->getGet('users'))->findAll();
            ;

            $data = [];
            foreach ($dosens as $dosen) {
                $data[] = [
                    'id' => $dosen->id,
                    'value' => $dosen->nama,
                    'label' => $dosen->nama
                ];
            }
            return $this->response->setJSON($data);
        }
    }



    public function details($id_mou, $getDataOnly = false)
    {
        $model = (new PerjanjianModel);
        $row = $model->select('*')
            ->join('mou_instansi_mitra', 'mou_instansi_mitra.id_instansi_mitra = mou.id_instansi_mitra')
            ->join('mou_pic_mitra', 'mou_pic_mitra.id_mou = mou.id_mou')
            ->join('mou_pic_ugm', 'mou_pic_ugm.id_mou = mou.id_mou')
            ->where('mou.id_mou', $id_mou)->first();
        // dump($row);
        $row->bidang_kerjasama = json_decode($row->bidang_kerjasama) ?? [];
        // $row->bidang_kerjasama = implode (", ", $row->bidang_kerjasama);
        $row->program_studi_terlibat = json_decode($row->program_studi_terlibat) ?? [];
        // $row->program_studi_terlibat = implode(", ", $row->program_studi_terlibat);

        $db = \Config\Database::connect();

        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_pic_mou = session('gol_pic_mou');

        $perjanjian = $this->perjanjian->select(('mou.*'))
            ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];
        $judul_kerjasama = $perjanjian->judul_kerjasama;

        $status_mou = $this->perjanjian->select("mou.status_mou")->where("mou.id_mou = '$id_mou'")->paginate(10)[0]->status_mou;

        $periode_kerjasama = json_decode($perjanjian->jangka_waktu, true) ?? [];
        $tahun_durasi = (int) $periode_kerjasama['tahun'];
        $bulan_durasi = (int) $periode_kerjasama['bulan'];
        $tidak_dibatasi = $tahun_durasi == 0 && $bulan_durasi == 0 ? true : false;

        $data = [
            'id_mou' => $id_mou,
            'row' => $row,
            'judul_kerjasama' => $judul_kerjasama,
            'status_mou' => $status_mou,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'gol_pic_mou' => $gol_pic_mou,
            'status' => $status,
            'active' => 1
        ];

        if ($status_mou < 3) {
            $data['periode_kerjasama'] = "Belum dimulai";
            if ($tidak_dibatasi) {
                $data['durasi_kerjasama'] = "Durasi: Tidak dibatasi";
            } else {
                $data['durasi_kerjasama'] = "Durasi: " . ($tahun_durasi ? "$tahun_durasi tahun" : "") . " " . ($bulan_durasi ? "$bulan_durasi bulan" : "");
            }
        }

        // $nama_penandatangan = $db->query("SELECT nama_publikasi nama FROM pegawais WHERE id=$row->penandatangan_pegawai_id")->getResult()[0]->nama;


        if ($getDataOnly) {
            return $data;
        } else {
            return view('perjanjian/details', $data);
        }
    }

    public function details_get_json($id)
    {
        $data = json_encode($this->details($id, true));

        echo $data;
    }

    function upload_mou_ditandatangani($id_mou)
    {
        try {
            $data = $this->request->getPost();

            $dokumen = new PerjanjianDokumenModel();
            $dokumen = $dokumen->select(('mou_dokumen_revisi.id_gdrive_folder'))
                ->where("mou_dokumen_revisi.id_mou = '$id_mou'")->limit(1)->paginate(10)[0];
            $id_gdrive_folder = $dokumen->id_gdrive_folder;

            $perjanjian = $this->perjanjian->select(('mou.*'))
                ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];
            $judul_kerjasama = $perjanjian->judul_kerjasama;

            $pic_ugm = new PICUGMModel();

            $pic_ugm = $pic_ugm->select(('mou_pic_ugm.*'))
                ->where("mou_pic_ugm.id_mou = '$id_mou'")->paginate(10)[0];
            $email_pic_ugm = $pic_ugm->email_pic_ugm ?? '';

            $periode_kerjasama = json_decode($perjanjian->jangka_waktu, true) ?? [];
            $tahun_durasi = (int) $periode_kerjasama['tahun'];
            $bulan_durasi = (int) $periode_kerjasama['bulan'];
            $tidak_dibatasi = $tahun_durasi == 0 && $bulan_durasi == 0 ? true : false;

            // $tanggal_mulai = $perjanjian->tanggal_penandatanganan ?? '';
            if ($tidak_dibatasi) {
                $tanggal_selesai = "0000-00-00";
            } else {
                $months_to_add = $bulan_durasi + ($tahun_durasi * 12);
                $tanggal_selesai = date('Y-m-d', strtotime("+$months_to_add months"));
            }

            $target_emails = array($email_pic_ugm);
            if (!empty($this->request->getFile('dokumen-mou')) && !empty($this->request->getFile('dokumen-mou')->getFileName())) {
                $file = $this->request->getFile('dokumen-mou');
                $filename = $judul_kerjasama . "_Kerjasama UGM_Ditandatangani";
                $file_metadata = $this->uploadPDFtoDrive($file, $filename, $id_gdrive_folder, pic_ugm_email: $target_emails);
            }

            if (!empty($this->request->getFile('dokumen-mou-dpi')) && !empty($this->request->getFile('dokumen-mou-dpi')->getFileName())) {
                $file_dpi = $this->request->getFile('dokumen-mou-dpi');
                $filename_dpi = $judul_kerjasama . "_SURAT PERNYATAAN KETUA PELAKSANA-TARIF DPI_UGM";
                $file_metadata_dpi = $this->uploadPDFtoDrive($file_dpi, $filename_dpi, $id_gdrive_folder);
            }

            $data_update = [
                "no_dokumen_ugm" => $data['no_dokumen_ugm'],
                "no_dokumen_mitra" => $data['no_dokumen_mitra'],
                "tanggal_penandatanganan" => $data['tanggal_penandatanganan'],
                "tanggal_mulai_kerjasama" => $data['tanggal_penandatanganan'],
                "id_gdrive_dokumen" => $file_metadata['id'],
                "id_dpi_dokumen" => $file_metadata_dpi['id'],
                "url_dokumen" => $file_metadata['webContentLink'],
                "status_mou" => 3,
            ];

            if ($perjanjian->tanggal_akhir_kerjasama == null) {
                $data_update['tanggal_akhir_kerjasama'] = $tanggal_selesai;
            }



            $this->perjanjian->update($id_mou, $data_update);

            echo json_encode('success');
        } catch (\Exception $e) {
            echo json_encode('error');
        }
    }

    public function dpi($id_mou, $template = 0)
    {

        $model = (new PerjanjianModel);

        $perjanjian = $model->select(('mou.*'))
            ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];

        $id_dpi_dokumen = $perjanjian->id_dpi_dokumen ?? '';
        if ($id_dpi_dokumen == '' || $template) {
            $judul_kerjasama = $perjanjian->judul_kerjasama;

            $dpi_path = "dokumen_mou_dpi/template_dpi.docx";

            $download_data = [
                'is_uploaded' => false,
                'filePath' => $dpi_path,
                'judul_kerjasama' => $judul_kerjasama,
            ];

            return $download_data;
        } else {
            $download_data = [
                'is_uploaded' => true,
                'id_dpi_dokumen' => $id_dpi_dokumen,
            ];

            return $download_data;
        }
    }

    public function download_dpi($id_mou, $template = 0)
    {

        $download_data = $this->dpi($id_mou, $template);

        if ($download_data['is_uploaded'] && !$template) {
            $d_data = $this->download_pdf_from_drive($download_data['id_dpi_dokumen']);

            $filePath = $d_data['filePath'];
            $folderPath = $d_data['folderPath'];

            if (file_exists($filePath)) {
                $response = $this->response->download($filePath, null);

                $response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->setHeader('Pragma', 'no-cache');
                $response->setHeader('Expires', '0');

                $response->send();

                $this->delete_dokumen($folderPath);

                exit;
                // return $this->response->download($filePath, null);
            } else {
                echo 'The file does not exist.';
            }
        } else {


            $filePath = $download_data['filePath'];

            // $folderPath = $download_data['folderPath'];

            if (file_exists($filePath)) {
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($filePath);
                $kerjasama = new PerjanjianModel();
                $query = $kerjasama->select("mou.judul_kerjasama,nama_mitra,alamat_mitra, no_dokumen_ugm,
                                        no_dokumen_mitra, program_studi_terlibat, tipe_dokumen,
                                        pejabat_penandatanganan_ugm, pejabat_penandatanganan_mitra, bidang_kerjasama,
                                        currency, nominal_kerjasama, no_telp_mitra, email_mitra, jangka_waktu,
                                        nama_pic_ugm, jabatan_pic_ugm, mou_pic_ugm.departemen_ugm,
                                        email_pic_ugm,no_telp_pic_ugm, nama_pic_mitra, jabatan_pic_mitra,
                                        email_pic_mitra, no_telp_pic_mitra")
                    ->join('mou_pic_ugm', 'mou.id_mou = mou_pic_ugm.id_mou')
                    ->join('mou_instansi_mitra', 'mou.id_instansi_mitra = mou_instansi_mitra.id_instansi_mitra')
                    ->join('mou_pic_mitra', 'mou.id_mou = mou_pic_mitra.id_mou')
                    ->where("mou.id_mou = '$id_mou'")
                    ->first();
                // dump($query);
                // $templateProcessor->setMacroChars('<<', '>>');
                $query->bidang_kerjasama = implode(", ", json_decode($query->bidang_kerjasama) ?? []);
                $kurs =
                    [
                        '0' => 'Rp',
                        '1' => '$',
                        '2' => '€',
                        '3' => '¥',
                    ];
                $query->currency = $kurs[$query->currency];
                $query->jangka_waktu = json_decode($query->jangka_waktu, true) ?? [];
                $query->jangka_waktu = ($query->jangka_waktu['tahun'] ? $query->jangka_waktu['tahun'] . " tahun" : "") . " " . ($query->jangka_waktu['bulan'] ? $query->jangka_waktu['bulan'] . " bulan" : "");
                $templateProcessor->setValues(
                    [
                        'nama_pic_ugm' => $query->nama_pic_ugm,
                        'jabatan_pic_ugm' => $query->jabatan_pic_ugm,
                        'email_pic_ugm' => $query->email_pic_ugm,
                        'no_telp_pic_ugm' => $query->no_telp_pic_ugm,
                        'nama_pic_mitra' => $query->nama_pic_mitra,
                        'jabatan_pic_mitra' => $query->jabatan_pic_mitra,
                        'email_pic_mitra' => $query->email_pic_mitra,
                        'no_telp_pic_mitra' => $query->no_telp_pic_mitra,
                        'judul_kerjasama' => $query->judul_kerjasama,
                        'nama_mitra' => $query->nama_mitra,
                        'alamat_mitra' => $query->alamat_mitra,
                        'no_dokumen_ugm' => $query->no_dokumen_ugm,
                        'no_dokumen_mitra' => $query->no_dokumen_mitra,
                        'tipe_dokumen' => $query->tipe_dokumen,
                        'pejabat_penandatanganan_ugm' => $query->pejabat_penandatanganan_ugm,
                        'nama_penandatanganan_mitra' => $query->pejabat_penandatanganan_mitra,
                        'bidang_kerjasama' => $query->bidang_kerjasama,
                        'currency' => $query->currency,
                        'nominal_kerjasama' => $query->nominal_kerjasama,
                        'departemen_ugm' => $query->departemen_ugm,
                        'no_telp_mitra' => $query->no_telp_mitra,
                        'email_mitra' => $query->email_mitra,
                        'jangka_waktu' => $query->jangka_waktu,
                    ]
                );


                $tempFile = tempnam(sys_get_temp_dir(), 'phpword_');
                $templateProcessor->saveAs($tempFile);

                // $response = $this->response->download($filePath, null);


                $filename = $download_data['judul_kerjasama'] . '_SURAT PERNYATAAN KETUA PELAKSANA-TARIF DPI_UGM.docx';

                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                header('Expires: 0');
                header('Pragma: public');
                readFile($tempFile);
                // $response->send();

                // $this->delete_dokumen($folderPath);

                exit;
                // return $this->response->download($filePath, null);
            } else {
                echo 'The file does not exist.';
            }
        }
    }

    public function luaranData($id_mou)
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'perjanjian' AND user_id = '" . session('id') . "'");

        $data = $this->request->getGet();
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'asc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_pic_mou = session('gol_pic_mou');

        $perjanjian = $this->perjanjian->select(('mou.*'))
            ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];
        $judul_kerjasama = $perjanjian->judul_kerjasama;

        $status_mou = $this->perjanjian->select("mou.status_mou")->where("mou.id_mou = '$id_mou'")->paginate(10)[0]->status_mou;

        $luaran = new PerjanjianLuaranModel();

        $luaran->select('mou_luaran.*')
            ->where("mou_luaran.id_mou = '$id_mou' AND mou_luaran.deleted = '0'")
            ->orderBy($sort_column, $sort_order);

        $rows = $luaran->paginate(100000000);
        $data = [
            'id_mou' => $id_mou,
            'judul_kerjasama' => $judul_kerjasama,
            'rows' => $rows,
            'status_mou' => $status_mou,
            'pager' => $luaran->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'gol_pic_mou' => $gol_pic_mou,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'active' => 3
        ];

        return $data;
    }

    public function luaran($id_mou)
    {
        $data = $this->luaranData($id_mou);

        if ($data['status_mou'] < 3) {
            return redirect()->to(base_url('perjanjian'));
        }

        return view('perjanjian/luaran', $data);
    }
    public function luaran_get_json($id_mou)
    {
        try {
            $data = json_encode($this->luaranData($id_mou));
            echo $data;
        } catch (\Exception $e) {
            echo json_encode('error');
        }
    }

    public function uploadPDFtoDrive($file_pdf, $file_name, $parentsId = '', $pic_ugm_email = [])
    {
        try {
            $googleDrive = $this->initGoogleDriveService();

            if ($parentsId == '') {

                $defaultFolderId = $this->getDefaultFolderId();
                $folderId = $defaultFolderId;
            } else {
                $folderId = $parentsId;
            }

            $file = new GoogleDriveFile();
            $file->setName($file_name . ".pdf");
            $file->setParents(array($folderId));
            $result = $googleDrive->files->create(
                $file,
                array(
                    'data' => file_get_contents($file_pdf),
                    'mimeType' => 'application/octet-stream',
                    'uploadType' => 'multipart',
                    'fields' => 'id,createdTime,name,parents,webViewLink',
                )
            );

            if (count($pic_ugm_email) > 0) {
                $this->share_drive_access($result->id, $pic_ugm_email, 1);
            }

            return $result;
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }

    public function download_pdf_from_drive($id_gdrive_dokumen)
    {
        $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);
        $fileid = $id_gdrive_dokumen;
        $googleDrive = $this->initGoogleDriveService();
        // membaca data file di Google Drive berdasarkan ID
        $file = $googleDrive->files->get($fileid);
        // membaca nama file
        $name = $file->getName();
        $folderName = "$id-" . $name;

        $response = $googleDrive->files->get($fileid, array("alt" => "media"));
        $content = $response->getBody()->getContents();

        mkdir('dokumen_mou/' . $folderName, 0777, true);
        $folderPath = 'dokumen_mou/' . $folderName . '/';
        $filePath = $folderPath . $name;

        file_put_contents("$filePath", $content);

        $fileName = "$name";

        $download_data = [
            'filePath' => $filePath,
            'fileName' => $file->getName(),
            'folderPath' => $folderPath,
        ];

        if (file_exists($filePath)) {
            return $download_data;
        } else {
            return 'The file does not exist.';
        }
    }

    function delete_dokumen($file_path)
    {
        $filePath = $file_path;
        helper('filesystem');
        delete_files($filePath);
        rmdir($file_path);
    }

    public function download_luaran($id_luaran)
    {
        $download_data = $this->download_pdf_from_drive($id_luaran);
        $filePath = $download_data['filePath'];
        $folderPath = $download_data['folderPath'];

        if (file_exists($filePath)) {
            $response = $this->response->download($filePath, null);

            $response->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->setHeader('Pragma', 'no-cache');
            $response->setHeader('Expires', '0');

            $response->send();

            $this->delete_dokumen($folderPath);

            exit;
            // return $this->response->download($filePath, null);
        } else {
            echo 'The file does not exist.';
        }
    }

    public function preview_luaran($id_luaran)
    {
        $filePath = $this->download_pdf_from_drive($id_luaran);
        if (file_exists($filePath)) {
            return ('');
        } else {
            echo 'The file does not exist.';
        }
    }

    public function upload_luaran($id_mou)
    {
        try {
            $data = $this->request->getPost();

            $nama_luaran = $data['nama-luaran'] ?? '(unnamed)';

            $perjanjian = $this->perjanjian->select(('mou.*'))
                ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];
            $judul_kerjasama = $perjanjian->judul_kerjasama;

            $dokumen = new PerjanjianDokumenModel();
            $dokumen = $dokumen->select(('mou_dokumen_revisi.id_gdrive_folder'))
                ->where("mou_dokumen_revisi.id_mou = '$id_mou'")->limit(1)->paginate(10)[0];
            $mou_gdrive_folder = $dokumen->id_gdrive_folder;

            $luaran = new PerjanjianLuaranModel();
            $luaran = $luaran->select(('mou_luaran.id_gdrive_folder'))
                ->where("mou_luaran.id_mou = '$id_mou'")->limit(1);
            $luaran_count = $luaran->countAllResults();

            $luaran_gdrive_folder = $luaran_count == 0 ? '' : $luaran->paginate(10)[0]->id_gdrive_folder;

            if (!empty($this->request->getFile('dokumen-luaran')) && !empty($this->request->getFile('dokumen-luaran')->getFileName())) {
                $file = $this->request->getFile('dokumen-luaran');
                $file_name = "Luaran-" . "$judul_kerjasama" . "-$nama_luaran";

                if ($luaran_gdrive_folder == '') {
                    $luaran_gdrive_folder = $this->create_folder("Luaran", $mou_gdrive_folder);
                    $file_metadata = $this->uploadPDFToDrive($file, $file_name, $luaran_gdrive_folder);
                } else {
                    $file_metadata = $this->uploadPDFToDrive($file, $file_name, $luaran_gdrive_folder);
                }
            }

            $data_luaran = [
                'id_luaran' => $file_metadata['id'],
                'id_mou' => $id_mou,
                'id_gdrive_folder' => $luaran_gdrive_folder,
                'nama_luaran' => $nama_luaran,
                'deskripsi_luaran' => $data['deskripsi'],
                'bentuk_kegiatan' => $data['bentuk-kegiatan'],
                'jumlah_luaran' => $data['jumlah-luaran'],
                'satuan' => $data['satuan'],
            ];

            (new PerjanjianLuaranModel())->insert($data_luaran);

            echo json_encode('success');
        } catch (\Exception $e) {
            echo json_encode("error");
        }
    }

    public function delete_luaran($id_mou, $id_luaran)
    {

        try {
            $luaran = new PerjanjianLuaranModel();
            $delete = [
                'deleted' => 1
            ];
            $luaran->update($id_luaran, $delete);

            // return $this->response->redirect(site_url('perjanjian/luaran/' . $id_mou));
            echo json_encode("delete-success");
        } catch (\Exception $e) {
            echo json_encode('error');
        }
    }

    //SECTION MONEV

    public function monev_list_semester($id_mou)
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'perjanjian' AND user_id = '" . session('id') . "'");

        $perjanjian = $this->perjanjian->select(('mou.*'))
            ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];
        $tanggal_mulai = $perjanjian->tanggal_penandatanganan;

        $tahun_mulai = (int) explode('-', $tanggal_mulai)[0];
        $bulan_mulai = (int) explode('-', $tanggal_mulai)[1];

        // $current_year = 2025;
        $current_year = (int) date('Y');
        // $current_month = 6;
        $current_month = (int) date('m');

        $list_semester = [];

        for ($i = $tahun_mulai; $i <= $current_year; $i++) {
            if ($bulan_mulai < 7 && $i == $tahun_mulai) {
                $semester = [
                    'tahun' => $i,
                    'semester' => 0
                ];
                array_push($list_semester, $semester);
                $semester = [
                    'tahun' => $i,
                    'semester' => 1
                ];
                array_push($list_semester, $semester);
            } else if ($i == $tahun_mulai) {
                $semester = [
                    'tahun' => $i,
                    'semester' => 1
                ];
                array_push($list_semester, $semester);
            }

            if ($i != $tahun_mulai && $i != $current_year) {
                $semester = [
                    'tahun' => $i,
                    'semester' => 0
                ];
                array_push($list_semester, $semester);
                $semester = [
                    'tahun' => $i,
                    'semester' => 1
                ];
                array_push($list_semester, $semester);
            }

            if ($tahun_mulai != $current_year) {
                if ($current_month > 6 && $i == $current_year) {
                    $semester = [
                        'tahun' => $i,
                        'semester' => 0
                    ];
                    array_push($list_semester, $semester);
                    $semester = [
                        'tahun' => $i,
                        'semester' => 1
                    ];
                    array_push($list_semester, $semester);
                } else if ($i == $current_year) {
                    $semester = [
                        'tahun' => $i,
                        'semester' => 0
                    ];
                    array_push($list_semester, $semester);
                }
            }
        }

        echo json_encode($list_semester);
    }

    public function monev_list_tahun($id_mou)
    {

        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'perjanjian' AND user_id = '" . session('id') . "'");

        $perjanjian = $this->perjanjian->select(('mou.*'))
            ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];
        $tanggal_mulai = $perjanjian->tanggal_penandatanganan;

        $tahun_sudah_dimonev = (new PerjanjianMonevModel)->select(('mou_monev.*'))
            ->where("mou_monev.id_mou = '$id_mou'")->orderBy('periode, semester', 'asc')->paginate(10000000);


        $temp_thn = '';
        $temp_smt = -1;
        $list_tahun_sudah = [];

        foreach ($tahun_sudah_dimonev as $monev) {
            array_push(
                $list_tahun_sudah,
                [
                    'tahun' => $monev->periode,
                    'semester' => $monev->semester
                ]
            );
        }

        $tahun_mulai = (int) explode('-', $tanggal_mulai)[0];

        // $current_year = (int) date('Y');
        $current_year = 2030;

        $list_tahun = [];

        // }
        for ($i = $tahun_mulai; $i <= $current_year; $i++) {

            if (in_array($i, $list_tahun_sudah)) {
                continue;
            } else {
                $tahun_akademik = [
                    'tahun' => $i,
                    'semester' => 0
                ];
                array_push($list_tahun, $tahun_akademik);
            }
        }

        echo json_encode($list_tahun);
    }


    public function monevData($id_mou)
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE notifications SET status = 1 WHERE notification_type = 'perjanjian' AND user_id = '" . session('id') . "'");

        $data = $this->request->getPost();
        // $periode_selected = $data['periode_selected'] ?? '';
        // $smt_selected = $data['smt_selected'] ?? '';
        $q = $data['q'] ?? '';
        $status = $data['status'] ?? '';
        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'asc';

        $jenis_user = session('jenis_user');
        $id = session('id');
        $pegawai_id = session('pegawai_id');
        $gol_pic_mou = session('gol_pic_mou');

        $sort_column = $data['sort_column'] ?? 'created_at';
        $sort_order = $data['sort_order'] ?? 'desc';

        $monev = new PerjanjianMonevModel();

        $monev = $monev->select('mou_monev.*')
            ->where("mou_monev.id_mou = '$id_mou' AND mou_monev.deleted_at IS NULL")
            ->orderBy('periode, semester', 'desc');


        $rows = $monev->paginate();

        $perjanjian = $this->perjanjian->select(('mou.*'))
            ->where("mou.id_mou = '$id_mou'")->paginate(10)[0];
        $judul_kerjasama = $perjanjian->judul_kerjasama;

        $status_mou = $this->perjanjian->select("mou.status_mou")->where("mou.id_mou = '$id_mou'")->paginate(10)[0]->status_mou;

        $data = [
            'id_mou' => $id_mou,
            'rows' => $rows,
            'judul_kerjasama' => $judul_kerjasama,
            'status_mou' => $status_mou,
            'pager' => $monev->pager,
            'jenis_user' => $jenis_user,
            'user_id' => $id,
            'pegawai_id' => $pegawai_id,
            'gol_pic_mou' => $gol_pic_mou,
            'q' => $q,
            'status' => $status,
            'sort_column' => $sort_column,
            'sort_order' => $sort_order,
            'active' => 4
        ];

        return $data;
    }

    public function monev_get_json($id_mou)
    {
        try {
            $data = json_encode($this->monevData($id_mou));
            echo $data;
        } catch (\Exception $e) {
            echo json_encode('error');
        }
    }


    public function monev($id_mou)
    {
        $data = $this->monevData($id_mou);
        if ($data['status_mou'] < 3) {
            return redirect()->to(base_url('perjanjian'));
        }
        return view('perjanjian/monev', $data);
    }

    public function upload_monev($id_mou)
    {
        try {
            $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);

            $data = $this->request->getPost();

            $temp = explode('-', $data['tahun-akademik-monev']);

            $semester = (int) $temp[1] ?? '';
            $periode = (int) $temp[0] ?? '';

            // dump($semester, $periode);

            $check = (new PerjanjianMonevModel)->select(('mou_monev.*'))
                ->where("mou_monev.id_mou = '$id_mou' AND mou_monev.semester = '$semester' AND mou_monev.periode = '$periode' AND mou_monev.deleted_at IS NULL");

            $check = $check->countAllResults();

            if ($check > 0) {
                echo json_encode([
                    'status' => 'sudah',
                    'message' => $semester == 0 ? "Monev Semester Genap " . ($periode - 1) . " / " . $periode . " sudah ada" : "Monev Semester Gasal $periode / " . ($periode + 1) . " sudah ada",
                ]);
                exit;
            }

            $data_monev = [
                'id_monev' => $id,
                'id_mou' => $id_mou,
                'evaluator' => $data['evaluator-monev'] ?? '',
                // 'mekanisme' => $data['mekanisme-monev'] ?? '',
                'status_kegiatan' => $data['status-kegiatan'] ?? '',
                'aktifitas_sudah' => $data['aktifitas-sudah'] ?? '',
                'aktifitas_belum' => $data['aktifitas-belum'] ?? '',
                'kendala' => $data['kendala'] ?? '',
                'solusi' => $data['solusi'] ?? '',
                'perpanjangan' => $data['perpanjangan'] ?? null,
                'periode' => $periode ?? null,
                'semester' => $semester ?? null,
            ];

            (new PerjanjianMonevModel())->insert($data_monev);

            echo json_encode('success');
            // echo json_encode($data);
        } catch (\Exception $e) {
            echo json_encode("error" . $e->getMessage());
        }
    }


    public function edit_monev($id_mou)
    {
        try {
            // $id = (new \Hidehalo\Nanoid\Client())->formattedId(getenv('NANOID_ALPHABET'), 16);

            $data = $this->request->getPost();

            $id_monev = $data['id_monev'];

            $data_monev = [
                'evaluator' => $data['evaluator-edit-monev'] ?? '',
                // 'mekanisme' => $data['mekanisme-monev'] ?? '',
                'status_kegiatan' => $data['status-kegiatan-edit-monev'] ?? '',
                'aktifitas_sudah' => $data['aktifitas-sudah-edit-monev'] ?? '',
                'aktifitas_belum' => $data['aktifitas-belum-edit-monev'] ?? '',
                'kendala' => $data['kendala-edit-monev'] ?? '',
                'solusi' => $data['solusi-edit-monev'] ?? '',
                'perpanjangan' => $data['perpanjangan-edit-monev'] ?? null,
            ];

            (new PerjanjianMonevModel())->update($id_monev, $data_monev);

            echo json_encode('success');
        } catch (\Exception $e) {
            echo json_encode("error" . $e->getMessage());
        }
    }

    public function delete_monev($id_monev)
    {
        try {
            // $id_monev = $this->request->getPost('id_monev');

            $data_monev = [
                'deleted_at' => date('Y-m-d H:i:s'),
            ];

            (new PerjanjianMonevModel())->update($id_monev, $data_monev);

            echo json_encode('success');
        } catch (\Exception $e) {
            echo json_encode("error" . $e->getMessage());
        }
    }

    public function send_notif($user_id, $msg)
    {
        $data['pemberi_tugas_user_id'] = session('id');
        if (empty($data['user_ids']))
            $data['user_ids'][] = $user_id;
        foreach ($data['user_ids'] as $val) {
            $data['user_id'] = $val;
            $data['tugas'] = $msg;
            (new TodoModel())->insert($data);
        }

        $notifTokens = (new \App\Models\NotificationTokenModel())->where('user_id', $data['user_id'])->findAll();
        foreach ($notifTokens as $notifToken) {
            try {
                $factory = (new Factory)->withServiceAccount('/var/www/surat/fmipa-8a1b4-firebase-adminsdk-g8rl3-a81c70c820.json');
                $messaging = $factory->createMessaging();
                $message = CloudMessage::withTarget('token', $notifToken->fcmtoken)
                    ->withNotification(Notification::create('Anda memiliki tugas baru!', $msg))
                    ->withData(['user_id' => $data['user_id']]);
                $messaging->send($message);
            } catch (\Throwable $t) {
                (new \App\Models\NotificationTokenModel())->where('fcmtoken', $notifToken->fcmtoken)->delete();
                continue;
            }
        }
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $departemen_ugm = $data['departemen_ugm'] ?? '';
        $data['program_studi_terlibat'] = json_encode($data['program_studi_terlibat'] ?? []);
        $data['bidang_kerjasama'] = json_encode($data['bidang_kerjasama'] ?? []);
        $data['jangka_waktu'] = [
            'tahun' => $data['tahun'] != "" ? $data['tahun'] : "0",
            'bulan' => $data['bulan'] != "" ? $data['bulan'] : "0",
        ];
        $data['jangka_waktu'] = json_encode($data['jangka_waktu']);
        $model = (new PerjanjianModel);
        $row = $model->select('*')
            ->join('mou_instansi_mitra', 'mou_instansi_mitra.id_instansi_mitra = mou.id_instansi_mitra')
            ->join('mou_pic_mitra', 'mou_pic_mitra.id_mou = mou.id_mou')
            ->join('mou_pic_ugm', 'mou_pic_ugm.id_mou = mou.id_mou')
            ->where('mou.id_mou', $id)->first();
        if ($data['status'] == 1) {

            $data['status_mou'] = 1;

            $judul_kerjasama = $data['judul_kerjasama'];
            if (!empty($this->request->getFile('dokumen-mou')) && !empty($this->request->getFile('dokumen-mou')->getFileName())) {
                $file = $this->request->getFile('dokumen-mou');
                $file_metadata = $this->uploadMoUToDrive($id, $departemen_ugm, $file, $judul_kerjasama, pic_ugm_email: $data['email_pic_ugm'] ?? '');
            }

            $dataDokumen['id_mou'] = $id;
            $dataDokumen['id_revisi_mou'] = 1;
            $dataDokumen['id_gdrive_dokumen_ori'] = $file_metadata['ori']['id'];
            $dataDokumen['id_gdrive_dokumen'] = $file_metadata['comment']['id'];
            $dataDokumen['id_gdrive_folder'] = $file_metadata['ori']['parents'][0];
            $dataDokumen['url_view_dokumen_ori'] = $file_metadata['ori']['webViewLink'];
            $dataDokumen['url_view_dokumen'] = $file_metadata['comment']['webViewLink'];
            $dataDokumen['keterangan'] = "Dokumen pengajuan awal kerjasama $judul_kerjasama.";
            $dataDokumen['status_revisi'] = 0;
            $dataDokumen['tanggal_revisi'] = date('Y-m-d');
            (new PerjanjianDokumenModel())->insert($dataDokumen);
        }
        $this->perjanjian->update($id, $data);
        (new InstansiMitraModel())->update($row->id_instansi_mitra, $data);
        (new PICMitraModel())->update($row->id_pic_mitra, $data);
        (new PICUGMModel())->update($row->id_pic_ugm, $data);
        // $row = $this->perjanjian
        //     ->join('users', 'surat_kp.user_id = users.id')
        //     ->where('surat_kp.id', $id)->first();
        $db = \Config\Database::connect();
        session()->setFlashdata('success', 'Data berhasil disimpan');

        return $this->response->redirect(site_url('perjanjian/details/' . $id));
    }

    //Proses update komentar
    public function updatekomentar($id)
    {
        $model = new PerjanjianModel();
        $data = [
            'komentar' => $this->request->getVar('komentar'),
        ];
        $model->update($id, $data);
        return $this->response->redirect(site_url('perjanjian'));
    }

    //Proses delete data surat
    public function delete($id)
    {
        if (!session('logged_in'))
            return redirect()->to(base_url('auth'));
        (new PerjanjianModel)->where('id_mou', $id)->delete();
        return $this->response->redirect(site_url('perjanjian'));
    }

    //Proses buat nomor surat
    public function create_no_surat($id)
    {
        $row = $this->perjanjian->where('id', $id)->first();
        if ($row->status == 3) {
            $arr = explode('/', $row->no_surat);
            $kode_klasifikasi = $arr[count($arr) - 2];
            $r = (new NomorSuratModel())->where('kode_klasifikasi', $kode_klasifikasi)->first();
            $increment = $r->nomor;
            $no_surat = $increment . $row->no_surat;
            $this->perjanjian->update($id, ['no_surat' => $no_surat]);
            (new NomorSuratModel())->update($r->id, ['nomor' => ++$increment]);
        }
    }

    //Proses set status
    public function status($id, $status)
    {
        $model = new PerjanjianModel();
        $model->update($id, ['status' => $status]);
        return $this->response->redirect(site_url('perjanjian'));
    }

    //Proses set status
    // public function verification($id, $jenis_user)
    // {
    //     $row = (new perjanjianModel)->where('id', $id)->first();
    //     $is_kadep = (bool)(new \App\Models\PegawaiModel())->where('id', $row->departemen_pegawai_id)->where('user_id', session('id'))->countAllResults();
    //     $is_penandatangan = (bool)(new \App\Models\PegawaiModel())->where('id', $row->penandatangan_pegawai_id)->where('user_id', session('id'))->countAllResults();

    //     $model = new perjanjianModel();
    //     if ($jenis_user == 'verifikator') {
    //         if ($row->status == 2){
    //             $model->update($id, ['status' => 3]);
    //             return $this->response->redirect(site_url("pdf/generate/$id/0/1"));
    //         }
    //         else{
    //         $model->update($id, ['verifikasi_verifikator' => 1, 'status' => 2]);
    //         $db = \Config\Database::connect();
    //         $db->query("INSERT INTO notifications
    //             select null, id, 'surat_kp', 'surat', '0', now(), now() from users 
    //             where jenis_user IN('dekan', 'wadek', 'wadek1', 'wadek2', 'wadek3', 'wadek4')
    //         ");
    //         session()->setFlashdata('success', 'Berkas berhasil disetujui');
    //         }
    //     } elseif ($is_penandatangan) {
    //         $model->update($id, ['status' => 3]);
    //         return $this->response->redirect(site_url("pdf/generate/$id/0/1"));
    //     }
    //     return $this->response->redirect(site_url('perjanjian'));
    // }

    //Proses cetak data surat
    public function print()
    {
        $db = \Config\Database::connect();
        $data['surat_kp'] = $db->query("SELECT
        nama, nomor, pangkat
        FROM users
        JOIN surat_kp ON user_id=users.id")->getResult();
        return view('pdf/generate', $data);
    }

    //Proses kirim email
    public function send_email($to)
    {
        $email = \Config\Services::email();

        $email->setFrom('noreply-mipa@ugm.ac.id', 'Your Name');
        $email->setTo($to);
        $email->setCC('another@another-example.com');
        $email->setBCC('them@their-example.com');

        $email->setSubject('Email Test');
        $email->setMessage('Testing the email class.');

        $email->send();
        // var_dump($email);
    }

    //Proses upload data surat
    public function upload($id)
    {
        return view('surat_kp/upload', ['id' => $id]);
    }

    public function uploads($id)
    {
        if (!empty($this->request->getFile('berkas')->getFilename())) {
            if (file_exists("upload/pertanggungjawaban_surat_kp/$id.pdf"))
                unlink("upload/pertanggungjawaban_surat_kp/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/pertanggungjawaban_surat_kp', $id . '.pdf');
        }
        session()->setFlashdata('success', 'Berkas berhasil diupload');
        return $this->response->redirect(site_url('perjanjian'));
    }

    // public function uploads($id)
    // {
    //     $db = \Config\Database::connect();
    //     $result = $db->query("SELECT file_pertanggungjawaban, date(NOW()) BETWEEN tanggal_kegiatan_mulai AND tanggal_kegiatan_selesai as dalam_periode FROM surat_kp WHERE id = '$id'")->getResult();

    //     $old_name = $result[0]->file_pertanggungjawaban;
    //     if (file_exists("upload/pertanggungjawaban_surat_kp/$old_name.pdf")) unlink("upload/pertanggungjawaban_surat_kp/$old_name.pdf");

    //     $file = $this->request->getFile('berkas');
    //     $name = hash_file('ripemd160', $file);
    //     $file->move('upload/pertanggungjawaban_surat_kp',  $name . '.pdf');

    //     (new perjanjianModel())->update($id, ['file_pertanggungjawaban' => $name . '.pdf']);
    //     session()->setFlashdata('success', 'Berkas berhasil diupload');
    //     return $this->response->redirect(site_url('perjanjian'));
    // }

    //Proses bagikan surat tugas
    // public function share($id)
    // {
    //     $db = \Config\Database::connect();
    //     $users = $db->query("SELECT id, nama as name FROM users ORDER BY nama")->getResult();
    //     for ($i = 0; $i < count($users); $i++) $users[$i]->id = (int)$users[$i]->id;
    //     $row = (new perjanjian)->where('id', $id)->first();
    //     $data = [
    //         'row' => $row,
    //         'users' => json_encode($users),
    //     ];
    //     return view('perjanjian/share', $data);
    // }

    //Proses simpan bagikan surat
    public function saveshare($id)
    {
        $shares = $this->request->getVar('shares');
        for ($i = 0; $i < count($shares); $i++)
            $shares[$i] = (int) $shares[$i];
        foreach ($shares as $share) {
            $db = \Config\Database::connect();
            $db->query("INSERT INTO notifications
                        select null, users.id, 'surat_kp', 'surat', '0', now(), now() from users 
                        where users.id = " . ($share ?? "''"));
        }
        $model = new Perjanjian();
        $model->update($id, ['shares' => json_encode($shares)]);
        session()->setFlashdata('success', 'Alhamdulillah.. Surat berhasil dibagikan!');
        return $this->response->redirect(site_url('perjanjian'));
    }

    //Proses upload dasar penerbitan
    public function upload_dasar_penerbitan($id)
    {
        if (!empty($this->request->getFile('berkas')) && !empty($this->request->getFile('berkas')->getFileName())) {
            if (file_exists("upload/dasar_penerbitan_surat_kp/$id.pdf"))
                unlink("upload/dasar_penerbitan_surat_kp/$id.pdf");
            $file = $this->request->getFile('berkas');
            $file->move('upload/dasar_penerbitan_surat_kp', $id . '.pdf');
        }
    }

    public function topdf($id, $save = false)
    {
        $filename = date('y-m-d_H.i.s') . '-surat_kp';

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $d = "Untuk cek validasi surat, silakah buka alamat berikut:\nhttps://mipa.ugm.ac.id/validasi-surat/\n\nKode surat: " . $id;
        $qr = (new QRCode)->render($d);
        $qr_note = "<br><i><b>Dokumen ini telah ditandatangani secara elektronik. Verifikasi keabsahan dokumen dapat dilakukan dengan scan QR code berikut.</b></i>";

        // load HTML content
        $db = \Config\Database::connect();

        $data['row'] = $db->query("SELECT
        no_surat, nama, no_surat, pegawais.pangkat, tanggal_pengajuan,tanggal_kegiatan_mulai, tanggal_kegiatan_selesai, tanggal_kegiatan,
        nama_surat, lokasi_kegiatan, status,
        pegawais.nip nip, tembusan, kepada_surat, peserta, jenis_kegiatan, alamat_kegiatan, alamat_tambahan_satu, alamat_tambahan_dua
        FROM users
        JOIN pegawais ON users.id = pegawais.user_id
        JOIN surat_kp ON surat_kp.user_id=pegawais.user_id
        WHERE surat_kp.id = '$id'")->getResult()[0];

        $data['penandatangan'] = $db->query("SELECT 
        pegawais.id id, nama_publikasi nama, pegawais.nip nip, prodi, departemen, pegawais.pangkat pangkat, golongan, jabatan, penandatangan.nama_penandatangan label
        FROM surat_kp 
        JOIN pegawais ON surat_kp.penandatangan_pegawai_id = pegawais.id
        JOIN penandatangan ON pegawais.id = penandatangan.pegawai_id
        WHERE surat_kp.id = '$id'")->getResult();

        if (count($data['penandatangan']) > 0) {
            $data['penandatangan'] = $data['penandatangan'][0];
        } else {
            $data['penandatangan'] = [];
        }

        $data['row']->tanggal_kegiatan = json_decode($data['row']->tanggal_kegiatan) ?? [];
        $data['row']->peserta = json_decode($data['row']->peserta) ?? [];


        $data['qr'] = $data['row']->status == 3 ? $qr : '';
        $data['qr_note'] = $data['row']->status == 3 ? $qr_note : '';
        $data['anggotas'] = [];

        $dompdf->getOptions()->setChroot(FCPATH);


        // return view('perjanjian/print', $data);
        // exit;

        $dompdf->loadHtml(view('perjanjian/print', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // $output = $dompdf->output();
        // file_put_contents("upload/surat_kp/" . $id . ".pdf", $output);          
        // return $this->response->redirect(site_url('perjanjian'));
        if ($save) {
            file_put_contents("upload/surat_kp/$id.pdf", $dompdf->output());
            file_put_contents("validasi/$id.pdf", $dompdf->output());
        } else {
            $dompdf->stream("$filename.pdf", ['Attachment' => 0]);
        }
    }
}