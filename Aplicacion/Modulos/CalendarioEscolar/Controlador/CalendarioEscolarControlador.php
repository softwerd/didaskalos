<?php

require_once 'Zend/View.php';
require_once LIBRERIAS . 'ControlarSesion.php';
require_once 'Aplicacion/Librerias/ControladorBase.php';

/**
 *  Clase Controladora del Modulo Login
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Login
 * 
 */
class CalendarioEscolarControlador extends ControladorBase 
{
    /**
     * Propiedad usada para la creacion de formularios
     * @var type Form 
     */
    protected static $_form;
    
//    /**
//     * Propiedad usada para enviar los elementos del formulario
//     * @var type Array
//     */
//    private $_varForm = array();
    
    /**
     * Propiedad usada para establecer los campos de la BD
     * @var type Array
     * @example id,apellidos,nombres,domicilio,nro_doc,fechaNac,nacionalidad,sexo
     */
    private $_campos = array(
        'id',
        'aLectivo',
        'inicio',
        'fin',
        'inicio_bimestre1',
        'fin_bimestre1',
        'inicio_bimestre2',
        'fin_bimestre2',
        'inicio_bimestre3',
        'fin_bimestre3',
        'inicio_bimestre4',
        'fin_bimestre4'
        );
    
    /**
     * Propiedad usada para establecer los títulos de los campos de la BD
     * @var type Array
     */
    private $_tituloCampos = array(
        'id'=>'Id',
        'aLectivo'=>'Ciclo Lectivo',
        'inicio'=>'Inicio',
        'fin'=>'Fin',
        'inicio_bimestre1'=>'Inicio 1ºBim.',
        'fin_bimestre1'=>'Fin 1ºBim.',
        'inicio_bimestre2'=>'Inicio 2ºBim.',
        'fin_bimestre2'=>'Fin 2ºBim.',
        'inicio_bimestre3'=>'Inicio 3ºBim.',
        'fin_bimestre3'=>'Fin 3ºBim.',
        'inicio_bimestre4'=>'Inicio 4ºBim.',
        'fin_bimestre4'=>'Fin 4ºBim.'
        );
    
    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=CalendarioEscolar&sub=agregar',
        'classIcono' => 'icono-nuevo32'
        );
    
    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=CalendarioEscolar&sub=listar',
        'classIcono' => 'icono-lista32'
        );
    
    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href'=>'index.php?option=CalendarioEscolar');
    
    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
    private $_paramBotonGuardar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"" ,
        );
    
    /**
     * Propiedad usada para configurar el boton FILTRAR
     * @var type array
     */
    private $_paramBotonFiltrar = array(
        'class' => 'btn_filtrar' ,
        'evento' => "onclick=\"javascript: submitbutton('filtrar')\"" ,
        'href'=>"\"javascript:void(0);\""
        );
    
    
    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */
    function __construct() 
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'CalendarioEscolar/Vista');
        require_once DIRMODULOS . 'CalendarioEscolar/Modelo/CalendarioEscolarModelo.php';
        $this->_modelo = new CalendarioEscolarModelo();
    }

    /**
     * Metodo para mostrar el menú principal de Configuracion
     */
    public function index() 
    {
        $this->_layout->content = $this->_vista->render('CalendarioEscolarVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }
    
    /**
     * Método para inscribir un alumno en un salón
     * El salón y el alumno deben existir
     */
    public function agregar()
    {
        require_once DIRMODULOS . 'CalendarioEscolar/Forms/CargaCalendarioEscolar.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        /* esto es para que la lista de configuracion no tenga limite */
        $this->_modelo->setLimite(0);
        /* creo el formulario */
        $this->_form = new Form_CargaCalendarioEscolar();
        /* pongo el formulario en la vista */
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['inicio'] = MyFechaHora::getFechaBd($values['inicio']);
                $values['fin'] = MyFechaHora::getFechaBd($values['fin']);
                $values['inicio_bimestre1'] = MyFechaHora::getFechaBd($values['inicio_bimestre1']);
                $values['fin_bimestre1'] = MyFechaHora::getFechaBd($values['fin_bimestre1']);
                $values['inicio_bimestre2'] = MyFechaHora::getFechaBd($values['inicio_bimestre2']);
                $values['fin_bimestre2'] = MyFechaHora::getFechaBd($values['fin_bimestre2']);
                $values['inicio_bimestre3'] = MyFechaHora::getFechaBd($values['inicio_bimestre3']);
                $values['fin_bimestre3'] = MyFechaHora::getFechaBd($values['fin_bimestre3']);
                $values['inicio_bimestre4'] = MyFechaHora::getFechaBd($values['inicio_bimestre4']);
                $values['fin_bimestre4'] = MyFechaHora::getFechaBd($values['fin_bimestre4']);
                $this->_modelo->guardar($values);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS,'info');
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);

        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarCalendarioEscolarVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    /**
     * Metodo para listar calendarios escolares en la grilla.
     * @param Array $arg 
     * @access public
     * @see Librerias/Grilla.php, Librerias/BarraHerramientas.php
     */
    public function listar ($arg='')
    {
        require_once LIBRERIAS . 'Grilla.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
//        require_once DIR_MODULOS . 'CalendarioEscolar/Forms/FiltroAlumnos.php';
        
        if (isset ($arg)){
            if (! empty($_GET['pg'])) {
                $pag = Input::get('pg');
            } else {
                $pag = 1;
            }
            $inicio = 0 + ($pag - 1) * 30;
            if (! empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'id DESC';
            }
        }
        $filtro = '';//$this->_crearFiltro($arg);

        $campos = $this->_campos;
        $fuenteDatos = $this->_modelo->listadoCalendarios($inicio, $orden, $filtro, $campos);
        $grilla = new Grilla($fuenteDatos);
        $grilla->setTituloGrilla('Calendarios Escolares');
//        $grilla->setFiltrar('SI');
        $grilla->setCampos($campos);
        $grilla->setPagina($pag);
        $grilla->setTotalPaginas(ceil($this->_modelo->getCantidadRegistros($filtro)/LIMITEGRILLA));
        $grilla->setColNames($this->_tituloCampos);
        $grilla->setFormatoCol(array(
            'id'=>'entero',
            'inicio'=>'fecha',
            'fin'=>'fecha',
            'inicio_bimestre1'=>'fecha',
            'fin_bimestre1'=>'fecha',
            'inicio_bimestre2'=>'fecha',
            'fin_bimestre2'=>'fecha',
            'inicio_bimestre3'=>'fecha',
            'fin_bimestre3'=>'fecha',
            'inicio_bimestre4'=>'fecha',
            'fin_bimestre4'=>'fecha'
            ));
        $grilla->setLink('index.php?option=CalendarioEscolar&sub=listar');
        if (is_array($arg)){
            $filtroBoton =  '&'.implode("&",$arg);
        }else{
            $filtroBoton =  '';
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        $bh->addBoton('Exportar',
                array('href' => 'index.php?option=CalendarioEscolar&sub=exportar'.$filtroBoton ,
                )); 
        $bh->addBoton('Volver', $this->_paramBotonVolver);
//        $campos = array(
//                    'apellidos'=>'Apellidos',
//                    'nombres'=>'Nombres',
//                    'domicilio'=>'Domicilio',
//                    'nro_doc'=>'Nro.Doc.',
//                    'fechaNac'=>'Fecha Nac.',
//                    'nacionalidad'=>'Nacionalidad',
//        );
//        $this->_form = new Form_FiltroAlumnos($campos);
        $this->_vista->barraherramientas = $bh->render();
        

//        $this->_vista->mostrarFiltro = $this->_form->mostrar();
        
        $this->_vista->grid = $grilla->render();
        $this->_layout->content = $this->_vista->render('ListadoCalendarioEscolarVista.php');
        echo $this->_layout->render();
    }
    
    /**
     * Metodo para editar los datos de un calendario escolar
     * @param Array $arg 
     * @access public
     */
    public function editar ($arg)
    {
        require_once DIRMODULOS . 'CalendarioEscolar/Forms/CargaCalendarioEscolar.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        include_once LIBRERIAS . 'MyFechaHora.php';        
        $calendarioBuscado = $this->_modelo->buscarCalendarioEscolar($arg);
        if (is_object($calendarioBuscado)){
            $this->_varForm['id'] = $calendarioBuscado->id;
            $this->_varForm['aLectivo'] = $calendarioBuscado->aLectivo;
            $this->_varForm['inicio'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio);
            $this->_varForm['fin'] = MyFechaHora::getFechaAr($calendarioBuscado->fin);
            $this->_varForm['inicio_bimestre1'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio_bimestre1);
            $this->_varForm['fin_bimestre1'] = MyFechaHora::getFechaAr($calendarioBuscado->fin_bimestre1);
            $this->_varForm['inicio_bimestre2'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio_bimestre2);
            $this->_varForm['fin_bimestre2'] = MyFechaHora::getFechaAr($calendarioBuscado->fin_bimestre2);
            $this->_varForm['inicio_bimestre3'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio_bimestre3);
            $this->_varForm['fin_bimestre3'] = MyFechaHora::getFechaAr($calendarioBuscado->fin_bimestre3);
            $this->_varForm['inicio_bimestre4'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio_bimestre4);
            $this->_varForm['fin_bimestre4'] = MyFechaHora::getFechaAr($calendarioBuscado->fin_bimestre4);
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['inicio'] = '';
            $this->_varForm['fin'] = '';
            $this->_varForm['inicio_bimestre1'] = '';
            $this->_varForm['fin_bimestre1'] = '';
            $this->_varForm['inicio_bimestre2'] = '';
            $this->_varForm['fin_bimestre2'] = '';
            $this->_varForm['inicio_bimestre3'] = '';
            $this->_varForm['fin_bimestre3'] = '';
            $this->_varForm['inicio_bimestre4'] = '';
            $this->_varForm['fin_bimestre4'] = '';
        }
        $this->_form = new Form_CargaCalendarioEscolar($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['inicio'] = MyFechaHora::getFechaBd($values['inicio']);
                $values['fin'] = MyFechaHora::getFechaBd($values['fin']);
                $values['inicio_bimestre1'] = MyFechaHora::getFechaBd($values['inicio_bimestre1']);
                $values['fin_bimestre1'] = MyFechaHora::getFechaBd($values['fin_bimestre1']);
                $values['inicio_bimestre2'] = MyFechaHora::getFechaBd($values['inicio_bimestre2']);
                $values['fin_bimestre2'] = MyFechaHora::getFechaBd($values['fin_bimestre2']);
                $values['inicio_bimestre3'] = MyFechaHora::getFechaBd($values['inicio_bimestre3']);
                $values['fin_bimestre3'] = MyFechaHora::getFechaBd($values['fin_bimestre3']);
                $values['inicio_bimestre4'] = MyFechaHora::getFechaBd($values['inicio_bimestre4']);
                $values['fin_bimestre4'] = MyFechaHora::getFechaBd($values['fin_bimestre4']);
                $this->_modelo->actualizar('conta_calendarioescolar',$values,$arg);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS,'info');
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Eliminar', 
                array('href' => 'index.php?option=CalendarioEscolar&sub=eliminarCalendarioEscolar&id='. $this->_varForm['id']
                    ));

        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarCalendarioEscolarVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    /**
     * Metodo para eliminar un calendario escolar.
     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
     * para no mostrarlo en las proximas oportunidades
     * @param Array $arg 
     * @access public
     */
    public function eliminarCalendarioEscolar ($arg='')
    {
	$where = implode(',', $arg);
    	$values['eliminado']='1';
    	$this->_modelo->actualizar('conta_calendarioescolar',$values,$arg);
    	$this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS,'info');
        parent::_redirect(LIVESITE .'index.php?option=CalendarioEscolar&sub=listar');
    }

    
}
