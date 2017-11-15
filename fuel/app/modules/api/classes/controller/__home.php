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

class Controller_Home extends Controller_Base_Rest{
	public $template = 'layout_admin';

	public function before(){
        Lang::load('app');
        parent::before();
	}

    public function action_index(){
        if(!Auth::check()){
            \Response::redirect('/api/auth/login');
        }
        header('Content-Type: application/json'); 
        $department = DB::query('SELECT department_name, department_code  FROM department')->execute()->as_array();

        $this->theme->set_partial('content_admin', 'api::auth/admin')->set(['department'=>$department]);
        echo json_encode($department);die;
    }
    
    public function action_getdeparment() {
        header('Content-Type: application/json'); 
        $department = DB::query('SELECT department_name, department_code  FROM department')->execute()->as_array();
        echo json_encode($department);die;
    } 

    public function action_getlist(){
        // var_dump(Input::post());die;
        $arrRes = [
            'list' => [],
            'total_page' => 0,
        ];
        header('Content-Type: application/json');
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
                // $result = [
                //     'list' => $oQuery->execute()->as_array(),
                //     'page_info' =>$page_info,
                // ];
            } else {
                $arrRes['list'] = $oQuery->execute()->as_array();
                $arrRes['total_page'] = 1;
            }
            
        } 
        echo json_encode($arrRes);die;
    }

    public function action_add() {
        header('Content-Type: application/json');
        $result = ['status' => 200];
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
        echo json_encode($result);die;
    }

    public function action_detail() {
        header('Content-Type: application/json');
        $id = Input::post('id');
        $result = DB::select('id','employee_id','name','email','phone_num','department_code')->from('users')->where('id', $id)->execute()->as_array();

        echo json_encode(current($result));die;
    }

/*-Function--------Update Order And Order Detail--------------------*/ 

    public function action_upDateOrder() {
        header('Content-Type: application/json');
        $orders      = Input::post('orders');
        $employee_id = Input::post('employee_id');
        $result = ['status' => 200];
         // var_dump($orders);die;
        /*
        DB::update('order_detail')->value("del_flg", 1)->where('id_order', 'IN', DB::expr("(
            SELECT `order`.id FROM `order` WHERE `order`.employee_id = ".$employee_id."
            )"))->execute();

        if ($employee_id == $orders[0]['employee_id']) { 
            DB::update('order')->value("del_flg", 1)->where('employee_id', '=', $employee_id)->execute();
        } */
        
        foreach ($orders as $key => $order) {
            if ($order['id'] == "" || $order['id'] == null) {
                # code...
                list($insert_id, $rows_affected) = DB::insert('order')->columns(array(
                        'employee_id','total','status','del_flg'
                    ))->values(array(
                        'employee_id' => $order['employee_id'],
                        'total'       => $order['total'],
                        'status'      => $order['status'],
                        'del_flg'     => $order['del_flg'],
                    ))->execute();
            } else {
                DB::update('order')
                    ->value("total", $order['total'])
                    ->where('id', '=', $order['id'])
                    ->execute();
            }

            foreach($order['order_detail'] as $product){
                if ($product['id'] == "" || $product['id'] == null) {
                    
                    DB::insert('order_detail')->columns(array(
                        'id_order','id_product','amount','del_flg'
                    ))->values(array(
                        'id_order'   => isset($insert_id) ? $insert_id : $order['id'],
                        'id_product' => $product['id_product'],
                        'amount'     => $product['amount'],
                        'del_flg'    => 0,
                    ))->execute(); 
                } else {
                    DB::update('order_detail')
                        ->value("id_product", $product['id_product'])
                        ->value("amount", $product['amount'])
                        ->where('id', '=', $product['id'])
                        ->execute();
                }             
            }
        }
        echo json_encode($result);die;
    }

    public function action_order() {
        header('Content-Type: application/json');

        $id = Input::post('id');
        $arrOrder = DB::select('order.*')->from('order')
                        ->join('users', 'LEFT')
                        ->on('order.employee_id', '=', 'users.id')
                        ->where('users.id','=',$id)
                        ->where('order.del_flg', '=', 0)
                        ->execute()->as_array();

       
        $arrOrderDetail = DB::select('order_detail.id','product.name','product.price', 'order_detail.id_order','order_detail.id_product','order_detail.amount')
                        ->from('order_detail')
                        ->join('product', 'LEFT')
                        ->on('order_detail.id_product', '=', 'product.id')
                        ->join('order', 'LEFT')
                        ->on('order_detail.id_order','=','order.id')
                        ->where('order_detail.del_flg','=', 0)
                        ->execute()->as_array();
        foreach ($arrOrder as $key => $value) {
            foreach ($arrOrderDetail as $value1) {
                if ($value['id'] == $value1['id_order']) {
                    if(!isset($arrOrder[$key]['order_detail'])){
                        $arrOrder[$key]['order_detail'] = [];
                    }
                    $arrOrder[$key]['order_detail'][] = $value1;
                }
            }
        }

        echo json_encode($arrOrder);die;

        // order-dettail
        // data input
        /*                
        $arrOrderDetail = [
            [
                'id' => 1,
                'id_order' => 142,
                'id_product' => 1,
                'amount' => 23
            ],
            [
                'id' => 2,
                'id_order' => 142,
                'id_product' => 1,
                'amount' => 23
            ]
            ,[
                'id' => 3,
                'id_order' => 143,
                'id_product' => 2,
                'amount' => 23
            ],
            [
                'id' => 4,
                'id_order' => 143,
                'id_product' => 1,
                'amount' => 23
            ]
        ];


        //result 
        $order = [
                    [
                        'id' => 142,
                        'total' => 1000,
                        'order-detail' => [
                            [   'id' => 1,
                                'id_order' => 142
                                'id_product' => 4,
                                'amount' => 20
                            ],
                            [   'id' => 2,
                                'id_order' => 143
                                'id_product' => 5,
                                'amount' => 40
                            ]
                        ]
                    ],

                    [
                        'id' => 143,
                        'total' => 2000,
                        'order-detail' => [
                            [   'id' => 1,
                                'id_order' => 143
                                'id_product' => 4,
                                'amount' => 20
                            ],
                            [   'id' => 2,
                                'id_order' => 143
                                'id_product' => 5,
                                'amount' => 40
                            ]
                        ]
                    ],
                ]

        */
        
    }
    
    public function action_showProduct() {
        header('Content-Type: application/json');
        $oProduct = DB::select('id','name','price')->from('product')->execute()->as_array();
        echo json_encode($oProduct);die;
    }

    public function action_showProducted() {
        header('Content-Type: application/json');
        $id           = Input::post('idProducted');
        $index        = Input::post('index');
        $indexparent  = Input::post('parentindex');
        $oProducted   = DB::select('product.id','product.name','product.price')->from('product')
                            ->where('product.id',' =', $id)
                            ->execute()->as_array();
        echo json_encode($oProducted);die;
    }

    public function action_multidelete() {
        if (Input::method() == 'POST') {
            $ids = Input::post('id');
            foreach ($ids as $id) {
               $result =  DB::update('users')->value("del_flg", 1)->where('id', '=', $id)->execute();
            }
        }
        echo $result;die;
    }

    public function action_deleteall() {
        header('Content-Type: application/json');
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
        echo $result;die;
    }

    
    public function after($response)
    {
        $response = parent::after($response);
        return $response;
    }
}