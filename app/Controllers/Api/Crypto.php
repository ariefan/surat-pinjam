<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Throwable;

class Crypto extends ResourceController
{
    protected $format = 'json';

    // For mTLS
    protected $ca_cert = "ca.cert"; // CA Certificate filename
    protected $csr_priv_key = "appprivate.key"; // Client private key filename
    protected $client_cert = "appcert.pem"; // Client certificate filename

    protected $slot_id = 1;
    protected $slot_password = "Sandhiguna1!";
    protected $url = 'https://lcev.sandhiguna.com:8083/v1.0';
    protected $encryption_key_id = "encrypt1";
    protected $wrapping_key_id = "wrap1";
    protected $signing_key_id = "sign1";

    function ping()
    {
        return $this->respond(__DIR__);
    }

    function postCallAPI($url, $data, $die_if_fault = true)
    {
        global $ca_cert, $csr_priv_key, $client_cert;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        // Options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
            )
        );

        // mTLS
        curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSLCERT, $client_cert);
        curl_setopt($curl, CURLOPT_SSLKEY, $csr_priv_key);
        curl_setopt($curl, CURLOPT_CAINFO, $ca_cert);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        if (!$result) {
            die("Calling to " . $url . " Failed: " . curl_error($curl) . "\r\n");
        }

        curl_close($curl);
        echo "Call to " . $url . " :\r\nRequest:\r\n" . $data . "\r\nResponse:\r\n" . $result . "\r\n\r\n";
        $decoded_result = json_decode($result);
        if ($die_if_fault) {
            if (!isset($decoded_result->result)) {
                die("Exited because Fault response returned\r\n");
            }
        }
        return $decoded_result;
    }

    function login()
    {
        try {
            $body = array("slotId" => $this->slot_id, "password" => $this->slot_password);
            $response = $this->postCallAPI($this->url . '/agent/login', json_encode($body));
            $session_token = $response->result->sessionToken;
            return $this->respond($session_token);
        } catch (Throwable $e) {
            return $this->respond($e->getMessage());
        }
    }

    function examplle()
    {
        // Variables :
        $slot_id = 3;
        $slot_password = "Sandhiguna1!";
        $url = 'https://lcev.sandhiguna.com:7004/v1.0';
        $encryption_key_id = "encrypt1";
        $wrapping_key_id = "wrap1";
        $signing_key_id = "sign1";

        // Login
        $body = array("slotId" => $slot_id, "password" => $slot_password);
        $response = $this->postCallAPI($url . '/agent/login', json_encode($body));
        $session_token = $response->result->sessionToken;

        // Encrypt
        $body = array("sessionToken" => $session_token, "slotId" => $slot_id, "keyId" => $encryption_key_id, "plaintext" => array(array("text" => $plaintext, "aad" => $sign_data)));
        $response = $this->postCallAPI($url . '/encrypt', json_encode($body));
        $key_version = $response->result->keyVersion;
        $ciphertext = $response->result->ciphertext;
        $ciphertext[0]->aad = $sign_data;

        // Decrypt
        $body = array("sessionToken" => $session_token, "slotId" => $slot_id, "keyId" => $encryption_key_id, "keyVersion" => $key_version, "ciphertext" => $ciphertext);
        $response = $this->postCallAPI($url . '/decrypt', json_encode($body));
    }

}