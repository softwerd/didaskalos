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
class ConfiguracionControlador extends ControladorBase 
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
        'href' => 'index.php?option=Configuracion&sub=agregar',
        'classIcono' => 'icono-nuevo32'
        );
    
    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=Configuracion&sub=listar',
        'classIcono' => 'icono-lista32'
        );
    
    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href'=>'index.php?option=Configuracion');
    
    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
//    private $_paramBotonGuardar = array(
//        'href' => "\"javascript:void(0);\"",
//        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"" ,
//        );
    
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
        $this->_vista->addScriptPath(DIRMODULOS . 'Configuracion/Vista');
        require_once DIRMODULOS . 'Configuracion/Modelo/ConfiguracionModelo.php';
        $this->_modelo = new ConfiguracionModelo();
    }

    /**
     * Metodo para mostrar el menú principal de Configuracion
     */
    public function index() 
    {
        $this->_layout->content = $this->_vista->render('ConfiguracionVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }
    
    
    /**
     * Metodo para listar turnos escolares en la grilla.
     * @param Array $arg 
     * @access public
     * @see Librerias/Grilla.php, Librerias/BarraHerramientas.php
     */
    public function turnos ($arg='')
    {
        require_once LIBRERIAS . 'JQGrid.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('TURNOS');
        $grilla->setHeight(200);
        $grilla->setUrl(LIVESITE . '/index.php?option=configuracion&sub=jsonListarTurnos');
        $grilla->setEditUrl(LIVESITE . '/index.php?option=configuracion&sub=editarTurnos');

        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'turno'" => "'Turno'",
            "'desde'" => "'Desde'",
            "'hasta'" => "'Hasta'"
        ));

        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '50', 'align'=>"right"),
            array('name' => 'Turno', 'index' => 'turno', 'width' => '130', 'editable' => 'true'),
            array('name' => 'Desde', 'index' => 'desde', 'width' => '85', 'formatter'=>'time', 'editable' => 'true', 'align'=>"right"),
            array('name' => 'Hasta', 'index' => 'hasta', 'width' => '85', 'editable' => 'true', 'align'=>"right")
        ));
        $grilla->setIfBotonEditar(TRUE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setIfBotonAgregar(TRUE);
//        $grilla->setCellEdit(TRUE);
        $grilla->setOnSelectRow(TRUE);
        $action = "function(id){
        if(id && id!==lastSel){ 
            jQuery('#grilla').jqGrid('saveRow',lastSel, false); 
            lastSel=id; 
            }
            jQuery('#grilla').jqGrid('editRow',id, true); 
	},";
        $grilla->setActionOnSelectRow($action);
//        $grilla->setOnDblClickRow(TRUE);
//        $grilla->setActionOnDblClickRow("/index.php?option=cuotas&sub=editar&id=');

        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
