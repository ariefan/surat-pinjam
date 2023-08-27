<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\PegawaiModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class Notifications extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function index()
    {
        define('API_ACCESS_KEY', 'AIzaSyCDaDlWVy4Q8OBJ_UCqmij5Rqt8lgSCTAg');
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token = '235zgagasd634sdgds46436';

        $notification = [
            'title' => 'title',
            'body' => 'body of message.',
            'icon' => 'myIcon',
            'sound' => 'mySound'
        ];
        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);


        echo $result;
    }

    public function send($user_id_pengirim, $user_id_penerima, $pesan)
    {
        $notifTokens = (new \App\Models\NotificationTokenModel())->where('user_id', $user_id_penerima)->findAll();
        foreach ($notifTokens as $notifToken) {
            try {
                $factory = (new Factory)->withServiceAccount('/var/www/surat/fmipa-8a1b4-firebase-adminsdk-g8rl3-a81c70c820.json');
                $messaging = $factory->createMessaging();
                $message = CloudMessage::withTarget('token', $notifToken->fcmtoken)
                    ->withNotification(Notification::create('FMIPA', $pesan))
                    ->withData(['user_id' => $user_id_penerima]);
                $messaging->send($message);
            } catch (\Throwable $t) {
                return $this->respond($t);
                (new \App\Models\NotificationTokenModel())->where('fcmtoken', $notifToken->fcmtoken)->delete();
                continue;
            }
        }
        return $this->respond([$user_id_pengirim, $user_id_penerima, $pesan]);
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
