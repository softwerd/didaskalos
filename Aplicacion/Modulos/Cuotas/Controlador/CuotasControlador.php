<?php

require_once 'Zend/View.php';
require_once LIBRERIAS . 'ControlarSesion.php';
require_once 'Aplicacion/Librerias/ControladorBase.php';

/**
 *  Clase Controladora del Modulo Cuotas
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Login
 * 
 */
class CuotasControlador extends ControladorBase 
{
    /**
     * Propiedad usada para la creacion de formularios
     * @var type Form 
     */
    protected static $_form;
    
    /**
     * Propiedad usada para enviar los elementos del formulario
     * @var type Array
     */
    private $_varForm = array();
    
    /**
     * Propiedad usada para establecer los campos de la BD
     * @var type Array
     * @example id,apellidos,nombres,domicilio,nro_doc,fechaNac,nacionalidad,sexo
     */
    private $_campos = array(
        'cuotas.id',
        'CONCAT_WS(", ", alumnos.apellidos,alumnos.nombres)',
        'cuotas.fecha_comprobante',
        'cuotas.comprobante',
        'cuotas.tipo_comprobante',
        'cuotas.nro_comprobante',
        'cuotas.condicion_venta',
        'cuotas.total',
        'cuotas.observaciones'
        );
    
    /**
     * Propiedad usada para establecer los títulos de los campos de la BD
     * @var type Array
     */
    private $_tituloCampos = array(
        'id'=>'Id',
        'CONCAT_WS(", ", alumnos.apellidos,alumnos.nombres)'=>'Alumno',
        'fecha_comprobante'=>'Fecha',
        'comprobante'=>'Comprobante',
        'tipo_comprobante'=>'Tipo',
        'nro_comprobante'=>'Nro.',
        'condicion_venta'=>'Forma Pago',
        'total'=>'Total',
        'observaciones'=>'Observaciones',
        );
    
    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevo = array(
        'href' => 'index.php?option=cuotas&sub=agregar',
        'classIcono' => 'icono-nuevo32'
        );
    
    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
//    private $_paramBotonLista = array(
//        'href' => 'index.php?option=cuotas&sub=listar',
//        'classIcono' => 'icono-lista32'
//        );
    
    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href'=>'index.php?option=cuotas');
    
    /**
     * Propiedad usa para configurar el botón GUARDAR
     * @var type Array
     */
    private $_paramBotonGuardar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"" ,
        );
    
    /**
     * Propiedad usada para configurar el botón LISTAR 
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href'=>'index.php?option=cuotas&sub=listar',
         'classIcono' => 'icono-lista32' 
    );
    /**
     * Propiedad usada para configurar el boton FILTRAR
     * @var type array
     */
