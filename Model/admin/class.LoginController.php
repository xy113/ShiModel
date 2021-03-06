<?php
namespace Admin;
class LoginController extends BaseController{
	function __construct(){
		parent::__construct();
		if (cookie('adminlogined') && $this->uid && $this->username){
			$this->redirect('/?m=admin');
		}
	}
	
	public function index(){
		if ($this->checkFormSubmit()){
			$account  = trim($_GET['account_'.FORMHASH]);
			$password = trim($_GET['password_'.FORMHASH]);
			if ($this->uid && $this->username){
				$userdata = member_get_data(array('uid'=>$this->uid, 'admincp'=>1));
			}else {
				if (ismobile($account)) {
					$userdata = member_get_data(array('admincp'=>1, 'mobile'=>$account));
				}elseif (isemail($account)) {
					$userdata = member_get_data(array('admincp'=>1 ,'email'=>$account));
				}
				if (!$userdata){
					$userdata = member_get_data(array('admincp'=>1, 'username'=>$account));
				}
			}

			if ($userdata && ($userdata['password'] == getPassword($password))){
				if (!$this->uid || !$this->username){
					member_login($userdata['uid'], $password, 'uid');
				}
				cookie('adminlogined', 1 ,1800);
				$this->showAjaxReturn(0);
			}else {
				$this->showAjaxError(101, 'LOGIN FAILD', array('info'=>L('password_incorrect')));
			}
		}else {
			$this->showlogin();
		}
	}
}