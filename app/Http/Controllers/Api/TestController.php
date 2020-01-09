<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Model\User;
use Illuminate\Support\Facades\Redis;
class TestController extends Controller
{
    public function reg(Request $request){
//       print_r($request->input());
        $pass1=$request->input('pass');
        $pass2=$request->input('pass2');
        if($pass1!=$pass2){
            die('两次输入的密码不一样');
        }
        $pwd=password_hash($pass1,PASSWORD_BCRYPT);
        $data=[
            'email'         =>$request->input('email'),
            'username'      =>$request->input('username'),
            'pwd'           =>$pwd,
            'tel'           =>$request->input('tel'),
            'last_login'    =>time(),
            'last_ip'       =>$_SERVER['REMOTE_ADDR'],
        ];
        $id=User::insertGetId($data);
        echo $id;die;
    }
    public function login(Request $request){
            $username=$request->input('username');
        $pass=$request->input('pass');
//        echo "pass:".$pass;echo "<br>";
        $userInfo=User::where(['username'=>$username])->first();
//        dump($userInfo['pwd']);
        if($userInfo){
            if(password_verify($pass,$userInfo->pwd)){
                echo '登陆成功';
//                生成token
                $token=Str::random(32);
                $response=[
                    'errno' =>0,
                    'msg'   =>'ok',
                    'data'=>[
                        'token'=>$token
                    ]
                ];
                return $response;
            }else{
                $response=[
                    'errno' =>40003,
                    'msg'   =>'密码不正确',
                ];
                return $response;
            }
        }else{
            $response=[
                'errno' =>40002,
                'msg'   =>'没有此用户',
            ];
            return $response;
        }
    }

    public function userList(){
        $list=User::all();
        echo'<pre>'; print_r($list);echo'<pre>';
//        $user_token=$_SERVER['HTTP_TOKEN'];
//        echo 'user_token:'.$user_token;echo'<br>';
//
//        $current_url=$_SERVER['REQUEST_URI'];
//        echo '当前URL'.$current_url;echo'<hr>';
////        echo '<pre>';print_r($_SERVER);echo '<pre>';
////        $url=$_SERVER[''].$_SERVER[''];
//
//        $redis_key='str:count:u:'.$user_token.'url:'.md5($current_url);
//        echo 'redis_key:'.$redis_key;echo'<br>';
//
//        $count=Redis::get($redis_key);
//        echo '访问次数'.$count;echo '<br>';
//        if($count >=5){
//            echo '访问次数以达到上限';
//            Redis::expire($redis_key,10);
//            die;
//        }
//        $count=Redis::incr($redis_key);
//        echo 'count: '.$count;
    }

    public function md1(){

    }
}
