<?php

namespace app\pay\controller;

use app\api\controller\Controller;
use app\pay\model\Klikpay as ModelKlikpay;
use think\Log;

/**
 * Wowpay
 */
class Klikpay extends Controller
{

    /**
     * 代收回调
     */
    public function paynotify()
    {
        $data = $_POST;
        Log::mylog('支付回调_data', $data, 'klikpayhd');
        (new ModelKlikpay())->paynotify($data);
        exit('SUCCESS');
    }

    /**
     * 代付回调
     */
    public function paydainotify()
    {
        $data = $_POST;
        Log::mylog('提现回调_data', $data, 'klikpaydfhd');
        (new ModelKlikpay())->paydainotify($data);
        exit('SUCCESS');
    } 
}
