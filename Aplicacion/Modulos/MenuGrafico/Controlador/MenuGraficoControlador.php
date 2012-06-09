<?php

require_once 'Zend/View.php';
require_once 'Aplicacion/Librerias/ControladorBase.php';
require_once LIBRERIAS . 'ControlarSesion.php';

class MenuGraficoControlador extends ControladorBase
{

    protected $_vista;

    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'MenuGrafico/Vista');
//        $this->_vista->addScriptPath(Aplicacion .'vistas/menu/');
    }

    public function index()
    {
        require_once LIBRERIAS . 'archivosYcarpetas.php';
        $modulos = archivosYcarpetas::listar_directorios_ruta('Aplicacion/Modulos/');

        foreach ($modulos as $modulo) {
            if ($modulo != 'Login' && $modulo != 'MenuGrafico'){
                $listaModulos[]=$modulo;
            }
        }
        $this->_vista->modulos = $listaModulos;
        $this->_layout->content = $this->_vista->render('MenuGraficoVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    public function logout()
    {
        Usuario::actualizarUltimaVisita($conta_sesion->MM_UserId, 'NO');
        Zend_Session::stop();
        // Finalmente, destruye la sesi�n
        Zend_Session::destroy();
    }


}

?>