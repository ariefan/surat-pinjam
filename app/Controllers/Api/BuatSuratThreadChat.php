<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\BuatSuratThreadChatModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class BuatSuratThreadChat extends ResourceController
{
    protected $modelName = 'App\Models\BuatSuratThreadChatModel';
    protected $format = 'json';

    private function getCredential()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$header) return $this->failUnauthorized('Anda tidak memiliki akses');
        $token = explode(' ', $header)[1];
        return JWT::decode($token, new Key($key, 'HS256'));
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index($id = null)
    {
        $this->getCredential();
        return $this->respond($this->model->where('buat_surat_id', $id)->findAll());
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
        $cred = $this->getCredential();
        $data = $this->request->getJson();
        $data->user_id = $cred->id;
        if (!$this->model->save($data)) {
            return $this->fail($this->model->errors());
        }
        return $this->respondCreated($data, 'post created');
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
            # code...
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
