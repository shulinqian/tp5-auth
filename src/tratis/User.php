<?php

namespace thinkweb\auth\tratis;

use thinkweb\auth\AuthException;

trait User{
    //设置字段名称，兼容不同表
    protected $nike_name_key = 'nike_name'; //昵称
    protected $login_fail_key = 'login_fail'; //登录失败次数
    protected $login_token_key = 'login_token'; //登录token
    protected $password_key = 'password'; //密码

    /**
     * @var array 登录会员保存表
     */
    static public $loginUser = [];
    protected $userType = 'user';
    protected $salt = 'salt';
    protected $max_login_fail_nums = 5;

    public function loginByUsername($post){
        return $this->loginByAccount($post, 'username');
    }

    public function loginByEmail($post){
        return $this->loginByAccount($post, 'email');
    }

    public function loginByMobile($post){
        return $this->loginByAccount($post, 'mobile');
    }

    protected function loginByAccount($post, $loginType){
        if(!$post){
            throw new AuthException('参数错误');
        }

        $password = isset($post[$this->password_key]) ? $post[$this->password_key] : null;
        $username = isset($post[$loginType]) ? $post[$loginType] : null;

        $user = $this->where($loginType, $username)->find();
        if(!$user){
            throw new AuthException('账号不存在');
        }
        //检测登录次数
        if($user[$this->login_fail_key] > $this->max_login_fail_nums){
            return false;
        }
        $hash = $this->hash($password);
        if($hash !== $user[$this->password_key]){
            $this->loginFail($user);
            throw new AuthException('账号或密码错误');
        }
        //更新登录信息
        $this->updateLogin($user);

        //设置登录状态
        $this->loginByUser($user);
        return $user;
    }

    protected function hash($value, $type = 'md5'){
        return md5(md5($value) . $this->userType . $this->salt);
    }

    protected function loginFail($user){
        if(!$user){
            return false;
        }
        return $user->setInc($this->login_fail_key);
    }

    protected function updateLogin($user){
        $data = array(
            $this->login_fail_key => 0,//重置登录失败
            'last_login_time' => time(),
            'last_login_ip' => request()->ip(1),
        );
        return $user->save($data);
    }

    /**
     * 签名类,可将登录信息进行签名，返回给第三方接口，第三方接口获取token后，以后获取数据根据token验证。
     * @param $data
     * @return string
     */
    protected function createToken($data) {
        //数据类型检测
        if(!is_array($data)){
            $data = (array)$data;
        }
        ksort($data); //排序
        $code = http_build_query($data); //url编码并生成query字符串
        $token = sha1($code); //生成签名
        return $token;
    }

    public function loginByUser($user){
        if(!$user || !isset($user['id']) || !$user['id']){
            return false;
        }
        $auth = array(
            'id' => $user->id,
            'username' => $user->username,
            'nike_name' => $user[$this->nike_name_key],
            'last_login_time' => $user->last_login_time,
        );
        //设置登录
        $key = $this->getSessionKey();
        $this->session($key, $auth);
        //设置token
        $this->setToken($user, $auth);

        return static::$loginUser[$user['id']] = $user;
    }

    protected function setToken($user, $auth){
        //设置token
        $authToken = $this->createToken($auth);
        $user->save([$this->login_token => $authToken]);
    }

    public function getUserByToken($authToken){
        return $this->where([$this->login_token => $authToken])->find();
    }

    public function isLogin(){
        $key = $this->getSessionKey();
        if($user = $this->session($key)){
            return $user;
        }
    }

    public function getLoginUser(){
        $loginUser = $this->isLogin();
        if(!$loginUser){
            return [];
        }
        $loginUsers = static::$loginUser;
        if($loginUsers && isset($loginUsers[$loginUser['id']])){
            return $loginUsers[$loginUser['id']];
        }
        $user = $this->find($loginUser['id']);
        return $loginUsers[$loginUser['id']] = $user;
    }

    public function logout(){
        $key = $this->getSessionKey();
        $this->session($key, null);
        return true;
    }

    protected function getSessionKey(){
        return 'user_auth_' . $this->userType;
    }

    protected function session($name, $value = '', $prefix = null){
        return session($name, $value, $prefix);
    }
}