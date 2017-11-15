<?php

namespace Store;
use Fuel\Core\DB;
use Fuel\Core\Theme;
use Auth\Auth;
use Fuel\Core\Date;
use Fuel\Core\Controller;
use Fuel\Core\Response, View;
use Fuel\Core\Input;

class Controller_Home extends Controller{
	public $template = 'layout_admin';
	public function before(){
        $department = DB::query('SELECT department_name, department_code  FROM department')->execute()->as_array();
		$this->theme = \Theme::instance();
		$this->theme->active('default');
		$this->theme->set_template('layout_admin')->set('department', $department);
		$this->theme->set_partial('head_admin', 'partials/head_admin');
		$this->theme->set_partial('menu_admin', 'partials/menu_admin');
	}

    public function action_index($page){
    	
        if(!Auth::check()){
            \Response::redirect('/store/auth/login');
        }
        $arrInput = Input::get();
        // var_dump($arrInput);
        $arrUser = DB::query('SELECT id, employee_id, department_code, email, name, phone_num, create_time FROM `users` ORDER BY id')->execute()->as_array();
        
        $oQuery = DB::select('users.*','department.department_name')->from('department')->join('users', 'LEFT')->on('users.department_code', '=', 'department.department_code')->where('users.del_flg','=',0);
        if(!empty($arrInput['sDepar'])){
             $oQuery->where('users.department_code','=', $arrInput['sDepar']);
        }
        if(!empty($arrInput['sEmp'])){
             $oQuery->where('employee_id','=', trim($arrInput['sEmp']));
        }
        if(!empty($arrInput['sName'])){
             $oQuery->where('name','like', '%'.trim($arrInput['sName']).'%');
        }
        if(!empty($arrInput['sEmail'])){
             $oQuery->where('email','like', '%'.trim($arrInput['sEmail']).'%');
        }
        if(!empty($arrInput['sPhone'])){
             $oQuery->where('phone_num','like', '%'.trim($arrInput['sPhone']).'%');
        }
        if(!empty($arrInput['datefrom']) && !empty($arrInput['dateto'])){
             $oQuery->where('users.create_time','between', array($arrInput['datefrom'],$arrInput['dateto']));
        }

        // echo $oQuery;

        // Pagination
        $result = $oQuery->execute()->as_array();
        $total_rows     = count($result);
        $current_page   = $page;
        var_dump($current_page);
        $limit          = 5;
        $total_page     = ceil($total_rows / $limit);
        $page_info = ['total_page'=>$total_page,'current_page'=>$current_page];

        // Giới hạn current_page trong khoảng 1 đến total_page
        if ($current_page > $total_page){
            $current_page = $total_page;
        }
        else if ($current_page < 1){
            $current_page = 1;
        }
        $start  = ($current_page - 1) * $limit;
        $result = $oQuery->limit($limit)->offset($start)->execute()->as_array();
        // var_dump($result);die;

        // var_dump($result['q']);die;
        // $result['pagination'] = $pagination;
         //var_dump($result['pagination']);die;
        // $result = DB::select('users.*','department.department_name')->from('department')->join('users', 'LEFT')
        //                  ->on('users.department_code', '=', 'department.department_code')->execute()->as_array();

        $department = DB::query('SELECT department_name, department_code  FROM department')->execute()->as_array();

        $this->theme->set_partial('content_admin', 'store::auth/admin')->set(['users'=>$result,'page_info'=>$page_info,'department'=>$department]);

        // var_dump($arrUser);die;
        // $arrData = ['arrUser' => $arrUser];
        //return Response::forge(View::forge('store::auth/admin', $arrData));

        // var_dump(Auth::get());
        // die;
    }

    
    public function renderQuery($oQuery){
        // if(!empty($arrInput['datefrom']) && !empty($arrInput['dateto'])){
        //      $oQuery->where('users.create_time','between', array($arrInput['datefrom'],$arrInput['dateto']));
        // }
        // return $oQuery;
    }
    public function action_add() {
        header('Content-Type: application/json');
        $result = ['status' => 200];

        $val = \Validation::forge();
        if (Input::method() == 'POST') {
            $val->add_field('email', 'Email', 'valid_email[email]');
            $val->add_field('password', 'Password','required|min_length[3]|max_length[50]');
            $val->add_field('confirm_password', 'Confirm Password', 'match_field[password]');
            $val->add_field('empid', 'Employee Id','required');
            $val->add_field('name', 'Name','required');
            $val->add_field('phone', 'Phone','required');

            if ($val->run()) {
                $empid      = Input::post('empid'); 
                $name       = Input::post('name'); 
                $email      = Input::post('email'); 
                $password   = Input::post('password');
                $password   = Auth::hash_password($password);
                $phone      = Input::post('phone'); 
                $department = Input::post('department');
                list($insert_id, $rows_affected) = DB::insert('users')->columns(array(
                    'employee_id','email','password','name','phone_num','department_code','create_time'
                ))->values(array(
                    'employee_id'       => $empid,
                    'email'             => $email,
                    'password'          => $password,
                    'name'              => $name,
                    'phone_num'         => $phone,
                    'department_code'   => $department,
                    'create_time'       => Date::forge(time())->format("%Y-%m-%d %H:%M:%S"),
                ))->execute();
                
            } else {
                $errors = $val->error_message();
                $result['status'] = 3000;
                $result['error'] = $errors;
            }
        }
        echo json_encode($result);die;
    }
    public function action_edit() {
        header('Content-Type: application/json');
        $result = ['status' => 200];

        $val = \Validation::forge();
        if (Input::method() == 'POST') {
            $val->add_field('empid', 'Employee Id', 'required');
            $val->add_field('name', 'Name', 'required');
            $val->add_field('email', 'Email', 'required|valid_email');
            $val->add_field('phone', 'Phone', 'required');
            if ($val->run()) {   
                $id         = Input::post('user_id');
                $empid      = Input::post('empid'); 
                $name       = Input::post('name'); 
                $email      = Input::post('email'); 
                $phone      = Input::post('phone'); 
                $department = Input::post('department');
                DB::update('users')
                    ->value("employee_id", $empid)
                    ->value("name", $name)
                    ->value("email", $email)
                    ->value("phone_num", $phone)
                    ->value("department_code", $department)
                    ->where('id', '=', $id)
                    ->execute(); 

            } else {
                $errors = $val->error_message();
                $result['status'] = 3000;
                $result['error'] = $errors;
            }
        }
        
        echo json_encode($result);die;
    }

    public function action_detail() {
        header('Content-Type: application/json');
        $id = Input::post('user_id');
        // Select from db where id = $id
        $result = DB::select('id','employee_id','name','email','phone_num','department_code')->from('users')->where('id', $id)->execute()->as_array();

        echo json_encode(current($result));die;
    }

    public function action_multidelete() {

        if (Input::method() == 'POST') {
            $ids = Input::post('id');
            // var_dump($ids);die;
            foreach ($ids as $id) {
                // $result = DB::delete('users')->where('id', '=', $id)->execute();
                $result = DB::update('users')
                    ->value("del_flg", 1)
                    ->where('id', '=', $id)
                    ->execute(); 
            }
        }
        echo $result;die;
    }
    public function after($response)
    {
        if (empty($response) or  ! $response instanceof Response) {
            $response = \Response::forge(\Theme::instance()->render());
        }
        
        return parent::after($response);
    }
}