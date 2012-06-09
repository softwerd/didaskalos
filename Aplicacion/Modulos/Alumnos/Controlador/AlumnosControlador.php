<?php

//require_once 'class/Usuario.php';
require_once 'Zend/View.php';
require_once LIBRERIAS . 'ControlarSesion.php';
//require_once 'Aplicacion/Librerias/Input.php';
//require_once 'Zend/Session/Namespace.php';
require_once 'Aplicacion/Librerias/ControladorBase.php';
//require_once 'Aplicacion/modelos/LoginModelo.php';
//require_once 'Aplicacion/Librerias/JQGrid.php';

/**
 *  Clase Controladora del Modulo Login
 *  @author Walter Ruiz Diaz
 *  @see ControladorBase
 *  @category Controlador
 *  @package Login
 * 
 */
class AlumnosControlador extends ControladorBase
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
        'sexo' => 'Sexo'
    );

    /**
     * Propiedad usada para acceder a los datos de la tabla salones
     * @var type SalonesModelo
     */
    private $_modeloSalones;

    /**
     * Propiedad usada para acceder a los datos de la tabla aLectivo
     * @var type CalendarioEscolarModelo
     */
    private $_modeloALectivo;

    /**
     * Propiedad usada para configurar el boton NUEVO
     * @var type Array
     */
    private $_paramBotonNuevoAlumno = array(
        'href' => 'index.php?option=alumnos&sub=agregar',
        'classIcono' => 'icono-nuevoAlumno32'
    );

    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonListaAlumnos = array(
        'href' => 'index.php?option=alumnos&sub=listar',
        'classIcono' => 'icono-listaAlumnos32'
    );

    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array('href' => 'index.php?option=alumnos');

    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
    private $_paramBotonGuardarAlumno = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"",
    );

    /**
     * Propiedad usada para configurar el botón NUEVA INSCRIPCION
     * @var type Array
     */
    private $_paramBotonNuevaInscripcion = array(
        'href' => 'index.php?option=alumnos&sub=inscribir',
        'classIcono' => 'icono-nuevaInscripcion32',
    );

    /**
     * Propiedad usada para configurar el botón LISTAR INSCRIPTOS
     * @var type Array
     */
    private $_paramBotonListaInscriptos = array(
        'href' => 'index.php?option=alumnos&sub=listarInscriptos',
        'classIcono' => 'icono-listaInscripcion32'
    );

    /**
     * Propiedad usada para configurar el boton FILTRAR
     * @var type array
     */
    private $_paramBotonFiltrar = array(
        'class' => 'btn_filtrar',
        'evento' => "onclick=\"javascript: submitbutton('filtrar')\"",
        'href' => "\"javascript:void(0);\""
    );


    /* Construccion de la clase usando la clase padre
     * Se asignan los path a las vistas
     * Se construye el objeto modelo a utilizar
     */

    function __construct()
    {
        parent::__construct();
        $this->_vista->addScriptPath(DIRMODULOS . 'Alumnos/Vista');
        require_once DIRMODULOS . 'Alumnos/Modelo/AlumnosModelo.php';
        require_once DIRMODULOS . 'Salones/Modelo/SalonesModelo.php';
        require_once DIRMODULOS . 'CalendarioEscolar/Modelo/CalendarioEscolarModelo.php';
        $this->_modelo = new AlumnosModelo();
        $this->_modeloSalones = new SalonesModelo();
        $this->_modeloALectivo = new CalendarioEscolarModelo();
    }

    /**
     * Metodo para mostrar el menú principal de Alumnos
     */
    public function index()
    {
        $this->_layout->content = $this->_vista->render('AlumnosVista.php');
        $this->_layout->setLayout('layout');
        echo $this->_layout->render();
    }

    /**
     * Método para inscribir un alumno en un salón
     * El salón y el alumno deben existir
     */
    public function inscribir()
    {
        require_once DIRMODULOS . 'Alumnos/Forms/InscribirAlumnos.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        /* esto es para que la lista de alumnos no tenga limite */
        $this->_modelo->setLimite(0);
        /* traigo la lista de inscriptos */
//        $listaInscriptos = $this->_modelo->listaInscriptos();
        $ciclo = date('Y');
        /* traigo la lista de alumnos aptos para inscripcion */
        $listaAlumnos = $this->_modelo->alumnosParaInscripcion($ciclo);
        /* traigo los siguientes campos de la lista de salones */
        $campos = array('salones.id, salones.salon');
        /* traigo la lista sin limites */
        $this->_modeloSalones->setLimite(0);
        /* obtengo los datos de la bd */
        $listaSalones = $this->_modeloSalones->listadoSalones(0,0, 'salon', '', $campos);
        /* obtengo los datos de los ciclos lectivos */
        $listaCiclosLectivos = $this->_modeloALectivo->listadoCalendarios(0, 'aLectivo', '');
        foreach ($listaCiclosLectivos as $cicloLectivo) {
            $cicloLectivoArray[] = Array($cicloLectivo['aLectivo'] => $cicloLectivo['aLectivo']);
        }

        /* creo el formulario */
        $this->_form = new Form_InscribirAlumnos($listaAlumnos, $listaSalones, $cicloLectivoArray);
        /* pongo el formulario en la vista */
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $alumnos[] = $values['alumnos'];
                $salon = $values['salones'];
                $aLectivo = $values['aLectivo'];
                foreach ($alumnos[0] as $alumno) {
                    $values = array('idAlumno' => $alumno, 'idSalon' => $salon, 'aLectivo' => $aLectivo);
                    $this->_modelo->inscribir($values);
                }
                parent::_redirect('index.php?option=alumnos&sub=listarInscriptos');
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardarAlumno);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevaInscripcion);
        $bh->addBoton('Lista', $this->_paramBotonListaInscriptos);
        $bh->addBoton('Volver', $this->_paramBotonVolver);

        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarAlumnosVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * Método que lleva a la pag donde se cargan los alumnos
     * Recibe los datos a guardar por POST y los guarda.
     * @return void
     */
    public function agregar()
    {
        require_once DIRMODULOS . 'Alumnos/Forms/CargaAlumnos.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        require_once LIBRERIAS . 'MyFechaHora.php';
        $this->_form = new Form_CargaAlumnos($this->_varForm);
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
        $bh->addBoton('Guardar', $this->_paramBotonGuardarAlumno);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevoAlumno);
        $bh->addBoton('Lista', $this->_paramBotonListaAlumnos);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarAlumnosVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * Metodo para editar los datos de un alumno
     * @param Array $arg 
     * @access public
     */
    public function editar($arg)
    {
        require_once DIRMODULOS . 'Alumnos/Forms/CargaAlumnos.php';
        include_once LIBRERIAS . 'MyFechaHora.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        $alumnoBuscado = $this->_modelo->buscarAlumno($arg);
        if (is_object($alumnoBuscado)) {
            $this->_varForm['id'] = $alumnoBuscado->id;
            $this->_varForm['apellidos'] = $alumnoBuscado->apellidos;
            $this->_varForm['nombres'] = $alumnoBuscado->nombres;
            $this->_varForm['domicilio'] = $alumnoBuscado->domicilio;
            $this->_varForm['nro_doc'] = $alumnoBuscado->nro_doc;
            $this->_varForm['fechaNac'] = MyFechaHora::getFechaAr($alumnoBuscado->fechaNac);
            $this->_varForm['nacionalidad'] = $alumnoBuscado->nacionalidad;
            $this->_varForm['sexo'] = $alumnoBuscado->sexo;
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
        $this->_form = new Form_CargaAlumnos($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $values['fechaNac'] = MyFechaHora::getFechaBd($values['fechaNac']);
                $this->_modelo->actualizar('conta_alumnos',$values, $arg);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardarAlumno);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevoAlumno);
        $bh->addBoton('Eliminar', array('href' => 'index.php?option=alumnos&sub=eliminarAlumno&id=' . $this->_varForm['id']
        ));

        $bh->addBoton('Lista', $this->_paramBotonListaAlumnos);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarAlumnosVista.php');
        // render final layout
        echo $this->_layout->render();
    }

    /**
     * Metodo para eliminar una inscripcion.
     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
     * para no mostrarlo en las proximas oportunidades
     * @param Array $arg 
     * @access public
     */
    public function eliminarInscripcion($arg='')
    {
        $where = implode(',', $arg);
        $values['eliminado'] = '1';
        $this->_modelo->actualizar('conta_inscripciones', $values, $arg);
        $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS, 'info');
        parent::_redirect(LIVESITE . '/index.php?option=alumnos&sub=listarInscriptos');
    }

    /**
     * Metodo para eliminar un alumno.
     * La eliminacion no es real, sino que establece el campo 'eliminado' en verdadero
     * para no mostrarlo en las proximas oportunidades
     * @param Array $arg 
     * @access public
     */
    public function eliminarAlumno($arg='')
    {
        $where = implode(',', $arg);
        $values['eliminado'] = '1';
        $this->_modelo->actualizar('conta_alumnos', $values, $arg);
        $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSELIMINADOS, 'info');
        parent::_redirect(LIVESITE . '/index.php?option=alumnos&sub=listar');
    }

    /**
     * Metodo para listar los alumnos enla grilla.
     * @param Array $arg 
     * @access public
     * @see Librerias/Grilla.php, Librerias/BarraHerramientas.php
     */
    public function listar($arg='')
    {
        require_once LIBRERIAS . 'JQGrid.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('ALUMNOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=alumnos&sub=jsonListarAlumnos');
        $grilla->setColNames(array(
            "'id'"=>"'Id'",
            "'apellidos'" => "'Apellidos'",
            "'nombres'" => "'Nombres'",
            "'domicilio'" => "'Domicilio'",
            "'nro_doc'" => "'Nro.Doc.'",
            "'fechaNac'" => "'Fecha Nac.'",
            "'nacionalidad'" => "'Nacionalidad'",
            "'salon'" => "'Salón'",
            "'division'" => "'Div.'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '45', 'align'=>"right"),
            array('name' => 'Apellidos', 'index' => 'apellidos', 'width' => '145'),
            array('name' => 'Nombres', 'index' => 'nombres', 'width' => '155'),
            array('name' => 'Domicilio', 'index' => 'domicilio', 'width' => '245'),
            array('name' => 'Nro.Doc.', 'index' => 'nro_doc', 'width' => '75', 'align'=>"right"),
            array('name' => 'Fecha Nac.', 'index' => 'fechaNac', 'width' => '70', 'align'=>"right", 'formatter'=>'date' ),
            array('name' => 'Nacionalidad', 'index' => 'nacionalidad', 'width' => '75'),
            array('name' => 'Salón', 'index' => 'salon', 'width' => '125'),
            array('name' => 'Div.', 'index' => 'division', 'width' => '45')
        ));
        $grilla->setOnSelectRow(FALSE);
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(TRUE);
        $grilla->setActionOnDblClickRow('/index.php?option=alumnos&sub=editar&id=');

        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevaInscripcion);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=inscriptos';
        } else {
            $filtroBoton = '&lista=inscriptos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=alumnos&sub=exportar' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoAlumnosVista.php');
        echo $this->_layout->render();
    }

    /**
     * Metodo para listar los alumnos inscriptos en la grilla.
     * @param Array $arg 
     * @access public
     * @see Librerias/JQGrid.php, Librerias/BarraHerramientas.php
     */
    public function listarInscriptos($arg='')
    {
        require_once LIBRERIAS . 'JQGrid.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        $grilla = new JQGrid('grilla');
        $grilla->setTitulo('ALUMNOS INSCRIPTOS');
        $grilla->setUrl(LIVESITE . '/index.php?option=alumnos&sub=jsonListarInscriptos');
        $grilla->setColNames(array(
            "'id'" => "'Id'",
            "'apellidos'" => "'Apellidos'",
            "'nombres'" => "'Nombres'",
            "'salon'" => "'Salón'",
            "'division'" => "'Div.'"
        ));
        
        $grilla->setColModel(array(
            array('name' => 'Id', 'index' => 'id', 'width' => '55', 'align'=>"right"),
            array('name' => 'Apellidos', 'index' => 'apellidos', 'width' => '155'),
            array('name' => 'Nombres', 'index' => 'nombres', 'width' => '155'),
            array('name' => 'Salón', 'index' => 'salon', 'width' => '255'),
            array('name' => 'Div.', 'index' => 'division', 'width' => '55')
        ));
        $grilla->setOnSelectRow(FALSE);
        $grilla->setIfBotonEditar(FALSE);
        $grilla->setIfBotonEliminar(FALSE);
        $grilla->setIfBotonBuscar(FALSE);
        $grilla->setOnDblClickRow(TRUE);
        $grilla->setActionOnDblClickRow('/index.php?option=alumnos&sub=editarInscripcion&id=');

        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevaInscripcion);
        $bh->addBoton('Filtrar', $this->_paramBotonFiltrar);
        if (is_array($arg)) {
            $filtroBoton = '&' . implode("&", $arg) . '&lista=inscriptos';
        } else {
            $filtroBoton = '&lista=inscriptos';
        }
        $bh->addBoton('Exportar', array('href' => 'index.php?option=alumnos&sub=exportarInscriptos' . $filtroBoton,
        ));
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $this->_vista->barraherramientas = $bh->render();
        $this->_vista->grid = $grilla->incluirJs();
        $this->_layout->content = $this->_vista->render('ListadoAlumnosVista.php');
        echo $this->_layout->render();
    }

    public function jsonListarInscriptos($arg='')
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
                $orden = 'salones.salon, salones.division, alumnos.apellidos, alumnos.nombres';
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
        $todos = $this->_modelo->ListaAlumnosInscriptos(0, 0,$ciclo,$orden,0,'');
        $total_pages = ceil(count($todos) / 30);
        $result = $this->_modelo->ListaAlumnosInscriptos($inicio, 30, $ciclo,$orden,0,'' );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = count($todos);
        $i = 0;

        foreach ($result as $row) {
            $responce->rows[$i]['id'] = $row['id'];
            $responce->rows[$i]['cell'] = array($row['id'],
                $row['apellidos'],
                $row['nombres'],
                $row['salon'],
                $row['division']);
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }
    
    public function jsonListarAlumnos($arg='')
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
                $orden = 'alumnos.apellidos, alumnos.nombres';
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
        $todos = $this->_modelo->listadoAlumnos(0, 0,$ciclo,$orden,'');
        $total_pages = ceil(count($todos) / 30);
        $result = $this->_modelo->listadoAlumnos($inicio, 30, $ciclo,$orden,'' );
        $count = count($result);
        $responce->page = $pag;
        $responce->total = $total_pages;
        $responce->records = count($todos);
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
                $row['salon'],
                $row['division'],
                );
            $i++;
        }
        // return the formated data
        echo $json->encode($responce);
    }

    /**
     * Metodo para traer la lista de los alumnos inscriptos.
     * @param string $arg. El ciclo lectivo
     * @access public
     */
    public function ajaxAlumnosParaInscribir($arg='')
    {
        if ($arg == '') {
            $ciclo = date('Y');
        } else {
            $ciclo = $arg[0];
        }
        print_r($arg);
        $salon = 0;
        $fuenteDatos = $this->_modelo->alumnosParaInscripcion($ciclo);
        /** Lista de alumnos * */
        $retorno = '';
        $i = 0;
        foreach ($fuenteDatos as $alumno) {
//            $opcionListaAlumnos[]=array($alumno->id => $alumno->apellidos .', ' . $alumno->nombres);
            $retorno .= '<optgroup id="alumnos-optgroup-0" label="' . $i . '">';
            $retorno .= '<option value="' . $alumno->id . '">';
            $retorno .= $alumno->apellidos . ', ' . $alumno->nombres . '</option>';
            $retorno .= ' </optgroup>';
            $i++;
        }
        print_r($retorno);
        return $retorno;
    }

    private function _mostrarGrafico()
    {
        $retorno = '<img src="http://chart.apis.google.com/chart?cht=';
        $retorno .= 'p3'; //tipo de grafico
        $retorno .= '&chs=400x175'; //tamaño del grafico
        $retorno .= '&chd=t:45,32,17'; //chd Especifica los datos de la gráfica. Por ejemplo t:45,32,17. El primer carácter t indica que los datos se representan en formato de texto básico, es decir, valores comprendidos entre 0 y 100 separados por comas.
        $retorno .= '&chl=Diseño|Programación|Otras+cosas';  //chl Indica las etiquetas de cada elemento de la gráfica. Por ejemplo chl=Diseño|Programación|Otras+cosas.
        $retorno .= '&chtt=Alumnos+Inscriptos';  //chtt Establece el título de la gráfica. Por ejemplo: chtt=Posts+en+Theproc.es.
        $retorno .= '&chco=ff0000'; //chco Establece el color de los elementos del gráfico. Por ejemplo: ff0000.
//        En el caso de las gráficas de tipo tarta los distintos elementos se muestran con varias tonalidades de dicho color. También se puede especificar un color por elemento, como en el ejemplo de gráfica barras chco=FF9999|99FF99|9999FF.
        $retorno .= '&chf=bg,s,ffffff" id="grafico" alt="grafico">'; //chf Establece el color de fondo de la gráfica. En los ejemplos anteriores he usado de fondo el mismo color que el fondo del blog para que se integre perfectamente bg,s,fff6f6, es decir, background color, solid, fff6f6.
        return $retorno;
    }

    private function _crearFiltro($pag)
    {
        $filtro = '';
        $valorRecibido = Input::get('valor');
        if ($valorRecibido != 'valor' && $valorRecibido != '') {
            $campoRecibido = Input::get('campo');
            $clase = self::_ifExisteClase($campoRecibido);
            $file = DIRMODULOS . 'Alumnos/Controlador/' . $clase . '.php';
            require_once ($file);
            $filtro = new $clase($valorRecibido);
        }
        return $filtro;
    }

    private function _ifExisteClase($class)
    {
        $file = DIRMODULOS . 'Alumnos/Controlador/' . 'Filtro' . ucfirst($class) . '.php';
        if (!file_exists($file)) {
            die('No se puede crear el filtro');
        }
        return 'Filtro' . ucfirst($class);
    }

    public function exportarInscriptos($filtro='')
    {
        require_once LIBRERIAS . 'ExportToExcel.php';
        $exp = new ExportToExcel();
        $filtro = $this->_crearFiltro($filtro);
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
                $orden = 'salones.salon, salones.division, alumnos.apellidos, alumnos.nombres';
            }
        }
        if (Input::get('lista') == 'inscriptos') {
            $ciclo = date('Y');
            $salon = 0;
            $exp->setTitulo('LISTADO DE ALUMNOS INSCRIPTOS');
            $tituloCampos = array(
                'id' => 'Id',
                'apellidos' => 'Apellidos',
                'nombres' => 'Nombres',
                'salon' => 'Salón',
                'division'=>'Div.'
            );
            foreach ($tituloCampos as $key => $value) {
                $encCol[] = $value;
            }
            $encBD = array(
                'id',
                'apellidos',
                'nombres',
                'salon',
                'division'
            );
            $exp->setFormatoCol(array(
                'id' => 'entero'
            ));
            $datos = $this->_modelo->listaAlumnosInscriptos(0, 0,$ciclo,$orden, $salon, $filtro);
        } else {
            $exp->setTitulo('LISTADO DE ALUMNOS');
            foreach ($this->_tituloCampos as $key => $value) {
                $encCol[] = $value;
            }
            $encBD = $this->_campos;
            $exp->setFormatoCol(array(
                'id' => 'entero',
                'nro_doc' => 'entero',
                'fechaNac' => 'fecha'
            ));
            $inicio = 0;
            $datos = $this->_modelo->listadoAlumnos($inicio, $orden, $filtro, $this->_campos);
        }
        $encCol = implode(',', $encCol);
        $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar 0476');
        $exp->setPiePagina('&RPag &P de &N');
        $exp->setEncBD($encBD);

        $exp->setEncabezados($encCol);
        $exp->setIfTotales(FALSE);

        $exp->exportar($datos);

        echo 'exportar';
    }

    
    public function exportar($filtro='')
    {
        $salon = '';
        $inicio = '';
        require_once LIBRERIAS . 'ExportToExcel.php';
        $exp = new ExportToExcel();
        $filtro = $this->_crearFiltro($filtro);
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
                $orden = 'salones.salon, salones.division, alumnos.apellidos, alumnos.nombres';
            }
        }
        
        $ciclo = date('Y');
        $salon = 0;
        $exp->setTitulo('LISTADO DE ALUMNOS');
        $tituloCampos = array(
            'id' => 'Id',
            'apellidos' => 'Apellidos',
            'nombres' => 'Nombres',
            'nro_doc' => 'Nro.Doc.',
            'fechaNac' => 'Fecha Nac.',
            'salon' => 'Salon',
            'division'=>'Div.'
        );
        foreach ($tituloCampos as $key => $value) {
            $encCol[] = $value;
        }
        $encBD = array(
            'id',
            'apellidos',
            'nombres',
            'nro_doc',
            'fechaNac',
            'salon',
            'division'
        );
        $exp->setFormatoCol(array(
            'id' => 'entero'
        ));
        $datos = $this->_modelo->listaAlumnosExport(0, 0,$ciclo,$orden, 0, $filtro);
        $datos = $this->_modelo->listadoAlumnos(0, 0, $ciclo,$orden,'' );