//    private $_paramBotonFiltrar = array(
//        'class' => 'btn_filtrar' ,
//        'evento' => "onclick=\"javascript: submitbutton('filtrar')\"" ,
//        'href'=>"\"javascript:void(0);\""
//        );
    
    /**
     * Propiedad usada para manejar datos de los alumnos
     * @var type 
     */
    private $_modeloAlumnos;
    
    
    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */
    function __construct() 
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Cuotas/Vista');
        require_once DIRMODULOS . 'Cuotas/Modelo/CuotasModelo.php';
        require_once DIRMODULOS . 'Alumnos/Modelo/AlumnosModelo.php';
        $this->_modelo = new CuotasModelo();
        $this->_modeloAlumnos = new AlumnosModelo();
        $this->_varForm['id'] = '';
        $this->_varForm['cuenta'] = '';
        $this->_varForm['proveedor']='';
        $this->_varForm['fecha_comprobante']='';
        $this->_varForm['comprobante']='';
    }

    /**
     * Metodo para mostrar el menú principal de Cuotas
     */
    public function index() 
    {
        $this->_layout->content = $this->_vista->render('CuotasVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }
    
    /**
     * Método que lleva a la pag donde se cargan las cuotas
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar ()
    {
        require_once DIRMODULOS . 'Cuotas/Forms/CargaCuotas.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        $cuenta = 20;
        $alumnos_array = $this->_modeloAlumnos->listaAlumnosInscriptosId(2012, $salon='0', $filtro='');
        foreach ($alumnos_array as $alumnoBuscado) {
            $alumnos[] = array($alumnoBuscado['id'] => $alumnoBuscado['apellidos'].', '.$alumnoBuscado['nombres']);
        }
        $this->_form = new Form_CargaCuotas($cuenta, $alumnos);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $valuesCuotas = $values;
                unset ($valuesCuotas['mes']);
                unset ($valuesCuotas['anio']);
                if ($this->_controlar_nro_factura($values['nro_comprobante'], $values['alumno'], $values['comprobante'], $values['tipo_comprobante'])=='ok'){
                    $valuesCuotas['fecha_comprobante']=implode('/', array_reverse(explode('/', $valuesCuotas['fecha_comprobante'])));
                    $meses[] = $values['mes'];
                    $anio = $values['anio'];
                    $ultimoId = $this->_modelo->guardar($valuesCuotas);
                    if ($ultimoId > 0){
                        foreach ($meses[0] as $mes) {
                            $values = array('idCuota'=>$ultimoId,'mes'=>$mes, 'anio'=>$anio);
                            $this->_modelo->guardarDetalle($values);
                        }
                    }
                    $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS,'info');
                }else{
                    $this->_vista->mensajes = Mensajes::presentarMensaje(FACTURAEXISTE,'error');
                }
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo); 
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);

        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarCuotaVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
    /**
     * Metodo para editar los datos de una Cuota
     * @param Array $arg 
     * @access public
     */
    public function editar ($arg)
    {
        require_once DIRMODULOS . 'Cuotas/Forms/CargaCuotas.php';
        include_once LIBRERIAS . 'MyFechaHora.php';
        require_once LIBRERIAS . 'BarraHerramientas.php'; 
        $cuenta = 20;
        $alumnos_array = $this->_modeloAlumnos->listaAlumnosInscriptosId(2012, $salon='0', $filtro='');
        foreach ($alumnos_array as $alumnoBuscado) {
            $alumnos[] = array($alumnoBuscado['id'] => $alumnoBuscado['apellidos'].', '.$alumnoBuscado['nombres']);
        }
        $cuotaBuscada = $this->_modelo->buscarCuota(implode(',', $arg));
        if (is_object($cuotaBuscada)){
            $this->_varForm['id'] = $cuotaBuscada->id;
            $this->_varForm['cuenta'] = $cuotaBuscada->cuenta;
            $this->_varForm['alumno'] = $cuotaBuscada->alumno;
            $this->_varForm['comprobante'] = $cuotaBuscada->comprobante;
            $this->_varForm['tipo_comprobante'] = $cuotaBuscada->tipo_comprobante;
            $this->_varForm['fecha_comprobante'] = MyFechaHora::getFechaAr($cuotaBuscada->fecha_comprobante);
            $this->_varForm['nro_comprobante'] = $cuotaBuscada->nro_comprobante;
            $this->_varForm['condicion_venta']= $cuotaBuscada->condicion_venta;
            $this->_varForm['total'] = $cuotaBuscada->total;
            $this->_varForm['observaciones'] = $cuotaBuscada->observaciones;
        } else {
            $this->_varForm['id'] = '0';
            $this->_varForm['cuenta'] = '';
            $this->_varForm['alumno'] = '';
            $this->_varForm['comprobante'] = '';
            $this->_varForm['tipo_comprobante'] = '';
            $this->_varForm['fecha_comprobante'] = '';
            $this->_varForm['nro_comprobante'] = '';
            $this->_varForm['condicion_venta']= '';
            $this->_varForm['total'] = '';
            $this->_varForm['observaciones'] = '';
        }
        $detalleCuotaBuscada = $this->_modelo->buscarDetalle("idCuota=$cuotaBuscada->id");
        foreach ($detalleCuotaBuscada as $detalleCuota) {
            $this->_varForm['mes']=$detalleCuota->mes;
            $this->_varForm['anio']=$detalleCuota->anio;
        }
        $this->_form = new Form_CargaCuotas($cuenta, $alumnos, $this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fecha_comprobante'] = MyFechaHora::getFechaBd($values['fecha_comprobante']);
                $resultado = $this->_modelo->actualizar($values,$arg);
                if ($resultado >= 1){
                    $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS,'info');
                }else{
                    $this->_vista->mensajes = Mensajes::presentarMensaje($resultado, 'error');
                }
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardar);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Eliminar', 
                array('href' => 'index.php?option=cuotas&sub=eliminarCuota&id='. $this->_varForm['id']
                    ));

        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarCuotaVista.php');
        // render final layout
        echo $this->_layout->render();
    }
    
