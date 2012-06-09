<?php

require_once 'Zend/View.php';
require_once LIBRERIAS . 'ControlarSesion.php';

require_once 'Aplicacion/Librerias/ControladorBase.php';


/**
 *  Clase Controladora del Modulo Docentes
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Docentes
 * 
 */
class DocentesControlador extends ControladorBase
{
//
//    /**
//     * Propiedad usada para la creacion de formularios
//     * @var type Form 
//     */
//    protected static $_form;

    /**
     * Propiedad usada para enviar los elementos del formulario
     * @var type Array
     */
    private $_varForm = array();

    /**
     * Propiedad usada para establecer los campos de la BD
     * @var type Array
     */
    private $_campos = array(
        'id',
        'apellidos',
        'nombres',
        'domicilio',
        'nro_doc',
        'fechaNac',
        'nacionalidad',
        'sexo'
    );

    /**
     * Propiedad usada para establecer los títulos de los campos de la BD
     * @var type Array
     */
    private $_tituloCampos = array(
        'id' => 'Id',
        'apellidos' => 'Apellidos',
        'nombres' => 'Nombres',
        'domicilio' => 'Domicilio',
        'nro_doc' => 'Nro.Doc.',
        'fechaNac' => 'Fecha Nac.',
        'nacionalidad' => 'Nacionalidad',
        'sexo' => 'Sexo',
    );
    
    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=docentes&sub=agregar',
        'classIcono' => 'icono-nuevo32'
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
    
    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href'=>'index.php?option=docentes');
    
