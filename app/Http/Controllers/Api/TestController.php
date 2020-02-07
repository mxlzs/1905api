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


//    public function brush(){
//        $data=[
//          'username'=>'zhangsan',
//            'email'=>'zhangsan@qq.com',
//            'amount'=>1000
//        ];
//        echo json_encode($data);
//        //获取用户标识
//        $token = $_SERVER['HTTP_TOKEN'];
//        //当前url
//        $request_uri=$_SERVER['REQUEST_URI'];
//        $url_hash=md5($token . $request_uri);
//        //echo 'url_hash: ' . $url_hash;echo '</br>';
//        $key='count:url:'.$url_hash;
//        //echo 'key:' .$key;echo '</br>';
//        //检查 次数是否已经超过限制
//        $count=Redis::get($key);
//        echo "当前接口访问次数为: ".$count;echo '<br>';
//        if($count>=3){
//            $time=5;
//            echo "请勿频繁请求接口, $time 秒后重试";
//            Redis::expire($key,$time);
//            die;
//        }
//        //访问数+1
//        $count=Redis::incr($key);
//        echo 'count: '.$count;
//    }
    public function brush(){
        //获取用户标识
        $token = $_SERVER['HTTP_TOKEN'];
        //当前url
        $request_uri=$_SERVER['REQUEST_URI'];
        $url_hash=md5($token . $request_uri);
        //echo 'url_hash: ' . $url_hash;echo '</br>';
        $key='count:url:'.$url_hash;
        //echo 'key:' .$key;echo '</br>';

        //检查 次数是否已经超过限制
        $count=Redis::get($key);
        echo "当前接口访问次数为: ".$count;echo '<br>';

        if($count>=5){
            $time=10;
            echo "请勿频繁请求接口, $time 秒后重试";
            Redis::expire($key,$time);
            die;
        }
        //访问数+1
        $count=Redis::incr($key);
        echo 'count: '.$count;
    }

    public function md5(){
        $data="Hello world"; //要发送的数据
        $key="1905"; //计算签名key

        //计算签名 MD5($data.$key)
        $signature=md5($data.$key);
        echo "待发送的数据:".$data;echo "</br>";
        echo "签名:".$signature;echo "</br>";

        //发送数据
        $url="http://1905admin.com/check?data=".$data . '&signature='.$signature;
        echo $url;echo "<hr>";

        $response=file_get_contents($url);
        echo $response;
    }

}