//    /**
//     * Metodo para eliminar una inscripcion.
//     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
//     * para no mostrarlo en las proximas oportunidades
//     * @param Array $arg 
//     * @access public
//     */
//    public function eliminarInscripcion ($arg='')
//    {
//	$where = implode(',', $arg);
//    	$values['eliminado']='1';
//    	$this->_modelo->actualizar('conta_inscripciones',$values,$arg);
//    	$this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS,'info');
//        parent::_redirect(LIVESITE .'/index.php?option=alumnos&sub=listarInscriptos');
//    }
//    /**
//     * Metodo para eliminar un alumno.
//     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
//     * para no mostrarlo en las proximas oportunidades
//     * @param Array $arg 
//     * @access public
//     */
//    public function eliminarAlumno ($arg='')
//    {
//	$where = implode(',', $arg);
//    	$values['eliminado']='1';
//    	$this->_modelo->actualizar('conta_alumnos',$values,$arg);
//    	$this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS,'info');
//        parent::_redirect(LIVESITE .'/index.php?option=alumnos&sub=listar');
//    }
    /**
     * Metodo para listar las cuotas pagadas.
     * @param Array $arg 
     * @access public
     * @see Librerias/Grilla.php, Librerias/BarraHerramientas.php
     */
    public function listar($arg = '')
    {
        require_once LIBRERIAS . 'JQGrid.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('CUOTAS - ALUMNOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=cuotas&sub=jsonListarCuotas');

        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'apellidos'" => "'Apellidos'",
            "'nombres'" => "'Nombres'",
            "'fecha_comprobante'" => "'Fecha'",
            "'comprobante'" => "'Comprobante'",
            "'tipo_comprobante'" => "'Tipo'",
            "'nro_comprobante'" => "'Nro.'",
            "'condicion_venta'" => "'Condición'",
            "'total'" => "'Total'",
            "'observaciones'" => "'Observaciones'"
        ));

        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '40', 'align'=>"right"),
            array('name' => 'Apellidos', 'index' => 'apellidos', 'width' => '120'),
            array('name' => 'Nombres', 'index' => 'nombres', 'width' => '130'),
            array('name' => 'Fecha', 'index' => 'fecha_comprobante', 'width' => '75', 'align'=>"right", 'formatter'=>'date'),
            array('name' => 'Comprobante', 'index' => 'comprobante', 'width' => '85'),
            array('name' => 'Tipo', 'index' => 'tipo_comprobante', 'width' => '40'),
            array('name' => 'Nro.', 'index' => 'nro_comprobante', 'width' => '85', 'align'=>"right"),
            array('name' => 'Condición', 'index' => 'condicion_venta', 'width' => '70'),
            array('name' => 'Total', 'index' => 'total', 'width' => '60', 'align'=>"right",'formatter'=>'currency', 'formatoptions'=>array('prefix'=>"$ ")),
            array('name' => 'Observaciones', 'index' => 'observaciones', 'width' => '110')
        ));
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(TRUE);
        $grilla->setActionOnDblClickRow('/index.php?option=cuotas&sub=editar&id=');

        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
