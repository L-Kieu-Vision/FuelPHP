<?php

use Fuel\Core\Controller_Rest;
use Fuel\Core\Config;
use Fuel\Core\Lang;
use Fuel\Core\Log;
use Fuel\Core\Uri;
use Fuel\Core\Input;
use Fuel\Core\Inflector;
use Fuel\Core\Session;
use Api\Exception\ExceptionCode;

class Controller_Base_Rest extends Controller_Rest
{

    protected $format = 'json';
    protected $is_login = false;
    protected $module;
    protected $controller;
    protected $action;
    protected $lang;
    protected $data = [];
    protected $user = [];
    protected $resp = [
        'code' => ExceptionCode::E_OK,
        'message' => '',
        'data' => [],
        'error' => [],
    ];

    public function before()
    {
        Lang::load('app');
        $this->init();
        parent::before(); 
    }

    public function after($response)
    {
        $response = parent::after($response);
        $response->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->set_header('Pragma', 'no-cache');

        $response->set_header('Access-Control-Allow-Origin', '*');
        $response->set_header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
        $response->set_header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
        $response->set_header('Access-Control-Max-Age', 0);
        $response->set_header('Content-Language', Config::get('language'));
        $response->set_header('Content-Type', 'application/json; charset=utf-8');
        return $response;
    }

    public function router($resource, $arguments)
    {

        // If method is not available, set status code to 404
        $controller_method = strtolower(Input::method()) . '_' . $resource;
        if (!method_exists($this, $controller_method)) {
            $this->response->status = ExceptionCode::E_OK; // or $this->no_method_status
            $this->resp(Lang::get('exception_msg.' . ExceptionCode::E_NOT_FOUND), ExceptionCode::E_NOT_FOUND);
            return $this->response($this->resp);
        } else {
            return call_user_func_array([$this, $controller_method], $arguments);
        }
        parent::router($resource, $arguments);
    }

    public function resp($msg = null, $code = null, $data = [])
    {
        if (!empty($code)) {
            $this->resp['code'] = $code;
        }
        if (!empty($msg)) {
            $this->resp['message'] = $msg;
        }
        if ($this->resp['code'] == ExceptionCode::E_OK) {
            $this->resp['data'] = $data;
        } else {
            $this->resp['error'] = $data;
        }
    }

    public function init()
    {
        Lang::load('app');
        $this->is_login = \Auth::check();
        // $this->user = Model_Base_User::getUSerInfo();
        $this->module = strtolower(Request::active()->module);
        $this->controller = strtolower(substr(Inflector::denamespace(Request::active()->controller), 11));
        $this->action = Request::active()->action;
        
        // write log
        $this->init_log();
    }

    public function init_log()
    {
        Log::write('NOTICE', Uri::main(), 'URL');
        Log::write('NOTICE', Input::method(), 'METHOD');
        if (!empty($this->user['emp_id'])) {
            Log::write('NOTICE', $this->user['emp_id'], 'emp_id');
        }
        Log::write('NOTICE', json_encode(Input::get(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'REQUEST - GET');
        Log::write('NOTICE', json_encode(Input::post(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'REQUEST - POST');
        Log::write('NOTICE', json_encode(Input::file()), 'REQUEST - FILE');
    }

}
