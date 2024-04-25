<?php

namespace app\pay\model;

use function EasyWeChat\Kernel\Support\get_client_ip;

use app\api\model\Report;
use app\api\model\Usercash;
use app\api\model\Userrecharge;
use app\api\model\Usertotal;
use think\Cache;
use think\Model;
use think\Db;
use think\Log;
use think\Exception;


class Coverpay extends Model
{
    //代付提单url(提现)
    public $dai_url = 'https://apis2-id.coverpay.cc/payment_dfpay_add.html';
    //代收提交url(充值)
    public $pay_url = 'https://apis2-id.coverpay.cc/pay_counter.html';
    //代付回调(提现)
    public $notify_dai = 'https://api.rothpro.id/pay/coverpay/paydainotify';
    //代收回调(充值)
    public $notify_pay = 'https://api.rothpro.id/pay/coverpay/paynotify';
    //代收秘钥
    public $key = "XJttFdA5S7e0DYqSN4W54Q3jLT8=";
    //代付秘钥
    public function pay($order_id, $price, $userinfo, $channel_info)
    {
        if($channel_info['busi_code'] == 'qris'){
            $type = 'qris';
            $code = 'QRIS';
        }else{
            $type = '';
            $code = '';
        }
        $param = [
            'memberId' => $channel_info['merchantid'],
            'type' => $type,
            'orderId' => $order_id,
            "dstCode" => $code,
            'amount' => $price,
            "dateTime"    => date("Y-m-d H:i:s"),
            "name"        => 'xinmiti',
            "phone"       => '088291448849',
            "email"       => 'gotocode@gmail.com',
            'notifyUrl' => $this->notify_pay,
        ];
        $sign = $this->sendSign($param, $this->key);
        $param['sign'] = $sign;
        Log::mylog("提交参数", $param, "coverpay");
        $return_json = $this->httpPost($this->pay_url, $param);
        Log::mylog("返回参数", $return_json, "coverpay");
        $return_array = json_decode($return_json, true);
        if ($return_array['status'] == "success") {
            $return_array = [
                'code' => 1,
                'payurl' => !empty(urlencode($return_array['payUrl'])) ? urlencode($return_array['payUrl']) : '',
            ];
        } else {
            $return_array = [
                'code' => 0,
                'msg' => $return_array['msg'],
            ];
        }
        return $return_array;
    }

    /**
     * 代收回调
     */
    public function paynotify($params)
    {
        if ($params['status'] == 'success'&&$params['refCode'] == '1') {
            $sign = $params['sign'];
            unset($params['sign']);
            $check = $this->sendSign($params, $this->key);
            if ($sign != $check) {
                Log::mylog('验签失败', $params, 'coverpayhd');
                return false;
            }
            $order_id = $params['orderId']; //商户订单号
            $order_num = $params['platOrderId']; //平台订单号
            $amount = $params['amount']; //支付金额
            (new Paycommon())->paynotify($order_id, $order_num, $amount, 'coverpayhd');
        } else {
            //更新订单信息
            $upd = [
                'status' => 2,
                'order_id' => $params['orderId'],
                'updatetime' => time(),
            ];
            (new Userrecharge())->where('order_id', $params['orderId'])->where('status', 0)->update($upd);
            Log::mylog('支付回调失败！', $params, 'coverpayhd');
        }
    }

