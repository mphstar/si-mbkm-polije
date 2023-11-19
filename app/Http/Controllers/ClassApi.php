<?php
namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ClassApi {
    public function generateToken()
    {

        $grant_type = 'client_credentials';
        $client_id = '39';
        $client_secreet = 'OY1PSZcSJn4G0TTmm063Gqjj726z2emrJBagWwZ3';

        if (!isset($grant_type) || !isset($client_id) || !isset($client_secreet)) {
            throw new Exception('ops , your env not included the token');
        }

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'http://api.polije.ac.id/oauth/token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('grant_type' => $grant_type, 'client_id' => $client_id, 'client_secret' => $client_secreet),
            )
        );

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }

    private function hitAPI($url = '', $query = [], Request $request){
        try {
            $http = new Client();

            //Check sesi token ada atau tidak
            if ($request->session()->has('access_token')) {
                $data = $request->session()->get('access_token');
            } else {
                //jika tidak ada, maka mengambil access token

                $data = $this->generateToken()->access_token;
                //simpan access token ke sesi
                $request->session()->put('access_token', $data);
            }

            // dd($data);
            //Jika token sudah didapatkan, masukkan URl API untuk mengambil data
            if ($data) {
                $result = $http->get(url($url), [
                    'headers' => [
                        'Accept' => 'application/json',
                        'User-Agent' => '/Postman/i',
                        'Authorization' => 'Bearer ' . $data
                    ],
                    //Parameter API
                    'query' => $query
                ]);

                $respone = json_decode($result->getBody());

                if ($respone) {
                    return $respone;
                }

                //Jika token tidak di autorisasi, tampilkan halaman error.
            } else {
                $data['title'] = '403';
                $data['name'] = 'No Authorization.';

                return response()
                    ->view('errors.403', 403);
            }
        } catch (\Exception $e) {
            dd($e);
            abort($e->getCode());
        }
    }

    public function getJurusan(Request $request){
        return $this->hitAPI('http://api.polije.ac.id/resources/global/unit', [
            "debug" => true,
            "jenis" => "JURUSAN"
        ], $request);
    }

    public function getProdi(Request $request){
        return $this->hitAPI('http://api.polije.ac.id/resources/global/unit', [
            "debug" => true,
            "jenis" => "PROGRAM STUDI"
        ], $request);
    }
}