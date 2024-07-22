<?php

namespace App\Http\Controllers;

use App\Mail\LoginLink;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendEmail(Request $request){
        $email = $request->input('email');
        $url = $request->input('main_url');
        $exist = DB::table('users')->select('email','id')->where('email',$email)->first();
        $time = Carbon::now()->format('Y-m-d H:i:s');
        $token = md5($email.$time);
        if($exist){
            DB::table('users')->where('id',$exist->id)->update(['token' => $token, 'time' => $time, 'login' => 0]);
        }else{
            DB::table('users')->insert(['email' => $email,'token' => $token, 'time' => $time, 'login' => 0]);
        }
        $url = $url.'/dashboard/'.$token;
        $response = array(
            'status' => 'success',
            'url' => $url,
        );
        Mail::to($email)
            ->send(new LoginLink($url));
        return response()->json($response);
    }

    public function edit(Request $request){
        $email = $request->input('email');
        $token = $request->input('token');
        $uid = $request->input('uid');
        $exist = DB::table('users')->select('email','id','time','token','login')->where('id',$uid)->where('token',$token)->first();
        $nowtime = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
        if($exist){
            $dbtime = strtotime(date('Y-m-d H:i:s', strtotime('+60 minutes '.$exist->time)));
            if($dbtime < $nowtime){
                $response = array(
                    'status' => 'failure',
                    'message' => 'Sorry You are Timed Out',
                );
            }else{
                DB::table('users')->where('id',$exist->id)->update(['email' => $email]);
                $response = array(
                    'status' => 'success',
                    'message' => 'Updated Successfully '.$email,
                    'token' => $exist->token,
                    'uid' => $exist->id,
                );
            }
        }else{
            $response = array(
                'status' => 'failure',
                'message' => 'Un-Authorized Access'
            );
        }
        return response()->json($response);
    }

    public function checkAuth(Request $request){
        $token = $request->input('token');
        $exist = DB::table('users')->select('id','email','time','token','login')->where('token',$token)->first();
//        return $exist->time;
        $nowtime = strtotime(Carbon::now()->format('Y-m-d H:i:s'));
//        $token = md5($time);
        if($exist && $exist->token == $token){
            $dbtime = strtotime(date('Y-m-d H:i:s', strtotime('+60 minutes '.$exist->time)));
            if($exist->login){
                $response = array(
                    'status' => 'failure',
                    'message' => 'Already Logged In',
                );
            }elseif($dbtime < $nowtime){
                $response = array(
                    'status' => 'failure',
                    'message' => 'Sorry You are Timed Out',
                );
            }else{
                DB::table('users')->where('id',$exist->id)->update(['login' => 1]);
                $response = array(
                    'status' => 'success',
                    'message' => 'Access Granted! Welcome '.$exist->email,
                    'token' => $exist->token,
                    'uid' => $exist->id,
                );
            }

        }else {
            $response = array(
                'status' => 'failure',
                'message' => 'Un-Authorized Access',
            );
        }
        return response()->json($response);
    }
}
