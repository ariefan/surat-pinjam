<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\PegawaiModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function login()
    {
        helper(['form']);
        $rules = [
            'username' => 'required|valid_email',
            'password' => 'required'
        ];
        if (!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        $model = new UserModel();
        $user = $model->where("username", $this->request->getVar('username'))->first();
        $pegawai = (new PegawaiModel())->where("user_id", $user->id)->first();
        if (!$user) return $this->failNotFound('Email Not Found');

        $verify = password_verify($this->request->getVar('password'), $user->password);
        if (!$verify) return $this->fail('Wrong Password');

        $key = getenv('TOKEN_SECRET');
        $payload = [
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "id" => $user->id,
            "username" => $user->username,
            "nama" => $pegawai->nama_publikasi ?? $user->nama,
            "jenisUser" => $user->jenis_user,
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->respond([
            'id' => (int)$user->id,
            'username' => $user->username,
            'nama' => $user->nama,
            'jenisUser' => $user->jenis_user,
            "token" => $token,
        ]);
    }

    use ResponseTrait;
    public function profile()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if (!$header) return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            $response = [
                'id' => $decoded->id,
                'username' => $decoded->username,
                'jenis_user' => $decoded->jenisUser,
                'nama' => $decoded->nama
            ];
            return $this->respond($response);
        } catch (\Throwable $th) {
            return $this->fail('Invalid Token');
        }
    }

    public function saveFcmtoken()
    {
        $data = $this->request->getJson();
        $db = \Config\Database::connect();
        if(!empty($data->user_id) && $data->user_id > 0){
            if (count($db->query("SELECT * FROM notification_token WHERE user_id='" . $data->user_id . "' AND fcmtoken = '" . $data->fcmtoken . "'")->getResult()) == 0) {
                if (!$db->query("REPLACE INTO notification_token (user_id, fcmtoken) VALUES('" . $data->user_id . "', '" . $data->fcmtoken . "')")) {
                    return $this->fail($this->model->errors());
                }
            }
        }
        return $this->respondCreated($data, 'token saved');
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
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
        //
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
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }
}