    /**
     *提现 
     */
    public function withdraw($data, $channel)
    {
        $bankname = '';
        $type = 'bankcard';
        if($data['bankname'] == 'Bank Permata'){
            $bankname = 'PERMATA';
        }

        if($data['bankname'] == 'Bank BRI'){
            $bankname = 'BRI';
        }

        if($data['bankname'] == 'Bank Mandiri'){
            $bankname = 'MANDIRI';
        }

        if($data['bankname'] == 'Bank BNI'){
            $bankname = 'BNI';
        }

        if($data['bankname'] == 'Bank Danamon'){
            $bankname = 'DANAMON';
        }

        if($data['bankname'] == 'Bank BCA'){
            $bankname = 'BCA';
        }

        if($data['bankname'] == 'BII Maybank'){
            $bankname = 'MAYBANK';
        }

        if($data['bankname'] == 'Bank Panin'){
            $bankname = 'PANIN';
        }

        if($data['bankname'] == 'CIMB Niaga'){
            $bankname = 'CIMB';
        }

        if($data['bankname'] == 'Bank UOB INDONESIA'){
            $bankname = 'UOB';
        }
        if($data['bankname'] == 'Bank OCBC NISP'){
            $bankname = 'OCBC_NISP';
        }
        if($data['bankname'] == 'CITIBANK'){
            $bankname = 'CITIBANK';
        }
        if($data['bankname'] == 'Bank ARTHA GRAHA'){
            $bankname = 'ARTHA_GRAHA';
        }
        if($data['bankname'] == 'Bank DBS'){
            $bankname = 'DBS_INDONESIA';
        }
        if($data['bankname'] == 'Standard Chartered'){
            $bankname = 'STANDARD_CHARTERED';
        }
        if($data['bankname'] == 'Bank CAPITAL'){
            $bankname = 'CAPITAL_INDONESIA';
        }
        if($data['bankname'] == 'ANZ Indonesia'){
            $bankname = 'ANZ_INDONESIA';
        }
        if($data['bankname'] == 'Bank OF CHINA'){
            $bankname = 'OF_CHINA';
        }
        if($data['bankname'] == 'Bank HSBC'){
            $bankname = 'HSBC_INDONESIA';
        }
        if($data['bankname'] == 'Bank MAYAPADA'){
            $bankname = 'MAYAPADA';
        }
        if($data['bankname'] == 'Bank Jawa Barat'){
            $bankname = 'JABAR';
        }
        if($data['bankname'] == 'Bank JATENG'){
            $bankname = 'BPD_JAWA_TENGAH';
        }
        if($data['bankname'] == 'Bank Jatim'){
            $bankname = 'JATIM';
        }
        if($data['bankname'] == 'Bank Aceh Syariah'){
            $bankname = 'ACEH_SYARIAH';
        }

        if($data['bankname'] == 'OVO'){
            $bankname = 'OVO';
            $type = 'ewallet';
        }
        if($data['bankname'] == 'Dana'){
            $bankname = 'DANA';
            $type = 'ewallet';
        }

        if($data['bankname'] == 'ShopeePay'){
            $bankname = 'SHOPEEPAY';
            $type = 'ewallet';
        }
        if(empty($bankname)){
            return ['status'=>'FAIL','msg'=>'不支持的银行'];
        }
        $param = array(
            'memberId' => $channel['merchantid'],
            'orderId' => $data['order_id'],
            "type" => $type,
            'amount' => $data['trueprice'],
            'dstCode' => $bankname,
            'name' => $data['username'], //收款姓名
            'phone' => trim($data['phone'],0),
            'email' => 'test@gmail.com',
            'account' => $data['bankcard'], //收款账号
            'notifyUrl' => $this->notify_dai,
            "address"  => 'gotocode@gmail.com',
        );
        $sign = $this->sendSign($param, $this->key);
        $param['sign'] = $sign;
        Log::mylog('提现提交参数', $param, 'coverpaydf');
        $return_json = $this->httpPost($this->dai_url, $param);
        Log::mylog($return_json, 'coverpaydf', 'coverpaydf');
        return $return_json;
    }

