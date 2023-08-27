<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\TodoModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Todo extends ResourceController
{
    protected $modelName = 'App\Models\TodoModel';
    protected $format = 'json';

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$header) return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];

        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return $this->respond($this->model->where('user_id', $decoded->id)->where('NOW() <= DATE_ADD(created_at, INTERVAL 10 DAY)')->findAll());
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $record = $this->model->find($id);
        if (!$record) {
            # code...
            return $this->failNotFound(sprintf(
                'post with id %d not found',
                $id
            ));
        }

        return $this->respond($record);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$header) return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $notifTokens = (new \App\Models\NotificationTokenModel())->where('user_id', $decoded->id)->findAll();

        $data = $this->request->getJson();
        if (!$this->model->save($data)) {
            return $this->fail($this->model->errors());
        }
        foreach ($notifTokens as $notifToken) {
            try {
                $factory = (new Factory)->withServiceAccount('/var/www/surat/fmipa-8a1b4-firebase-adminsdk-g8rl3-a81c70c820.json');
                $messaging = $factory->createMessaging();
                $message = CloudMessage::withTarget('token', $notifToken->fcmtoken)
                    ->withNotification(Notification::create('Anda memiliki tugas baru!', $data->tugas))
                    ->withData(['user_id' => $decoded->id]);
                $messaging->send($message);
            } catch (\Throwable $t) {
                (new \App\Models\NotificationTokenModel())->where('fcmtoken', $notifToken->fcmtoken)->delete();
                continue;
            }
        }

        // return $this->respondCreated($message, 'post created');
        return $this->respond($data, 200, 'post created');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        $data['id'] = $id;

        if (!$this->model->save($data)) {
            return $this->fail($this->model->errors());
        }

        return $this->respond($data, 200, 'post updated');
    }

    public function toggle($id = null)
    {
        $todo = $this->model->find($id);
        $data = ['id' => $todo->id, 'status_tugas' => !((bool)$todo->status_tugas)];
        if (!$this->model->save($data)) {
            return $this->fail($this->model->errors());
        }
        return $this->respond($data, 200, 'post updated');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $delete = $this->model->delete($id);
        if ($this->model->db->affectedRows() === 0) {
            return $this->failNotFound(sprintf(
                'post with id %id not found or already deleted',
                $id
            ));
        }

        return $this->respondDeleted(['id' => $id], 'post deleted');
    }
}