    private $_paramBotonVerHistorial = array(
        'class' => 'btn_Ver' ,
        'evento' => "onclick=\"javascript: submitbutton('verHistorialDocente')\"" ,
        'href'=>"\"javascript:void(0);\"",
        'titulo'=>'Historial'
        );
    
    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
    private $_paramBotonGuardar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"" ,
        );
    
   
    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=docentes&sub=listar',
        'classIcono' => 'icono-lista32'
        );


    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */

    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Docentes/Vista');
        require_once DIRMODULOS . 'Docentes/Modelo/DocentesModelo.php';
        $this->_modelo = new DocentesModelo();
    }

    public function index()
    {
        $this->_layout->content = $this->_vista->render('DocentesVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    /**
     * Método que lleva a la pag donde se cargan los Docentes
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar()
    {
        require_once DIRMODULOS . 'Docentes/Forms/CargaDocentes.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        require_once LIBRERIAS . 'MyFechaHora.php';
        $this->_form = new Form_CargaDocentes($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fechaNac'] = MyFechaHora::getFechaBd($values['fechaNac']);
                $this->_modelo->guardar($values);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        
        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarDocentesVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    /**
     * M
     */
    public function agregarHistorial($arg)
    {
        require_once DIRMODULOS . 'Docentes/Forms/CargaHistorialDocentes.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        require_once LIBRERIAS . 'MyFechaHora.php';
        $docente = $this->_modelo->buscarDocente($arg);
        $datos = '<div id="barraherramientas"><p  class="zend_form">Apellidos: ' . $docente->apellidos . '</p>';
        $datos .= '<p>Nombres: ' . $docente->nombres . '</p></div>';
        $this->_vista->datos = $datos;
        $this->_form = new Form_CargaHistorialDocentes($docente, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fechaInicio'] = MyFechaHora::getFechaBd($values['fechaInicio']);
                $values['fechaFin'] = MyFechaHora::getFechaBd($values['fechaFin']);
                $this->_modelo->guardarHistorial($values);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $this->_paramBotonLista = array(
        'href' => 'index.php?option=docentes&sub=historial&idDocente='.$docente->id,
        'classIcono' => 'icono-lista32'
        );
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        
        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarHistorialDocentesVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * Metodo para editar los datos de un docente
     * @param Array $arg 
     * @access public
     */
    public function editar($arg)
    {
        require_once DIRMODULOS . 'Docentes/Forms/CargaDocentes.php';
        include_once LIBRERIAS . 'MyFechaHora.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        require_once LIBRERIAS . 'Zend/File/Transfer.php';
        $docenteBuscado = $this->_modelo->buscarDocente($arg);
        if (is_object($docenteBuscado)) {
            $this->_varForm['id'] = $docenteBuscado->id;
            $this->_varForm['apellidos'] = $docenteBuscado->apellidos;
            $this->_varForm['nombres'] = $docenteBuscado->nombres;
            $this->_varForm['domicilio'] = $docenteBuscado->domicilio;
            $this->_varForm['nro_doc'] = $docenteBuscado->nro_doc;
            $this->_varForm['fechaNac'] = MyFechaHora::getFechaAr($docenteBuscado->fechaNac);
            $this->_varForm['nacionalidad'] = $docenteBuscado->nacionalidad;
            $this->_varForm['sexo'] = $docenteBuscado->sexo;
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['apellidos'] = '';
            $this->_varForm['nombres'] = '';
            $this->_varForm['domicilio'] = '';
            $this->_varForm['nro_doc'] = '';
            $this->_varForm['fechaNac'] = '';
            $this->_varForm['nacionalidad'] = '';
            $this->_varForm['sexo'] = '';
        }
        $this->_form = new Form_CargaDocentes($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fechaNac'] = MyFechaHora::getFechaBd($values['fechaNac']);
                $this->_modelo->actualizar($values, $arg);
                
                $file = $this->_form->foto;
                // Creamos el adapter de Zend_File_Transfer
                $adapter = new Zend_File_Transfer_Adapter_Http();
                // Set a new destination path for all files
                $ruta = realpath(IMG.'fotos/');
                $ruta .= '/id'.$values['id']. '.png';
//                $adapter->setDestination($ruta);
                $file = $adapter->getFileInfo();
                
//                echo $ruta;
                $adapter->addFilter('Rename', array('target' => $ruta , 'overwrite' => true));
//                if (!$adapter->isValid($file)) {
//                    $messages = $adapter->getMessages();
//                    $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
//                    var_dump ($messages);
//                    echo 'no es valido';
//                }
                if (!$adapter->receive()) {
                    $messages[] = implode('=>',$adapter->getMessages());
                    $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
//                    echo 'no recibio';
//                    var_dump ($messages);
                }
                $messages[] = DATOSGUARDADOS;
                $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Ver', $this->_paramBotonVerHistorial);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $foto = '<div id=mostrarFoto><img src="' . IMG . 'fotos/id' . $this->_varForm['id'] . '.png" class="mostrarFoto"/></div>';
        $this->_vista->foto = $foto;
        $this->_layout->content = $this->_vista->render('AgregarDocentesVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * Metodo para eliminar un docente.
     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
     * para no mostrarlo en las proximas oportunidades
     * @param Array $arg 
     * @access public
     */
    public function eliminar($arg='')
    {
        $where = implode(',', $arg);
        $values['eliminado'] = '1';
        $this->_modelo->actualizar($values, $arg);
        $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS, 'info');
        parent::_redirect(LIVESITE . '/index.php?option=docentes&sub=listar');
    }

    /**
     * Metodo para listar los docentes enla grilla.
     * @param Array $arg 
     * @access public
     * @see Librerias/Grilla.php, Librerias/BarraHerramientas.php
     */
    public function listar()
    {
        require_once LIBRERIAS . 'JQGrid.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('DOCENTES');
        $grilla->setUrl(LIVESITE . '/index.php?option=docentes&sub=jsonListarDocentes');
        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'apellidos'" => "'Apellidos'",
            "'nombre'" => "'Nombres'",
            "'domicilio'" => "'Domicilio'",
            "'nro_doc'" => "'Nro.Doc.'",
            "'fechaNac'" => "'Fecha Nac.'",
            "'nacionalidad'" => "'Nacionalidad'",
            "'sexo'" => "'Sexo'",
            "'foto'" => "'Foto'",
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '55', 'align'=>"right"),
            array('name' => 'Apellidos', 'index' => 'apellidos', 'width' => '150'),
            array('name' => 'Nombres', 'index' => 'nombres', 'width' => '150'),
            array('name' => 'Domicilio', 'index' => 'domicilio', 'width' => '180'),
            array('name' => 'Nro.Doc.', 'index' => 'nro_doc', 'width' => '100', 'align'=>"right"),
            array('name' => 'Fecha Nac.', 'index' => 'fechaNac', 'width' => '100', 'align'=>"right", 'formatter'=>'date'),
            array('name' => 'Nacionalidad', 'index' => 'nacionalidad', 'width' => '120'),
            array('name' => 'Sexo', 'index' => 'sexo', 'width' => '80'),
            array('name' => 'Foto', 'index' => 'foto', 'width' => '60', 'align' => 'center'),
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(TRUE);
        $grilla->setActionOnDblClickRow('/index.php?option=docentes&sub=editar&id=');

        $bh = new BarraHerramientas($this->_vista);
//        $bh->addBoton('Exportar', array('href' => 'index.php?option=alumnos&sub=exportar' . $filtroBoton,
//        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoDocentesVista.php');
        echo $this->_layout->render();
    }
    
    public function jsonListarDocentes($arg='')
    {
        $responce = '';
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
                $orden = 'apellidos, nombres';
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
        $campos = array('id','apellidos', 'nombres','domicilio','nro_doc','fechaNac','nacionalidad','sexo');
        $todos = count($this->_modelo->listadoDocentes(0,0,$orden,'',$campos ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->listadoDocentes($inicio, 30,$orden,'',$campos );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array(
                $row['id'],
                $row['apellidos'],
                $row['nombres'],
                $row['domicilio'],
                $row['nro_doc'],
                $row['fechaNac'],
                $row['nacionalidad'],
                $row['sexo'],
                '<img src="' . IMG . 'fotos/id' . $row['id'] . '.png" class="foto_usuario_32"',
                );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
public function jsonListarHistorialDocente($arg='')
    {
        $responce = '';
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
                $orden = 'id';
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
                $filtro = implode(",", $arg);
//                echo $filtro;
            } else {
                $filtroBoton = '';
                $filtro = '';
            }
        }
        $json = new Zend_Json();
        $campos = array('id','cargo', 'fechaInicio','fechaFin');
        $todos = count($this->_modelo->listadoHistorialDocente(0,0,$orden,'',$campos ));
        $total_pages = ceil($todos / 30);
        $result = $this->_modelo->listadoHistorialDocente($inicio, 30,$orden,'',$campos );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array(
                $row['id'],
                $row['cargo'],
                $row['fechaInicio'],
                $row['fechaFin']
                );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    
    /**
     * Método para editar el historial docente
     * Recibe como parámetro el id del docente
     * @param type $docente integer
     * @access public
     */
    public function historial($arg='')
    {
        require_once LIBRERIAS . 'JQGrid.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        require_once DIRMODULOS . 'Docentes/Forms/HistorialDocentes.php';
//        $id = Input::get('idDocente');
        $idDocente = 0;
        $docente = $this->_modelo->buscarDocente($arg);
        if (is_object($docente)){
            $idDocente = $docente;
            $idDoc = $idDocente->id;
        }else{
            $idDocente = NULL;
            $idDoc = 0;
        }
        $listadocentes = $this->_modelo->listadoDocentes(0,'apellidos','',array('id','apellidos','nombres'));
        $docentes[]=array('0'=>'Seleccione');
        foreach ($listadocentes as $docenteBuscado) {
            $docentes[] = array($docenteBuscado['id'] => $docenteBuscado['apellidos'].', '.$docenteBuscado['nombres']);
        }
        $this->_form = new Form_HistorialDocentes($idDocente, $docentes);
        $this->_vista->form = $this->_form->mostrar();
        
        $grilla = new JQGrid('grilla');
        if (is_object($docente)){
            $grilla->setTitulo('HISTORIAL DOCENTE: ' . $docente->apellidos . ', ' . $docente->nombres);
        }else{
            $grilla->setTitulo('HISTORIAL DOCENTE');
        }
        $grilla->setUrl(LIVESITE . '/index.php?option=docentes&sub=jsonListarHistorialDocente');
        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'cargo'" => "'Cargo'",
            "'fechaInicio'" => "'Fecha Inicio'",
            "'fechaFin'" => "'Fecha Fin'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '55', 'align'=>"right"),
            array('name' => 'Cargo', 'index' => 'cargo', 'width' => '200'),
            array('name' => 'Fecha Inicio', 'index' => 'fechaInicio', 'width' => '150'),
            array('name' => 'Fecha Fin', 'index' => 'fechaFin', 'width' => '180')
        ));
        $grilla->setOnSelectRow(FALSE);
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
//        $grilla->setOnDblClickRow(TRUE);
//        $grilla->setActionOnDblClickRow('/index.php?option=docentes&sub=editar&id=');

        $bh = new BarraHerramientas($this->_vista);
//        $bh->addBoton('Exportar', array('href' => 'index.php?option=alumnos&sub=exportar' . $filtroBoton,
//        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('HistorialDocentesVista.php');
        echo $this->_layout->render();  
    }

    private function _crearFiltro($pag)
    {
        $filtro = '';
        $valorRecibido = Input::get('valor');
        if ($valorRecibido != 'valor' && $valorRecibido != '') {
            $campoRecibido = Input::get('campo');
            $clase = self::_ifExisteClase($campoRecibido);
            $file = DIRMODULOS . 'Docentes/Controlador/' . $clase . '.php';
            require_once ($file);
            $filtro = new $clase($valorRecibido);
        }
        return $filtro;
    }

    private function _ifExisteClase($class)
    {
        $file = DIRMODULOS . 'Docentes/Controlador/' . 'Filtro' . ucfirst($class) . '.php';
        if (!file_exists($file)) {
            die('No se puede crear el filtro');
        }
//	require_once ($file);
        return 'Filtro' . ucfirst($class);
    }

    public function exportar($filtro='')
    {
        require_once LIBRERIAS . 'ExportToExcel.php';
        $exp = new ExportToExcel();
        $exp->setTitulo('LISTADO DE DOCENTES');
        $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar 0476');
        $exp->setPiePagina('&RPag &P de &N');
        foreach ($this->_tituloCampos as $key => $value) {
            $encCol[] = $value;
        }
        $encCol = implode(',', $encCol);
        $encBD = $this->_campos;
        $exp->setEncBD($encBD);
        $exp->setFormatoCol(array(
            'id' => 'entero',
            'nro_doc' => 'entero',
            'fechaNac' => 'fecha'
        ));
        $exp->setEncabezados($encCol);
        $exp->setIfTotales(FALSE);
        $inicio = 0;
        if (isset($filtro)) {
            if (!empty($_GET['pg'])) {
                $pag = Input::get('pg');
            } else {
                $pag = 1;
            }
            $inicio = 0 + ($pag - 1) * 30;
            if (!empty($_GET['sidx'])) {
                $orden = Input::get('sidx');
            } else {
                $orden = 'id DESC';
            }
        }
        $filtro = $this->_crearFiltro($pag);
        $datos = $this->_modelo->listadoDocentes($inicio, $orden, $filtro, $this->_campos);
        $exp->exportar($datos);

        echo 'exportar';
    }

    private function _manejarFoto($values)
    {
        // Creamos el adapter de Zend_File_Transfer
        $adapter = new Zend_File_Transfer_Adapter_Http();
        $file = $adapter->getFileInfo();
        // Set a new destination path for all files
        $adapter->addFilter('Rename', array($this->_rutaFoto . $values['id'] . '.png', 'overwrite' => true));
        if (!$adapter->receive()) {
            $messages = $adapter->getMessages();
            $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
        }
        if (!$adapter->isValid($file)) {
            $messages = $adapter->getMessages();
            $this->_vista->mensajes = Mensajes::presentarMensaje($messages, 'info');
        }
    }

}
