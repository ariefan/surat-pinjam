<?php

namespace App\Controllers;

use App\Models\TodoModel;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Todo extends BaseController
{
    private $todo;

    function __construct()
    {
        $this->todo = new TodoModel();
    }

    public function index($count = false)
    {
    }

    public function create()
    {
        $row = new TodoModel();
        $row->tanggal_pengajuan = date('Y-m-d');
        $row->tembusan = [];
        $data = [
            'action' => 'store',
            'row' => $row,
        ];
        return view('todo/form', $data);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data['pemberi_tugas_user_id'] = session('id');
        if (empty($data['user_ids'])) $data['user_ids'][] = $data['user_id'];
        foreach ($data['user_ids'] as $val) {
            $data['user_id'] = $val;
            $this->todo->insert($data);
        }

        $notifTokens = (new \App\Models\NotificationTokenModel())->where('user_id', $data['user_id'])->findAll();
        foreach ($notifTokens as $notifToken) {
            try {
                $factory = (new Factory)->withServiceAccount('/var/www/surat/fmipa-8a1b4-firebase-adminsdk-g8rl3-a81c70c820.json');
                $messaging = $factory->createMessaging();
                $message = CloudMessage::withTarget('token', $notifToken->fcmtoken)
                    ->withNotification(Notification::create('Anda memiliki tugas baru!', $data['tugas']))
                    ->withData(['user_id' => $data['user_id']]);
                $messaging->send($message);
            } catch (\Throwable $t) {
                (new \App\Models\NotificationTokenModel())->where('fcmtoken', $notifToken->fcmtoken)->delete();
                continue;
            }
        }

        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(!empty($data['link']) ? $data['link'] : site_url('home'));
    }

    public function edit($id)
    {
        $row = (new TodoModel)->where('id', $id)->first();
        $data = [
            'action' => 'update',
            'row' => $row,
        ];
        return view('todo/form', $data);
    }

    public function toggle($id)
    {
        $row = (new TodoModel)->where('id', $id)->first();
        $this->todo->update($id, ['status_tugas' => !$row->status_tugas]);
        return $this->response->setJSON(!$row->status_tugas);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $this->todo->update($id, $data);
        session()->setFlashdata('success', 'Data berhasil disimpan');
        return $this->response->redirect(site_url('todo'));
    }

    public function delete($id)
    {
        (new TodoModel)->where('id', $id)->delete();
        return $this->response->redirect(site_url('home'));
    }
}
