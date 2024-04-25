<?php

namespace app\api\controller;

use app\api\controller\Controller;
use app\api\model\Agent;
use app\api\model\Financeorder;
use app\api\model\Teamlevel;
use app\api\model\User as ModelUser;
use app\api\model\Usercategory;
use app\api\model\Usermoneylog;
use app\api\model\Usertotal;
use app\common\controller\Api;
use app\common\library\Ems;
use app\common\library\Sms;
use fast\Random;
use think\cache\driver\Redis;
use think\Config;
use think\Log;
use think\Validate;

/**
 * 会员接口
 */
class User extends Controller
{
    protected $noNeedLogin = ['login', 'mobilelogin', 'register', 'resetpwd', 'changeemail', 'changemobile', 'third'];
    protected $noNeedRight = '*';

    /**
     * 会员登录
     *
     * @ApiMethod (POST)
     * @param string $mobile  手机号
     * @param string $password 密码
     */
    public function login()
    {
        $ip = get_real_ip();
        $redis = new Redis();
        $redis->handler()->select(0);
        $ban_ip = $redis->handler()->sIsMember("ban_ip:set", $ip);
        if ($ban_ip) {
            Log::mylog('Illegal IP has been restricted from logging in', $ip, 'ffloginip');
            $this->error(__('Illegal IP has been restricted from logging in'));
        }
        $mobile = $this->request->post('mobile');
        $password = $this->request->post('password');
        if (!$mobile) {
            $this->error(__('Please enter the phone number'));
        }
        if (!$password) {
            $this->error(__('Please enter the password'));
        }
        $model = new \app\api\model\User();
        $ret = $model->login($this->request->param(), $this->request->header());
        if ($ret) {
            if ($ret['code'] == 5) {
                $this->error(__($ret['msg']), '', 0, '', [], 5);
            }
            if ($ret['code'] == 0) {
                $this->error(__($ret['msg']));
            }
            $data = ['userinfo' => $ret['data']];
            $this->success(__('login successfully'), $data);
        } else {
            $this->error(__('login failure'));
        }
    }

    /**
     * 验证身份
     */
    public function checkauthentication()
    {
        $mobile = $this->request->post('mobile');
        $code = $this->request->post('code');
        $password = $this->request->post('password');
        if (!$code) {
            $this->error("The OTP cannot be empty");
        }
        if (!$mobile || !$code || !$password) {
            $this->error(__('parameter error'));
        }
        $is_reg = (new ModelUser)->where('mobile', $mobile)->find();
        $check_pass = (new ModelUser)->getEncryptPassword($password, $is_reg['salt']);
        //密码检测
        if ($is_reg['password'] != $check_pass) {
            $this->error(__('wrong password'));
        }
        //检测验证码
        $ret = Sms::authentication($mobile, $code);
        if (!$ret) {
            $this->error(__('OTP is incorrect'));
        }
        //更改状态
        (new ModelUser)->where('mobile', $mobile)->update(['need_sms' => 0]);
        $this->success(__('verify successfully'));
    }

    /**
     * 注册会员
     *
     * @ApiMethod (POST)
     * @param string $password 密码
     * @param string $re_password 重复密码
     * @param string $mobile   手机号
     * @param string $code     验证码
     * @param string $invite_code     邀请码
     */
    public function register()
    {
        $ip = get_real_ip();
        $redis = new Redis();
        $redis->handler()->select(0);
        $ban_ip = $redis->handler()->sIsMember("ban_ip:set", $ip);
        if ($ban_ip) {
            Log::mylog('Illegal IP has been restricted from logging in', $ip, 'ffregip');
            $this->error(__('Illegal IP has been restricted from logging in'));
        }
        $param = $this->request->post();
        $mobile = $this->request->post('mobile'); //手机号
        $password = $this->request->post('password'); //密码
        $re_password = $this->request->post('re_password'); //重复密码
        $code = $this->request->post('code'); //验证码
        if (!$mobile || !$password || !$code || !$re_password) {
            $this->error(__('parameter error'));
        }
        $mobile_check = substr($mobile, 0, 1);
        if ($mobile_check == 0) {
            $this->error(__('The first digit of the phone number cannot be 0'));
        }
        //密码是否一致
        if ($password != $re_password) {
            $this->error(__('Inconsistent passwords'));
        }
        //检测验证码
        $ret = Sms::checkcode($mobile, $code);
        if (!$ret) {
            $this->error(__('OTP is incorrect'));
        }
        $agent_id = 0;
        $agent_code = $this->request->post('agent', ''); //代码编号
        if ($agent_code) {
            $aid = (new Agent())->getIdByCode($agent_code);
            if ($aid) {
                $agent_id = $aid;
            }
        } else {
            if (Config::get("host.auto_assign_agent")) {
                $agent_id = (new Agent())->getAssignAgentId();
            }
        }

        $ret = (new ModelUser())->register($param, $agent_id);
        if ($ret) {
            if ($ret['code'] == 0) {
                $this->error(__($ret['msg']));
            }
            $this->success(__('registered successfully'));
        } else {
            $this->error(__('fail to register'));
        }
    }