    /**
     * 提现回调
     */
    public function paydainotify($params)
    {
        $sign = $params['sign'];
        unset($params['sign']);
        $check = $this->sendSign($params, $this->key);
        if ($sign != $check) {
            Log::mylog('验签失败', $params, 'coverpaydfhd');
            return false;
        }
        $usercash = new Usercash();
        if ($params['status'] != 'success'||$params['refCode'] != '1') {
            try {
                $r = $usercash->where('order_id', $params['orderId'])->find()->toArray();
                if ($r['status'] == 5) {
                    return false;
                }
                $upd = [
                    'status'  => 4, //新增状态 '代付失败'
                    'updatetime'  => time(),
                ];
                $res = $usercash->where('id', $r['id'])->update($upd);
                if (!$res) {
                    return false;
                }
                Log::mylog('代付失败,订单号:' . $params['orderId'], 'coverpaydfhd');
            } catch (Exception $e) {
                Log::mylog('代付失败,订单号:' . $params['orderId'], $e, 'coverpaydfhd');
            }
        } else {
            try {
                $r = $usercash->where('order_id', $params['orderId'])->find()->toArray();
                $upd = [
                    'order_no'  => $params['platOrderId'],
                    'updatetime'  => time(),
                    'status' => 3, //新增状态 '代付成功'
                    'paytime' => time(),
                ];
                $res = $usercash->where('status', 'lt', 3)->where('id', $r['id'])->update($upd);
                if (!$res) {
                    return false;
                }
                //统计当日提现金额
                $report = new Report();
                $report->where('date', date("Y-m-d", time()))->setInc('cash', $r['price']);
                //用户提现金额
                (new Usertotal())->where('user_id', $r['user_id'])->setInc('total_withdrawals', $r['price']);
                (new Paycommon())->withdrawa($r['user_id'],$r['id']);
                Log::mylog('提现成功', $params, 'coverpaydfhd');
            } catch (Exception $e) {
                Log::mylog('代付失败,订单号:' . $params['orderId'], $e, 'coverpaydfhd');
            }
        }
    }

    function sendSign($params, $appsecret)
    {
        ksort($params);
        $signStr = '';
        foreach ($params as $key => $val) {
            if ($val != null) {
                $signStr .= $key . '=' . $val . '&';
            }
        }
        $signStr .= 'key=' . $appsecret;
        // echo $signStr;
        $sign = strtoupper(md5($signStr));
        return $sign;
    }

    function httpPost($url, $data)
    {

        $postData = http_build_query($data); //重要！！！
        $ch = curl_init();
        // 设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        $header = array();
        $header[] = 'User-Agent: ozilla/5.0 (X11; Linux i686) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.186 Safari/535.1';
        $header[] = 'Accept-Charset: UTF-8,utf-8;q=0.7,*;q=0.3';
        $header[] = 'Content-Type:application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    // 对证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);    // 从证书中检查SSL加密算法是否存在
        //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // 使用自动跳转
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);       // 自动设置Referer
        curl_setopt($ch, CURLOPT_POST, 1);      // 发送一个 常规的Post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    // Post提交的数据包
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);      // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_HEADER, 0);        // 显示返回的Header区域内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //获取的信息以文件流的形式返回

        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "Errno" . curl_error($ch);   // 捕抓异常
        }
        curl_close($ch);    // 关闭CURL
        return $output;
    }

    public function curl($postdata)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->pay_url); //支付请求地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json; charset=utf-8',
            )
        );
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function curls($postdata)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://payment.weglobalpayment.com/pay/transfer"); //支付请求地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 代收回调
     */
    public function paynotifytest($params)
    {
        if ($params['tradeResult'] == 1) {
            //$sign = $params['sign'];
            // unset($params['sign']);
            // unset($params['signType']);
            // $check = $this->generateSign($params, $this->key);
            // if ($sign != $check) {
            //     Log::mylog('验签失败', $params, 'ppayhd');
            //     return false;
            // }
            $order_id = $params['merchantOrderId']; //商户订单号
            $order_num = $params['orderId']; //平台订单号
            $amount = $params['amount']; //支付金额
            (new Paycommon())->paynotify($order_id, $order_num, $amount, 'ppayhd');
        } else {
            //更新订单信息
            $upd = [
                'status' => 2,
                'order_id' => $params['mchOrderNo'],
                'updatetime' => time(),
            ];
            (new Userrecharge())->where('order_id', $params['mchOrderNo'])->where('status', 0)->update($upd);
            Log::mylog('支付回调失败！', $params, 'ppayhd');
        }
    }
}