//        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=cuotas';
        } else {
            $filtroBoton = '&lista=inscriptos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=cuotas&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_vista->grafico = $this->_mostrarGrafico();
        $this->_layout->content = $this->_vista->render('ListadoCuotasVista.php');
        echo $this->_layout->render();
    }

    public function jsonListarCuotas($arg = '')
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
                $orden = 'cuotas.id';
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
        $ciclo = date('Y');
        $json = new Zend_Json();
        $todos = $this->_modelo->listadoCuotas(0,'0', $orden, $ciclo);
        $total_pages = ceil(count($todos) / 30);
        $result = $this->_modelo->listadoCuotas($inicio,30, $orden, $ciclo);
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array($row['id'],
                $row['apellidos'],
                $row['nombres'],
                $row['fecha_comprobante'],
                $row['comprobante'],
                $row['tipo_comprobante'],
                $row['nro_comprobante'],
                $row['condicion_venta'],
                $row['total'],
                $row['observaciones'],
            );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    

    private function _mostrarGrafico()
    {
        $retorno = '<img src="http://chart.apis.google.com/chart?';
        $retorno .= 'cht=bvg'; //tipo de grafico
        $retorno .= '&chs=500x175'; //tamaño del grafico
        $retorno .= '&chd=t:45,32,17'; //chd Especifica los datos de la gráfica. Por ejemplo t:45,32,17. El primer carácter t indica que los datos se representan en formato de texto básico, es decir, valores comprendidos entre 0 y 100 separados por comas.
        $retorno .= '&chl=Diseño|Programación|Otras+cosas';  //chl Indica las etiquetas de cada elemento de la gráfica. Por ejemplo chl=Diseño|Programación|Otras+cosas.
        $retorno .= '&chtt=Cuotas+Por+Mes';  //chtt Establece el título de la gráfica. Por ejemplo: chtt=Posts+en+Theproc.es.
        $retorno .= '&chco=ff0000'; //chco Establece el color de los elementos del gráfico. Por ejemplo: ff0000.
//        En el caso de las gráficas de tipo tarta los distintos elementos se muestran con varias tonalidades de dicho color. También se puede especificar un color por elemento, como en el ejemplo de gráfica barras chco=FF9999|99FF99|9999FF.
        $retorno .= '&chf=bg,s,ffffff" id="grafico" alt="grafico">'; //chf Establece el color de fondo de la gráfica. En los ejemplos anteriores he usado de fondo el mismo color que el fondo del blog para que se integre perfectamente bg,s,fff6f6, es decir, background color, solid, fff6f6.
        return $retorno;

    }