     /**
     * 注册会员-自动登录
     *
     * @ApiMethod (POST)
     * @param string $password 密码
     * @param string $re_password 重复密码
     * @param string $mobile   手机号
     * @param string $code     验证码
     * @param string $invite_code     邀请码
     */
    public function registernew()
    {
        $ip = get_real_ip();
        $redis = new Redis();
        $redis->handler()->select(0);
        $ban_ip = $redis->handler()->sIsMember("ban_ip:set", $ip);
        if ($ban_ip) {
            Log::mylog('Illegal IP has been restricted from logging in', $ip, 'ffregip');
            $this->error(__('Illegal IP has been restricted from logging in'));
        }
        $param = $this->request->post();
        $mobile = $this->request->post('mobile'); //手机号
        $password = $this->request->post('password'); //密码
        $re_password = $this->request->post('re_password'); //重复密码
//        $code = $this->request->post('code'); //验证码
        if (!$mobile) {
            $this->error(__('Please enter the phone number'));
        }
        if (!$password || !$re_password) {
            $this->error(__('Please enter the password'));
        }
//        if (!$code) {
//            $this->error(__('Please enter a one-time verification code（OTP）'));
//        }

        $mobile = ltrim($mobile,'0');
        if(strlen($mobile) <= 9){
            $this->error(__('Mohon masukan nomor ponselyang benar'));
        }
        //密码是否一致
        if ($password != $re_password) {
            $this->error(__('Inconsistent passwords'));
        }
        //检测验证码
//        $ret = Sms::checkcode($mobile, $code);
//        if (!$ret) {
//            $this->error(__('OTP is incorrect'));
//        }
        $agent_id = 0;
        $agent_code = $this->request->post('agent', ''); //代码编号
        if ($agent_code) {
            $aid = (new Agent())->getIdByCode($agent_code);
            if ($aid) {
                $agent_id = $aid;
            }
        } else {
            if (Config::get("host.auto_assign_agent")) {
                $agent_id = (new Agent())->getAssignAgentId();
            }
        }

        $ret = (new ModelUser())->registernew($param, $agent_id);
        if ($ret) {
            if ($ret['code'] == 0) {
                $this->error(__($ret['msg']));
            }
            $data = ['userinfo' => $ret['data']];
            $this->success(__('registered successfully'),$data);
        } else {
            $this->error(__('fail to register'));
        }
    }


    /**
     * 退出登录
     * @ApiMethod (POST)
     */
    public function logout()
    {
        $this->verifyUser();
        (new ModelUser())->logout($this->gettoken());
        $this->success(__('Logout successful'));
    }

    /**
     * 注销账号
     * @ApiMethod (POST)
     */
    public function deleteacount()
    {
        $this->verifyUser();
        (new ModelUser())->where(['id'=>$this->uid])->update(['status'=>2]);
        (new ModelUser())->logout($this->gettoken());
        $this->success(__('Logout successful'));
    }

