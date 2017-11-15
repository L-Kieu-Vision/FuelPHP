<?php
namespace Api;
use Fuel\Core\Controller;
use Fuel\Core\Theme;
use Auth\Auth;

class Controller_Auth extends Controller{
	public function before(){
		$this->theme = Theme::instance();
		$this->theme->active('default');
		$this->theme->set_template('layout_login');
		$this->theme->set_partial('head', 'partials/head');
		// $this->theme->set_partial('script', 'partials/script');

	}
	 public function action_login(){
        
        if(Auth::check()){
            \Response::redirect('/api/home');
        }
        
        if(\Request::main()->get_method() == 'POST'){
            $val = \Validation::forge();
            $val->add_field('email', 'email', 'required|min_length[2]|max_length[255]');
            $val->add_field('password', 'password', 'required|min_length[3]|max_length[50]');
            if (!$val->run()) {
            	$errors = $val->error_message();
                // pass error
                $this->theme->set_partial('content', 'api::auth/login')->set('errors', $errors);
                
            }else{

	            $userId = $val->validated('email');
	            $password = $val->validated('password');
	            if (Auth::instance()->login($userId, $password)) {
	                // var_dump(Auth::instance()->login($userId, $password));die;
	                \Response::redirect('/api/home/index');
	            }else{
	            	// pass error
	            	$errors = array('Email Or Password Incorect! Please Try Again');
	                $this->theme->set_partial('content', 'store::auth/login')->set('errors', $errors);
	            }
            }
        }else{
        	$this->theme->set_partial('content', 'api::auth/login');		
        }
    }
    public function action_logout() {
    	Auth::logout();
    	\Response::redirect('/api/auth/login');
    }

	public function after($response) {
        // If no response object was returned by the action,
        if (empty($response) or  ! $response instanceof Response)
        {
            // render the defined template
            $response = \Response::forge(\Theme::instance()->render());
        }

        return parent::after($response);
    }
}
?>