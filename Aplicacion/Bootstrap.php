<?php

require_once 'Controlador.php';
require_once 'Aplicacion/Config/configuration.php';
//require_once 'Librerias/Zend/Session/Namespace.php';

class Bootstrap
{

    protected $_appNamespace = 'Application';

    protected function __construct()
    {
        
    }

    protected function _initSession()
    {
         if (!Zend_Session::isStarted()) {
             Zend_Session::start();
         }
    }

    static function main()
    {
//        $sesion = new Zend_Session_Namespace('didaskalos');
//        if (!Zend_Session::isStarted()) {
//            echo 'no hay sesion';
//            Zend_Session::start();
//            echo 'sesion creada';
//        }
        require_once LIBRERIAS . 'Zend/Loader.php';
        Zend_Loader::registerAutoload();
        $autoloader = Zend_Loader_Autoloader::getInstance();
//        $autoloader->registerNamespace('ZendX');
//        Zend_Loader::loadClass('ZendX_JQuery', 'Aplicacion/Librerias');
//        Zend_Loader_Autoloader::getInstance();
//        Zend_Session::setOptions(array('strict' => 'true'));
//        $loader = new Zend_Loader_PluginLoader();
//        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
//        $viewRenderer->setView($view);
//        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        Controlador::despachador();
    }

}
