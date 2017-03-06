<?php
namespace thinkweb\auth\examples;

use think\Model;
use thinkweb\auth\tratis\User;

class Admin extends Model {
    use User;

    protected function initialize(){
        //ÉèÖÃ×Ö¶ÎÃû³Æ£¬¼æÈÝ²»Í¬±í
        $this->nike_name_key = 'nike_name'; //êÇ³Æ
        $this->login_fail_key = 'login_fail'; //µÇÂ¼Ê§°Ü´ÎÊý
        $this->login_token_key = 'login_token'; //µÇÂ¼token
        $this->password_key = 'password'; //ÃÜÂë

        $this->userType = 'admin';
        $this->salt = 'salt';
        $this->max_login_fail_nums = 5;
    }

}