//        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=configuracion';
        } else {
            $filtroBoton = '&lista=inscriptos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=configuracion&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoConfiguracionVista.php');
        echo $this->_layout->render();
    }
    
    public function editarTurnos()
    {
        $where = '';
        $values = array();

        if (! empty($_POST['id'])) {
            $where = 'id = ' . $_POST['id'];
        }
        if (! empty($_POST["Turno"])) {
            $values['turno'] = input::post("Turno"); 
        }
        if (! empty($_POST["Desde"])) {
            $values['desde'] = htmlspecialchars($_POST["Desde"]);
        }
        if (! empty($_POST["Hasta"])) {
            $values['hasta'] = htmlspecialchars($_POST["Hasta"]);
        }
        if ($_POST) {
            if ($_POST['oper']=='edit') {
               $this->_modelo->actualizar('conta_turnos',$values,$where);
            }
            if ($_POST['oper']=='add') {
               $this->_modelo->guardar('conta_turnos',$values);
            }
            
        }
        
    }


    public function jsonListarTurnos($arg='')
    {
        /** Me fijo si hay argumentos */
        if (isset($arg)) {
            /** Me fijo si existe el argumento page */
            if (!empty($_GET['page'])) {
                $pag = Input::get('page');
            } else {
                $pag = 1;
            }
            $inicio = ($pag - 1) * 30;
            /** Me fijo si existe el argumento de orden */
            if (!empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'turno';
            }
            /** Me fijo si el argumento es el tipo de orden (ASC o DESC) */
            if (!empty($_GET['sord'])) {
                $orden .= ' ' . Input::get('sord');
            } else {
                $orden .= ' ASC';
            }
            /** Si el argumento es un array entonces creo el filtro */
            if (is_array($arg)) {
                $filtroBoton = '&' . implode("&", $arg);
            } else {
                $filtroBoton = '';
            }
        }
        $json = new Zend_Json();
        $todos = $this->_modelo->listadoTurnos(0, 0,'', 0);
        $total_pages = ceil(count($todos) / 30);
        $result = $this->_modelo->listadoTurnos($inicio, 30, $orden, 0 );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;
        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array(
                $row['id'],
                $row['turno'],
                $row['desde'],
                $row['hasta']
                );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    /**
     * Metodo para editar los datos de un calendario escolar
     * @param Array $arg 
     * @access public
     */
//    public function editar ($arg)
//    {
//        require_once DIRMODULOS . 'CalendarioEscolar/Forms/CargaCalendarioEscolar.php';
//        require_once LIBRERIAS . 'BarraHerramientas.php';
//        include_once LIBRERIAS . 'MyFechaHora.php';        
//        $calendarioBuscado = $this->_modelo->buscarCalendarioEscolar($arg);
//        if (is_object($calendarioBuscado)){
//            $this->_varForm['id'] = $calendarioBuscado->id;
//            $this->_varForm['aLectivo'] = $calendarioBuscado->aLectivo;
//            $this->_varForm['inicio'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio);
//            $this->_varForm['fin'] = MyFechaHora::getFechaAr($calendarioBuscado->fin);
//            $this->_varForm['inicio_bimestre1'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio_bimestre1);
//            $this->_varForm['fin_bimestre1'] = MyFechaHora::getFechaAr($calendarioBuscado->fin_bimestre1);
//            $this->_varForm['inicio_bimestre2'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio_bimestre2);
//            $this->_varForm['fin_bimestre2'] = MyFechaHora::getFechaAr($calendarioBuscado->fin_bimestre2);
//            $this->_varForm['inicio_bimestre3'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio_bimestre3);
//            $this->_varForm['fin_bimestre3'] = MyFechaHora::getFechaAr($calendarioBuscado->fin_bimestre3);
//            $this->_varForm['inicio_bimestre4'] = MyFechaHora::getFechaAr($calendarioBuscado->inicio_bimestre4);
//            $this->_varForm['fin_bimestre4'] = MyFechaHora::getFechaAr($calendarioBuscado->fin_bimestre4);
//        } else {
//            $this->_varForm['id'] = '0';
//            $this->_varForm['inicio'] = '';
//            $this->_varForm['fin'] = '';
//            $this->_varForm['inicio_bimestre1'] = '';
//            $this->_varForm['fin_bimestre1'] = '';
//            $this->_varForm['inicio_bimestre2'] = '';
//            $this->_varForm['fin_bimestre2'] = '';
//            $this->_varForm['inicio_bimestre3'] = '';
//            $this->_varForm['fin_bimestre3'] = '';
//            $this->_varForm['inicio_bimestre4'] = '';
//            $this->_varForm['fin_bimestre4'] = '';
//        }
//        $this->_form = new Form_CargaCalendarioEscolar($this->_varForm);
//        $this->_vista->form = $this->_form->mostrar();
//        if ($_POST) {
//            if ($this->_form->isValid($_POST)) {
//                $values = $this->_form->getValidValues($_POST);
//                $values['inicio'] = MyFechaHora::getFechaBd($values['inicio']);
//                $values['fin'] = MyFechaHora::getFechaBd($values['fin']);
//                $values['inicio_bimestre1'] = MyFechaHora::getFechaBd($values['inicio_bimestre1']);
//                $values['fin_bimestre1'] = MyFechaHora::getFechaBd($values['fin_bimestre1']);
//                $values['inicio_bimestre2'] = MyFechaHora::getFechaBd($values['inicio_bimestre2']);
//                $values['fin_bimestre2'] = MyFechaHora::getFechaBd($values['fin_bimestre2']);
//                $values['inicio_bimestre3'] = MyFechaHora::getFechaBd($values['inicio_bimestre3']);
//                $values['fin_bimestre3'] = MyFechaHora::getFechaBd($values['fin_bimestre3']);
//                $values['inicio_bimestre4'] = MyFechaHora::getFechaBd($values['inicio_bimestre4']);
//                $values['fin_bimestre4'] = MyFechaHora::getFechaBd($values['fin_bimestre4']);
//                $this->_modelo->actualizar('conta_calendarioescolar',$values,$arg);
//                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS,'info');
//            }
//        }
//        $bh = new BarraHerramientas($this->_vista);
//        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
//        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
//        $bh->addBoton('Eliminar', 
//                array('href' => 'index.php?option=CalendarioEscolar&sub=eliminarCalendarioEscolar&id='. $this->_varForm['id']
//                    ));
//
//        $bh->addBoton('Lista', $this->_paramBotonLista);
//        $bh->addBoton('Volver', $this->_paramBotonVolver);
//        $this->_vista->barraherramientas = $bh->render();
//        $this->_layout->content = $this->_vista->render('AgregarCalendarioEscolarVista.php');
//        // render final layout
//        echo $this->_layout->render();
//    }
//    
//    /**
//     * Metodo para eliminar un calendario escolar.
//     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
//     * para no mostrarlo en las proximas oportunidades
//     * @param Array $arg 
//     * @access public
//     */
//    public function eliminarCalendarioEscolar ($arg='')
//    {
//	$where = implode(',', $arg);
//    	$values['eliminado']='1';
//    	$this->_modelo->actualizar('conta_calendarioescolar',$values,$arg);
//    	$this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS,'info');
//        parent::_redirect(LIVESITE .'index.php?option=CalendarioEscolar&sub=listar');
//    }

    
}
