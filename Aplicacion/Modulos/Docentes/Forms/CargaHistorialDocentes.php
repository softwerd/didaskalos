<?php

require_once LIBRERIAS . 'Zend/Form.php';
require_once LIBRERIAS . 'Form/Decorator/IconoInformacion.php';
//require_once LIBRERIAS . 'Form/Decorator/FotoInformacion.php';

/**
 *  Clase para armar el formulario donde se cargan los docentes
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Docentes
 */
class Form_CargaHistorialDocentes extends Zend_Form
{

    private $_varForm = array();
    private $_docente;
//    private $_config;
    public $elementRequeridoDecorators = array(
        'ViewHelper',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array('IconoInformacion', array('placement' => 'append')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementDecorators = array(
        'ViewHelper',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementZendDecorators = array(
        'UiWidgetElement',
        array('Description', array('tag' => 'span', 'class' => 'element-description')),
        array('Errors'),
//        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );

    function __construct($docente, $historial = null)
    {
        $this->addPrefixPath('Aplicacion_Librerias_Form_Decorator', 'Aplicacion/Librerias/Form/Decorator', 'decorator');
        $this->addPrefixPath('Aplicacion_Librerias_ZendX_JQuery_Form_Decorator', 'Aplicacion/Librerias/ZendX/JQuery/Form/Decorator', 'decorator');
        $this->_varForm = $historial;
        $this->_docente = $docente;
        parent::__construct();
    }

    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorIdDocente = $this->_varForm['idDocente'];
            $valorCargo = $this->_varForm['cargo'];
            $valorFechaInicio = $this->_varForm['fechaInicio'];
            $valorFechaFin = $this->_varForm['fechaFin'];
        }else{
            $valorId = 0;
            $valorIdDocente = $this->_docente->id;
            $valorCargo = '';
            $valorFechaInicio = '';
            $valorFechaFin = '';
        }
        if ($valorId == 0) {
            $this->setAction('index.php?option=docentes&sub=agregarHistorial&id=' . $valorIdDocente);
        } else {
            $this->setAction('index.php?option=docentes&sub=editarHistorial&id=' . $valorIdDocente);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmdocentes');
        $this->setAttrib('enctype', 'multipart/form-data');

        /** Id  * */
        $id = $this->createElement('hidden', 'id', array('value' => $valorId));
        /** IdDocente  * */
        $idDocente = $this->createElement('hidden', 'idDocente', array('value' => $valorIdDocente));
        /** Cargo * */
        $cargo = $this->createElement('text', 'cargo', array('decorators' => $this->elementDecorators, 'value' => $valorCargo));
        $cargo->setLabel('Cargo:');
        
        /** Fecha de Inicio **/
        $fechaInicio = $this->createElement('text', 'fechaInicio',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorFechaInicio));
        $fechaInicio->setLabel('Fecha Inicio:');
        $fechaInicio->setRequired(true);
        
        /** Fecha de Fin **/
        $fechaFin = $this->createElement('text', 'fechaFin',array('decorators' => $this->elementDecorators, 'value'=>$valorFechaFin));
        $fechaFin->setLabel('Fecha Fin:');
        
        //Agrego todos los elementos
        $this->addElement($idDocente);
//        $this->addElement($apellidos);
//        $this->addElement($nombres);
        $this->addElement($cargo);
        $this->addElement($fechaInicio);
        $this->addElement($fechaFin);

        

        /** establezco ubicaci�n * */
//        $local = new Zend_Locale();
        //creo un translate
//        $translate = new Zend_Translate('array', 'Aplicacion/Librerias/Idiomas/es/' . $local . '.php', 'es');
        //establezco el idioma del decorador
//        $this->setDefaultTranslator($translate);
        return $this;
    }

}
