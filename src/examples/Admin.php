<?php
namespace thinkweb\auth\examples;

use think\Model;
use thinkweb\auth\tratis\User;

class Admin extends Model {
    use User;

    protected function initialize(){
        //�����ֶ����ƣ����ݲ�ͬ��
        $this->nike_name_key = 'nike_name'; //�ǳ�
        $this->login_fail_key = 'login_fail'; //��¼ʧ�ܴ���
        $this->login_token_key = 'login_token'; //��¼token
        $this->password_key = 'password'; //����

        $this->userType = 'admin';
        $this->salt = 'salt';
        $this->max_login_fail_nums = 5;
    }

}