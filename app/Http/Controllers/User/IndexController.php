<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
class IndexController extends Controller
{
    function test(){
        echo '<pre>';print_r($_SERVER);echo '</pre>';
    }

    //用户注册
    function reg(Request $request){
        echo '<pre>';print_r(request()->input());echo '</pre>';
        $pass1 = request()->input('pass1');
        $pass2 = request()->input('pass2');
        if($pass1 != $pass2){
            die("两次输入的密码不一致");
        }

        $password = password_hash($pass1,PASSWORD_BCRYPT);
        $data = [
            'email' =>request()->input('email'),
            'name' => request()->input('name'),
            'password' =>$password,
            'mobile' =>request()->input('mobile'),
            'last_login' =>time(),
            'last_ip' =>$_SERVER['REMOTE_ADDR'], //获取远程IP
        ];
        $uid=User::insertGetId($data);
        var_dump($uid);die;
    }

    //用户登录接口
    function login(Request $request){
        $name = request()->input('name');
        $pass = request()->input('pass');
        //echo "pass: ".$pass;echo '</br>';
        $u=User::where(['name'=>$name])->first();
        if($u){
            //echo '<pre>';print_r($u->toArray());echo '</pre>';
            //验证密码
            if(password_verify($pass,$u->password)){
                //登陆成功
                echo "登陆成功";
                //生成token
                $token = str::random(32);
                $response = [
                    'errno'=>0,
                    'msg'=>'ok',
                    'data'=>[
                        'token'=>$token
                    ]
                ];
            }else{
                $response = [
                    'errno'=> 400003,
                    'msg'=> '密码不正确'
                ];
            }
        }else{
            $response = [
                'errno'=> 400004,
                'msg'=> '用户不存在'
            ];
        }
        return $response;
    }

    //获取用户列表
    function userlist(){
        $list=User::all();
        echo '<pre>';print_r($list->toArray());echo '</pre>';
    }

}
