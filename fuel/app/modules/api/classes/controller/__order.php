<?php

namespace Api;
use Fuel\Core\DB;
use Fuel\Core\Theme;
use Auth\Auth;
use Fuel\Core\Date;
use Fuel\Core\Controller;
use Fuel\Core\Response, View;
use Fuel\Core\Input;

class Controller_Order extends Controller{


/*-Function--------Update Order And Order Detail--------------------*/ 

    public function action_upDateOrder() {
        header('Content-Type: application/json');
        $orders      = Input::post('orders');
        $employee_id = Input::post('employee_id');
        $result = ['status' => 200];
         // var_dump($orders);die;
        
        DB::update('order_detail')->value("del_flg", 1)->where('id_order', 'IN', DB::expr("(
            SELECT `order`.id FROM `order` WHERE `order`.employee_id = ".$employee_id."
            )"))->execute();

        if ($employee_id == $orders[0]['employee_id']) { 
            DB::update('order')->value("del_flg", 1)->where('employee_id', '=', $employee_id)->execute();
        } 
        
        foreach ($orders as $key => $order) {
            if ($order['id'] == "" || $order['id'] == null) {
                # code...
                list($insert_id, $rows_affected) = DB::insert('order')->columns(array(
                        'employee_id','total','status','del_flg'
                    ))->values(array(
                        'employee_id' => $order['employee_id'],
                        'total'       => $order['total'],
                        'status'      => $order['status'],
                        'del_flg'     => 0,
                    ))->execute();
            } else {
                DB::update('order')
                    ->value("total", $order['total'])
                    ->value("del_flg", 0)
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
                        ->value("del_flg", 0)
                        ->where('id', '=', $product['id'])
                        ->execute();
                }             
            }
        }
        echo json_encode($result);die;
    }

/*-Function--------Show Order And Order Detail--------------------*/ 

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

    }
 /*-Function--------Show Product In Select Box--------------------*/ 
   
    public function action_showProduct() {
        header('Content-Type: application/json');
        $oProduct = DB::select('id','name','price')->from('product')->execute()->as_array();
        echo json_encode($oProduct);die;
    }

/*-Function--------When selected selectbox appear information of that product--------------------*/ 

    public function action_showProducted() {
        header('Content-Type: application/json');
        $id           = Input::post('idProducted');
        $oProducted   = DB::select('product.id','product.name','product.price')->from('product')
                            ->where('product.id',' =', $id)
                            ->execute()->as_array();
        echo json_encode($oProducted);die;
    }
}