    /**
     * 用户信息
     */
    public function getuserinfo()
    {
        $this->verifyUser();
        $data = $this->userInfo;
        if (empty($data['withdraw_password'])) {
            $data['is_set_withdraw_password'] = 0;
        } else {
            $data['is_set_withdraw_password'] = 1;
        }
        // unset($data['id']);
        unset($data['token']);
        unset($data['password']);
        unset($data['withdraw_password']);
        unset($data['salt']);
        $user_id = $this->uid;
        (new Usertotal())->setLogin($user_id);
        $usertotal = (new Usertotal())->where('user_id', $user_id)->find();
        $total_commission = $usertotal['total_commission'];
        $group_buying_commission = $usertotal['group_buying_commission'];
        $head_of_the_reward = $usertotal['head_of_the_reward'];
        $invite_commission = $usertotal['invite_commission'];
        $exchangemoney = $usertotal['exchangemoney'];
        //总收入
        $data['total_earning'] = bcadd(($total_commission + $group_buying_commission), ($head_of_the_reward + $invite_commission + $exchangemoney), 2);
        //今日收入
        $usercategory = (new Usercategory())->where('user_id', $user_id)->where('date', date('Y-m-d', time()))->find();
        $total_commissions = !empty($usercategory['total_commission']) ? $usercategory['total_commission'] : 0;
        $group_buying_commissions = !empty($usercategory['group_buying_commission']) ? $usercategory['group_buying_commission'] : 0;
        $head_of_the_rewards = !empty($usercategory['head_of_the_reward']) ? $usercategory['head_of_the_reward'] : 0;
        $invite_commissions = !empty($usercategory['invite_commission']) ? $usercategory['invite_commission'] : 0;
        $exchangemoneys = !empty($usercategory['exchangemoney']) ? $usercategory['exchangemoney'] : 0;
        $data['today_earning'] = bcadd(($total_commissions + $group_buying_commissions), ($head_of_the_rewards + $invite_commissions + $exchangemoneys), 2);
        //图片转换
        $redis = new Redis();
        $redis->handler()->select(6);
        $experience_time = $redis->handler()->zScore("zclc:sendlist", $user_id);
        if ($experience_time) {
            $data['experience_time'] = $experience_time;
        } else {
            $data['experience_time'] = 0;
        }
        //是否有等级升级记录
        $data['level_log'] = (new ModelUser())->levellog($this->uid);
        // $data['avatar'] = format_image($data["avatar"]);
        //个人中心已读，未读
        $data['record_read'] = !empty($data['record_read']) ? $data['record_read'] : 1;
        $data['earnings_read'] = !empty($data['earnings_read']) ? $data['earnings_read'] : 1;
        $this->success(__('The request is successful'), $data);
    }
    /**
     * 修改用户个人信息
     *
     * @ApiMethod (POST)
     * @param string $avatar   头像地址
     * @param string $username 用户名
     * @param string $nickname 昵称
     * @param string $bio      个人简介
     */
    public function profile()
    {
        $this->verifyUser();
        $nickname = $this->request->post('nickname');
        $exists = (new ModelUser())->where('nickname', $nickname)->find();
        if ($exists) {
            $this->error(__('Nickname already exists'));
        }
        // $nickname = substr($nickname, 0, 3) . '****' . substr($nickname, -2);
//        $avatar = $this->request->post('avatar');
        $upd = (new ModelUser())->where('id', $this->uid)->update(['nickname' => $nickname]);
        if (!$upd) {
            $this->error(__('operation failure'));
        }
        //更新用户信息
        $this->updateCacheUser();
        $this->success(__('operate successfully'));
    }

    /**
     * 重置密码
     *
     * @ApiMethod (POST)
     * @param string $mobile      手机号
     * @param string $password 新密码
     * @param string $re_password 重复密码
     * @param string $captcha     验证码
     */
    public function resetpwd()
    {
        $param = $this->request->post();
        $mobile = $this->request->post('mobile'); //手机号
        $password = $this->request->post('password'); //密码
        $re_password = $this->request->post('re_password'); //重复密码
//        $code = $this->request->post('code'); //验证码
        $oldpassword = $this->request->post('oldpassword'); //原始密码
        if (!$mobile || !$password || !$oldpassword || !$re_password) {
            $this->error(__('parameter error'));
        }
        //密码是否一致
        if ($password != $re_password) {
            $this->error(__('Inconsistent passwords'));
        }
//        //检测验证码
//        $ret = Sms::resetcheckcode($mobile, $code);
//        if (!$ret) {
//            $this->error(__('OTP is incorrect'));
//        }
        $is_reg = (new ModelUser)->where('mobile', $mobile)->find();
        $check_pass = (new ModelUser)->getEncryptPassword($oldpassword, $is_reg['salt']);
        //密码检测
        if ($is_reg['password'] != $check_pass) {
            $this->error(__('wrong password'));
        }
        $ret = (new ModelUser())->resetpwd($param);
        $this->success(__('Password reset succeeded'));
    }