//    public function exportar($filtro='')
//    {
//        require_once LIBRERIAS . 'ExportToExcel.php';
//        $exp = new ExportToExcel();
//        $filtro = $this->_crearFiltro($filtro);
//        if (isset ($filtro)){
//            if (! empty($_GET['pg'])) {
//                $pag = Input::get('pg');
//            } else {
//                $pag = 1;
//            }
//            $inicio = 0 + ($pag - 1) * 30;
//            if (! empty($_GET['sidx'])) {
//                $orden = Input::get('sidx');
//            } else {
//                $orden = 'id DESC';
//            }
//        }
//        if (Input::get('lista')=='inscriptos'){
//            $ciclo = date('Y');
//            $salon = 0;
//            $exp->setTitulo('LISTADO DE ALUMNOS INSCRIPTOS');
//            $tituloCampos = array(
//                    'id'=>'Id',
//                    'apellidos'=>'Apellidos',
//                    'nombres'=>'Nombres',
//                    'salon'=>'Salón'
//            );
//            foreach ($tituloCampos as $key => $value) {
//                $encCol[] = $value;
//            }
//            $encBD = array(
//                'id',
//                'apellidos',
//                'nombres',
//                'salon'
//            );
//            $exp->setFormatoCol(array(
//                     'id'=>'entero'
//            ));
//            $datos = $this->_modelo->listaAlumnosInscriptos($ciclo,$salon,$filtro);
//        }else{
//            $exp->setTitulo('LISTADO DE ALUMNOS');
//            foreach ($this->_tituloCampos as $key => $value) {
//                $encCol[] = $value;
//            }
//            $encBD = $this->_campos;
//            $exp->setFormatoCol(array(
//                     'id'=>'entero',
//                     'nro_doc'=>'entero',
//                     'fechaNac'=>'fecha'
//            ));
//            $inicio = 0;
//            $datos = $this->_modelo->listadoAlumnos($inicio, $orden, $filtro, $this->_campos);
//        }
//        $encCol = implode(',', $encCol);
//        $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar 0476');
//        $exp->setPiePagina('&RPag &P de &N');
//        $exp->setEncBD($encBD);
//        
//        $exp->setEncabezados($encCol);        
//        $exp->setIfTotales(FALSE);
//
//        $exp->exportar($datos);
//        
//        echo 'exportar';
//    }
//    
//    /**
//     * Metodo para editar los datos de un alumno
//     * @param Array $arg 
//     * @access public
//     */
//    public function editarInscripcion ($arg)
//    {
//        require_once DIRMODULOS . 'Alumnos/Forms/EditarInscripcion.php';
//        require_once LIBRERIAS . 'BarraHerramientas.php';  
//        /* traigo los siguientes campos de la lista de salones */
//        $campos = array('salones.id, salones.salon');
//        /* obtengo los datos de la bd */
//        $this->_varForm['listaSalones'] = $this->_modeloSalones->listadoSalones(0, 'salon', '', $campos) ;
//        /* Busco los datos de la inscripcion */
//        $inscripcionBuscada = $this->_modelo->buscarInscripto($arg);
//        if (is_object($inscripcionBuscada)){
//            $this->_varForm['id'] = $inscripcionBuscada->id;
//            /* Si encontró la inscripcion busco los datos del alumno */
//            $alumnoBuscado = $this->_modelo->buscarAlumno(array('id='.$inscripcionBuscada->idAlumno));
//            if (is_object($alumnoBuscado)){
//                $this->_varForm['idAlumno'] = $alumnoBuscado->id;
//                $this->_varForm['apellidos'] = $alumnoBuscado->apellidos;
//                $this->_varForm['nombres'] = $alumnoBuscado->nombres;
//            }else{
//                die ('No se encontró al alumno');
//            }
//            /* Si encontró la inscripcion busco los datos del salón */
//            $salonBuscado = $this->_modeloSalones->buscarSalon(array('id='.$inscripcionBuscada->idSalon));
//            if (is_object($salonBuscado)){
//                $this->_varForm['idSalon'] = $inscripcionBuscada->idSalon;
//                $this->_varForm['salon'] = $salonBuscado->salon;
//            }else{
//                die ('No se encontró los datos del salón');
//            }
//            $this->_varForm['aLectivo'] = $inscripcionBuscada->aLectivo;
//        } else {
//            die ('No se encontraron los datos de Inscripción');
//        }
//        $this->_form = new Form_EditarInscripcion($this->_varForm);
//        $this->_vista->form = $this->_form->mostrar();
//        if ($_POST) {
//            if ($this->_form->isValid($_POST)) {
//                $values = $this->_form->getValidValues($_POST);
//                $valores['id'] = $values['id'];
//                $valores['idSalon'] = $values['idSalon'];
//                $valores['idAlumno'] = $values['idAlumno'];
//                $valores['aLectivo'] = $values['aLectivo'];
//                $condicion = implode(',',$arg);
//                $this->_modelo->actualizarInscripcion($valores, $condicion);
//                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS,'info');
//            }
//        }
//        $bh = new BarraHerramientas($this->_vista);
//        $bh->addBoton('Guardar', $this->_paramBotonGuardarAlumno);
//        $bh->addBoton('Nuevo', $this->_paramBotonNuevaInscripcion); 
//        $bh->addBoton('Eliminar', 
//                array('href' => 'index.php?option=alumnos&sub=eliminarInscripcion&id='. $this->_varForm['id']
//                    ));
//        $bh->addBoton('Lista', $this->_paramBotonListaInscriptos);
//        $bh->addBoton('Volver', $this->_paramBotonVolver);
//
//        $this->_vista->barraherramientas = $bh->render();
//        $this->_layout->content = $this->_vista->render('AgregarAlumnosVista.php');
//        // render final layout
//        echo $this->_layout->render();
//    }

    private function _controlar_nro_factura($nro_factura='', $alumno='', $comprobante='', $tipo_comprobante='') 
    {
        $consulta = sprintf("nro_comprobante = '%s' && alumno = %d && comprobante = '%s' && tipo_comprobante = '%s'", $nro_factura, $alumno, $comprobante, $tipo_comprobante);
        $cuotaBuscada = $this->_modelo->buscarCuota($consulta);
        if (empty($gastoBuscado)) {
            $retorno = 'ok';
        } else {
            $retorno = 'Ya existe un comprobante con ese Número. Verifique por favor';
        }
        return $retorno;
    }
}
