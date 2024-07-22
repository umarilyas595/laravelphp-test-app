<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        return view("welcome");
    }
    public function letIn($token){
        return view("dashboard",array('token' => $token));
//        $client = new Client();
//        echo $token;
//        die();
        $url = config('app.baseurl_api').'check-auth/';
        $postString = 'auth='.$token;
//        json_decode(file_get_contents($url), true);
//        echo $url;
//        die();
        $response = Http::get("$url", ['auth' => $token]);
//        $res = $client->request('POST', "$url"
//            , [
//                'headers' => [
//                    'Authorization' => csrf_token(),
//                    'X-CSRF-Token'=> csrf_token(),
//                    '_token' => csrf_token(),
//                    'Content-Type'     => 'application/x-www-form-urlencoded',
//                ],
//            'form_params' => [
//                'auth'   => $token,
//                'headers' => ['X-CSRF-Token'=> csrf_token()],
//            ]
//        ]
//        );
//        $response = Http::get($url);

//                'headers' => ['X-CSRF-Token'=> csrf_token()],
//        echo $res->getStatusCode();
        // 200
//        echo $res->getHeader('content-type');
        // 'application/json; charset=utf8'
//        echo $res->getBody();

        $respo = json_decode($response->body());
        if($respo['status'] == 'failure'){
        }

    }
}
