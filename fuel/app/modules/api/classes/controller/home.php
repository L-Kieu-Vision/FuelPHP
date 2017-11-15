<?php

namespace Api;
use Controller_Base_Rest;
use Fuel\Core\DB;
use Fuel\Core\Theme;
use Auth\Auth;
use Fuel\Core\Date;
use Fuel\Core\Controller;
use Fuel\Core\Response, View;
use Fuel\Core\Input;

use Fuel\Core\Log;
use Fuel\Core\Lang;
use Fuel\Core\Validation;
use Api\Exception\ExceptionCode;
use Exception;

class Controller_Home extends Controller_Base_Rest {

	public function before(){
        Lang::load('app');
        parent::before();
	}
    
    public function post_getdeparment() {

        try {

            $department = DB::query('SELECT department_name, department_code  FROM department')->execute()->as_array();
            $this->resp(null, null, $department);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }
    
    public function post_getlist(){
        try {
            $arrRes = [
                'list' => [],
                'total_page' => 0,
            ];
            $oQuery = DB::select('users.*','department.department_name')->from('users')->join('department', 'LEFT')
                            ->on('users.department_code', '=', 'department.department_code')->order_by('id', 'desc')
                            ->where('users.del_flg','=',0);
            if (Input::method() == 'POST') {
                $arrInput   = Input::post();
                $sEmp       = isset($arrInput['sEmp'])?$arrInput['sEmp']:'';
                $sEmail     = isset($arrInput['sEmail'])?$arrInput['sEmail']:'';
                $sName      = isset($arrInput['sName'])?$arrInput['sName']:'';
                $sPhone     = isset($arrInput['sPhone'])?$arrInput['sPhone']:'';
                $sDepar     = isset($arrInput['department'])?$arrInput['department']:'';
                $datefrom   = isset($arrInput['datefrom'])?$arrInput['datefrom']:'';
                $dateto     = isset($arrInput['dateto'])?$arrInput['dateto']:'';
                $page       = isset($arrInput['page'])?$arrInput['page']:1; 
                $limit      = isset($arrInput['limit'])?$arrInput['limit']:5;

                if(!empty($sEmp)){
                    $oQuery->where('employee_id','=', trim($arrInput['sEmp']));
                }
                if(!empty($sDepar)){
                    $oQuery->where('users.department_code','=', $arrInput['department']);
                }
                if(!empty($sName)){
                    $oQuery->where('name','like', '%'.trim($arrInput['sName']).'%');
                }
                if(!empty($sEmail)){
                    $oQuery->where('email','like', '%'.trim($arrInput['sEmail']).'%');
                }
                if(!empty($sPhone)){
                    $oQuery->where('phone_num','like', '%'.trim($arrInput['sPhone']).'%');
                }
                if(!empty($datefrom) && !empty($dateto)){
                    $oQuery->where('users.create_time','between', array($datefrom,$dateto));
                }
            }

            $result = $oQuery->execute()->as_array();
            if ($result) {
                $total_rows     = count($result);
                $current_page   = $page;
                $limit          = $limit;
                $totalrecord    = count($result);
                if (is_numeric($limit)) {
                    $total_page     = ceil($total_rows / $limit);
                    //$page_info = ['total_page'=>$total_page,'current_page'=>$current_page];

                    // Giới hạn current_page trong khoảng 1 đến total_page
                    if ($current_page > $total_page){
                        $current_page = $total_page;
                    }
                    else if ($current_page < 1){
                        $current_page = 1;
                    }
                    $start  = ($current_page - 1) * $limit;
                    $result = $oQuery->limit($limit)->offset($start)->execute()->as_array();


                    $arrRes['list']         = $oQuery->execute()->as_array();
                    $arrRes['total_page']   = $total_page;
                    $arrRes['total_record'] = $totalrecord;
                    
                } else {
                    $arrRes['list'] = $oQuery->execute()->as_array();
                    $arrRes['total_page'] = 1;
                }
                
            }
            $this->resp(null, null, $arrRes);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }

    public function post_add() {
        try {
            $result = [];
            // var_dump(Input::post());die;
            $val = \Validation::forge();
            if (Input::method() == 'POST') {
                $val->add_field('email', 'Email', 'valid_email[email]');
                $val->add_field('password', 'Password','required|min_length[3]|max_length[50]');
                $val->add_field('confirm', 'Confirm Password', 'match_field[password]');
                $val->add_field('empid', 'Employee Id','required');
                $val->add_field('name', 'Name','required');
                $val->add_field('phone', 'Phone','required');
                // $val->add_field('department', 'Department','required');

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
                    $result['error'] = $errors;
                }
            }
            $this->resp(null, null, $result);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }

    public function post_edit() {
        try {
            $result = [];

            $val = \Validation::forge();
            if (Input::method() == 'POST') {
                $val->add_field('empid', 'Employee Id', 'required');
                $val->add_field('name', 'Name', 'required');
                $val->add_field('email', 'Email', 'required|valid_email');
                $val->add_field('phone', 'Phone', 'required');
                if ($val->run()) {
                    $id         = Input::post('id');
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
            $this->resp(null, null, $result);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }

    public function post_detail() {
        try {
            $id = Input::post('id');
            $result = DB::select('id','employee_id','name','email','phone_num','department_code')->from('users')->where('id', $id)->execute()->as_array();
            $this->resp(null, null, $result);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        
        return $this->response($this->resp);
    }


    public function post_multidelete() {
        try {
            if (Input::method() == 'POST') {
                $ids = Input::post('id');
                foreach ($ids as $id) {
                   $result =  DB::update('users')->value("del_flg", 1)->where('id', '=', $id)->execute();
                }
            }
            $this->resp(null, null, $result);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        
        return $this->response($this->resp);
    }

    public function post_deleteall() {
        try {
            $oQuery = DB::update('users')->value("del_flg", 1);
            if (Input::method() == 'POST') {
                $arrInput   = Input::post();
                $sEmp       = isset($arrInput['sEmp'])?$arrInput['sEmp']:'';
                $sEmail     = isset($arrInput['sEmail'])?$arrInput['sEmail']:'';
                $sName      = isset($arrInput['sName'])?$arrInput['sName']:'';
                $sPhone     = isset($arrInput['sPhone'])?$arrInput['sPhone']:'';
                $sDepar     = isset($arrInput['department'])?$arrInput['department']:'';
                $datefrom   = isset($arrInput['datefrom'])?$arrInput['datefrom']:'';
                $dateto     = isset($arrInput['dateto'])?$arrInput['dateto']:'';
                // $page       = isset($arrInput['page'])?$arrInput['page']:1; 
                if(!empty($sEmp)){
                    $oQuery->where('employee_id','=', trim($arrInput['sEmp']));
                }
                if(!empty($sDepar)){
                    $oQuery->where('users.department_code','=', $arrInput['department']);
                }
                if(!empty($sName)){
                    $oQuery->where('name','like', '%'.trim($arrInput['sName']).'%');
                }
                if(!empty($sEmail)){
                    $oQuery->where('email','like', '%'.trim($arrInput['sEmail']).'%');
                }
                if(!empty($sPhone)){
                    $oQuery->where('phone_num','like', '%'.trim($arrInput['sPhone']).'%');
                }
                if(!empty($datefrom) && !empty($dateto)){
                    $oQuery->where('users.create_time','between', array($datefrom,$dateto));
                }
            }
            $result =  $oQuery->execute();
            $this->resp(null, null, $result);
        } catch (Exception $e) {
            Log::write('ERROR', $e->getMessage(), __CLASS__ . ':' . __FUNCTION__ . ':' . $e->getLine());
            $code = empty($e->getCode()) ? ExceptionCode::E_SYSTEM_ERROR : $e->getCode();
            $msg = empty($e->getMessage()) ? Lang::get('exception_msg.' . ExceptionCode::E_SYSTEM_ERROR) : $e->getMessage();
            $this->resp($msg, $code);
        }
        return $this->response($this->resp);
    }

    
    public function after($response)
    {
        $response = parent::after($response);
        return $response;
    }
}