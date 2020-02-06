<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function alipay()
    {
        $ali_gateway = 'https://openapi.alipaydev.com/gateway.do';  //支付网关
        // 公共请求参数
        $appid = '2016100100643258';
        $method = 'alipay.trade.page.pay';
        $charset = 'utf-8';
        $signtype = 'RSA2';
        $sign = '';
        $timestamp = date('Y-m-d H:i:s');
        $version = '1.0';
        $return_url = 'http://1905api.hcws.vip/test/alipay/return';       // 支付宝同步通知
        $notify_url = 'http://1905api.hcws.vip/test/alipay/notify';        // 支付宝异步通知地址
        $biz_content = '';
        // 请求参数
        $out_trade_no = time() . rand(1111,9999);       //商户订单号
        $product_code = 'FAST_INSTANT_TRADE_PAY';
        $total_amount = 0.01;
        $subject = '测试订单' . $out_trade_no;
        $request_param = [
            'out_trade_no'  => $out_trade_no,
            'product_code'  => $product_code,
            'total_amount'  => $total_amount,
            'subject'       => $subject
        ];
        $param = [
            'app_id'        => $appid,
            'method'        => $method,
            'charset'       => $charset,
            'sign_type'     => $signtype,
            'timestamp'     => $timestamp,
            'version'       => $version,
            'notify_url'    => $notify_url,
            'return_url'    => $return_url,
            'biz_content'   => json_encode($request_param)
        ];
        //echo '<pre>';print_r($param);echo '</pre>';
        // 字典序排序
        ksort($param);
        //echo '<pre>';print_r($param);echo '</pre>';
        // 2 拼接 key1=value1&key2=value2...
        $str = "";
        foreach($param as $k=>$v)
        {
            $str .= $k . '=' . $v . '&';
        }
        //echo 'str: '.$str;echo '</br>';
        $str = rtrim($str,'&');
        //echo 'str: '.$str;echo '</br>';echo '<hr>';
        // 3 计算签名   https://docs.open.alipay.com/291/106118
        $key = storage_path('keys/app_priv');
        $priKey = file_get_contents($key);
        $res = openssl_get_privatekey($priKey);
        //var_dump($res);echo '</br>';
        openssl_sign($str, $sign, $res, OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
        $param['sign'] = $sign;
        // 4 urlencode
        $param_str = '?';
        foreach($param as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $param_str = rtrim($param_str,'&');
        $url = $ali_gateway . $param_str;
        //发送GET请求
        //echo $url;die;
        header("Location:".$url);
    }

    public function sign1(){

        echo '<pre>';print_r($_GET);echo'<pre>';

        $sign=$_GET['sign'];
        unset($_GET['sign']);

        ksort($_GET);
        echo'<pre>';print_r($_GET);echo'<pre>';

        $str='';
        foreach($_GET as $k=>$v){
            $str .=$k.'='.$v.'&';
        }
        $str=rtrim($str,'&');
        echo $str;echo'<br>';echo'<hr>';

        $pub_key=file_get_contents(storage_path('keys/pubkey'));
        $status=openssl_verify($str,base64_decode($sign),$pub_key,OPENSSL_ALGO_SHA256);
        var_dump($status);
        if($status){
            echo 'success';
        }else{
            echo '验证失败';
        }
    }

    public function sign2(){
        $sign_token='asdfgh';
        echo '<pre>';print_r($_GET);echo '<pre>';
        $sign1=$_GET['sign'];
        echo '发送端的签名：'.$sign1;echo '<br>';
        unset($_GET['sign']);
        ksort($_GET);
        echo '<pre>';print_r($_GET);echo'<pre>';
        $str='';
        foreach($_GET as $k=>$v){
            $str .=$k.'='.$v.'&';
        }
        $str=rtrim($str,'&');
        echo $str;echo'<br>';echo'<hr>';

//        计算签名:

        $sign2=md5($str.$sign_token);
        echo '接收计算端的签名：'.$sign2;

        if($sign1 == $sign2){
            echo'success';
        }else{
            echo'失败';
        }
    }
}