    public function userInfo()
    {
        $this->verifyUser();
        $userInfo = $this->userInfo;
        $level = (new Teamlevel())->detail($userInfo['buy_level']);
        $userInfo['buy_level_name'] = $level['name']??'';
        $userInfo['buy_level_image'] = !empty($level['image'])?format_image($level['image']):'';
        if (empty($userInfo['withdraw_password'])) {
            $userInfo['is_set_withdraw_password'] = 0;
        } else {
            $userInfo['is_set_withdraw_password'] = 1;
        }
        $user_id = $this->uid;
        (new Usertotal())->setLogin($user_id);
        // unset($data['id']);
        unset($userInfo['token']);
        unset($userInfo['password']);
        unset($userInfo['withdraw_password']);
        unset($userInfo['salt']);
        unset($userInfo['is_robot']);
        $preg = "/^http(s)?:\\/\\/.+/";
        if (!preg_match($preg, $userInfo['avatar'])) {
            $userInfo['avatar'] = format_image($userInfo['avatar']);
        }
        $userInfo['current_money'] = (new \app\api\model\Financeorder())->where(['user_id' => $this->uid, 'status' => 1,'is_robot'=>0])->sum('amount');
        $usertotal = (new Usertotal())->where('user_id', $this->uid)->find();
        $total_commission = $usertotal['total_commission'];
        $group_buying_commission = $usertotal['group_buying_commission'];
        $head_of_the_reward = $usertotal['head_of_the_reward'];
        $invite_commission = $usertotal['invite_commission'];
        $exchangemoney = $usertotal['exchangemoney'];
        $promotion_award = $usertotal['promotion_award'];
        $cash_award = $usertotal['cash_award'];
        $salary = $usertotal['salary'];
        $crowdfunding_income = $usertotal['crowdfunding_income'];
        $listTypeReward = (new Usermoneylog())->listTypeReward(1,20,'english',$user_id,[27,28,29]);
        $accumulated_rewards = $listTypeReward['total'];
        $userInfo['total_earning'] = bcadd(($total_commission + $group_buying_commission), ($head_of_the_reward + $invite_commission + $exchangemoney + $promotion_award
            + $cash_award + $salary + $crowdfunding_income + $accumulated_rewards), 0);
        $userInfo['money'] = bcadd($userInfo['money'],0,0);
        $this->success('', $userInfo);
    }

    public function checkUserZc()
    {
        $this->verifyUser();
        $redis = new Redis();
        $redis->handler()->select(0);
        $exist = $redis->handler()->SISMEMBER("zclc:submitZc", $this->uid);
        if ($exist) {
            $return = 1;
        } else {
            $return = 0;
        }
        $this->success(__('The request is successful'), $return);
    }

    public function userSubmitZc()
    {
        $this->verifyUser();
        $redis = new Redis();
        $redis->handler()->select(0);
        $redis->handler()->SADD("zclc:submitZc", $this->uid);
        $this->success(__('The request is successful'));
    }

    public function search()
    {
        $mobile = $this->request->param('tel'); //手机号
        $redis = new Redis();
        $redis->handler()->select(0);
        $ip = get_real_ip();
        if(!$mobile){
            return true;
        }
        $searchmobile = $redis->handler()->get("zclc:searchmobile:{$ip}");
        if($searchmobile){
            $this->error(__('Permintaan terlalu sering! Silakan coba lagi setelah 30 detik!'));
        }else{
            $redis->handler()->set("zclc:searchmobile:{$ip}",1,30);
        }
        $mobile = ltrim($mobile,'0');
        $userinfo = (new \app\api\model\User())->where(['mobile'=>$mobile])->find();
        if(!$userinfo){
            $this->error('fail');
        }
        $start = strtotime('2024-2-11 00:00:00');
        $end = strtotime('2024-03-31 23:59:59');
        $user_arr = (new \app\api\model\User())->where(['sid'=>$userinfo['id'],'createtime'=>['between',[$start,$end]]])->column('id');
        $count_user = count($user_arr);
        if($count_user < 5){
            $this->error("Jumlah downline saat ini : {$count_user} orang, tidak memenuhi kriteria. ");
        }
        $total_money = (new Financeorder())->where(['user_id'=>['in',$user_arr],'popularize'=>['<>',2]])->sum('amount');
        if($total_money < 1000000){
            $this->error("Jumlah investasi downline saat ini: {$total_money}, tidak memenuhi kriteria.");
        }
        $this->success(__("Silahkan hubungi CS saat memenuhi persyaratan"));
    }
}
