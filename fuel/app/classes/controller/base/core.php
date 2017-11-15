<?php

use Fuel\Core\Controller_Hybrid;
use Fuel\Core\Theme;
use Fuel\Core\Inflector;
use Fuel\Core\Request;
use Fuel\Core\Lang;
use Fuel\Core\View;
use Fuel\Core\Asset;
use Fuel\Core\Response;

class Controller_Base_Core extends Controller_Hybrid
{

    public $template = 'layout';
    protected $format = 'json';
    protected $module;
    protected $controller;
    protected $action;
    protected $is_login = false;
    protected $user = [];

    public function before()
    {
        $this->init();
        parent::before();
        $this->render_template();
        $this->set_title();
    }

    public function after($response)
    {
        $response = parent::after($response);
        return $response;
    }

    public function router($resource, $arguments)
    {
        parent::router($resource, $arguments);
    }

    public function init()
    {
        Lang::load('app', true);
        $this->is_login   = Model_Base_User::is_login();
        $this->user       = Model_Base_User::getUserInfo();
        $this->module     = strtolower(Request::active()->module);
        $this->controller = strtolower(substr(Inflector::denamespace(Request::active()->controller), 11));
        $this->action     = Request::active()->action;
        if (!empty($this->module)) {
            if (!$this->is_login) {
                Response::redirect('/login');
            }
            $this->template = $this->module . '::' . $this->template;
        }

        if (Session::get('user_manage_unit_cd')) {
            $this->user['manage_unit_cd'] = Session::get('user_manage_unit_cd');
        } 

        View::set_global('module', $this->module);
        View::set_global('controller', $this->controller);
        View::set_global('action', $this->action);
        View::set_global('user', $this->user);

        switch ($this->module) {
            case 'call_list':
                if (empty($this->user['menu_priv']['list_flg']) && $this->controller !== 'dashboard') {
                    Response::redirect('/');
                }
                break;
            case 'call':
                if (empty($this->user['menu_priv']['call_flg'])) {
                    Response::redirect('/');
                }
                break;
            case 'apo':
                if (empty($this->user['menu_priv']['apo_flg'])) {
                    Response::redirect('/');
                }
                break;
            case 'master':
                if (empty($this->user['sys_role']['role_lv_cd']) || !in_array($this->user['sys_role']['role_lv_cd'], [40, 50])) {
                    Response::redirect('/');
                }
                break;
            default:
                break;
        }
    }

    public function render_template()
    {
        switch ($this->module) {
            case 'call_list':
            case 'call':
            case 'apo':
            case 'master':
                $this->theme = Theme::instance();
                $this->theme->active('main');
                $this->theme->set_template('layout');
                $this->theme->set_partial('head', 'partials/head');
                $this->theme->set_partial('left_menu', 'partials/left_menu');
                $this->theme->set_partial('top_menu', 'partials/top_menu');
                $this->theme->set_partial('modal', 'partials/modal');
                $this->theme->set_partial('script', 'partials/script');
                View::set_global('theme', $this->theme);
                Asset::add_path('themes/main/js/', 'js');
                break;
            default:
                break;
        }
    }

    public function set_title()
    {
        if (isset($this->theme) && is_object($this->theme)) {
            $this->theme->get_template()->set('title', Lang::get('app.title'));
        } elseif (is_object($this->template)) {
            $this->template->title = Lang::get('app.title');
        }
    }

}