//        print_r($datos);
        $encCol = implode(',', $encCol);
//        print_r($encCol);
        $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar 0476');
        $exp->setPiePagina('&RPag &P de &N');
        $exp->setEncBD($encBD);

        $exp->setEncabezados($encCol);
        $exp->setIfTotales(FALSE);

        $exp->exportar($datos);

        echo 'exportar';
    }
    
    
    /**
     * Metodo para editar los datos de un alumno
     * @param Array $arg 
     * @access public
     */
    public function editarInscripcion($arg)
    {
        require_once DIRMODULOS . 'Alumnos/Forms/EditarInscripcion.php';
        require_once LIBRERIAS . 'BarraHerramientas.php';
        /* traigo los siguientes campos de la lista de salones */
        $campos = array('salones.id, salones.salon');
        /* obtengo los datos de la bd */
        $this->_varForm['listaSalones'] = $this->_modeloSalones->listadoSalones(0,0, 'salon', '', $campos);
        /* Busco los datos de la inscripcion */
        $inscripcionBuscada = $this->_modelo->buscarInscripto($arg);
        if (is_object($inscripcionBuscada)) {
            $this->_varForm['id'] = $inscripcionBuscada->id;
            /* Si encontró la inscripcion busco los datos del alumno */
            $alumnoBuscado = $this->_modelo->buscarAlumno(array('id=' . $inscripcionBuscada->idAlumno));
            if (is_object($alumnoBuscado)) {
                $this->_varForm['idAlumno'] = $alumnoBuscado->id;
                $this->_varForm['apellidos'] = $alumnoBuscado->apellidos;
                $this->_varForm['nombres'] = $alumnoBuscado->nombres;
            } else {
                die('No se encontró al alumno');
            }
            /* Si encontró la inscripcion busco los datos del salón */
            $salonBuscado = $this->_modeloSalones->buscarSalon(array('id=' . $inscripcionBuscada->idSalon));
            if (is_object($salonBuscado)) {
                $this->_varForm['idSalon'] = $inscripcionBuscada->idSalon;
                $this->_varForm['salon'] = $salonBuscado->salon;
            } else {
                die('No se encontró los datos del salón');
            }
            $this->_varForm['aLectivo'] = $inscripcionBuscada->aLectivo;
        } else {
            die('No se encontraron los datos de Inscripción');
        }
        $this->_form = new Form_EditarInscripcion($this->_varForm);
        $this->_vista->form = $this->_form->mostrar();
        if ($_POST) {
            if ($this->_form->isValid($_POST)) {
                $values = $this->_form->getValidValues($_POST);
                $valores['id'] = $values['id'];
                $valores['idSalon'] = $values['idSalon'];
                $valores['idAlumno'] = $values['idAlumno'];
                $valores['aLectivo'] = $values['aLectivo'];
                $condicion = implode(',', $arg);
                $this->_modelo->actualizarInscripcion($valores, $condicion);
                $this->_vista->mensajes = Mensajes::presentarMensaje(DATOSGUARDADOS, 'info');
            }
        }
        $bh = new BarraHerramientas($this->_vista);
        $bh->addBoton('Guardar', $this->_paramBotonGuardarAlumno);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevaInscripcion);
        $bh->addBoton('Eliminar', array('href' => 'index.php?option=alumnos&sub=eliminarInscripcion&id=' . $this->_varForm['id']
        ));
        $bh->addBoton('Lista', $this->_paramBotonListaInscriptos);
        $bh->addBoton('Volver', $this->_paramBotonVolver);

        $this->_vista->barraherramientas = $bh->render();
        $this->_layout->content = $this->_vista->render('AgregarAlumnosVista.php');
        // render final layout
        echo $this->_layout->render();
    }

}
