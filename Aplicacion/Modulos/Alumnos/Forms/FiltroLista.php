<?php

require_once LIBRERIAS . 'Zend/Form.php';
require_once LIBRERIAS . 'Form/Decorator/IconoInformacion.php';

/**
 *  Clase para armar el formulario donde se edita el historia docentes
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Docentes
 */
class Form_FiltroLista extends Zend_Form
{

    private $_varForm = array();
    
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

    function __construct($docente, $docentes = null)
    {
        $this->addPrefixPath('Aplicacion_Librerias_Form_Decorator', 'Aplicacion/Librerias/Form/Decorator', 'decorator');
        $this->addPrefixPath('Aplicacion_Librerias_ZendX_JQuery_Form_Decorator', 'Aplicacion/Librerias/ZendX/JQuery/Form/Decorator', 'decorator');
        $this->_varForm = $docente;
        $this->_listaDocentes = $docentes;
        
        parent::__construct();
    }

    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm) > 0){
            $valorId = $this->_varForm->id;
        }else{
            $valorId = 0;
        }
//        if ($valorId == 0) {
            $this->setAction('index.php?option=docentes&sub=historial');
//        } else {
//            $this->setAction('index.php?option=docentes&sub=historial&id=' . $valorId);
//        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmdocentes');
        $this->setAttrib('enctype', 'multipart/form-data');

        /** Id  * */
//        $id = $this->createElement('hidden', 'id', array('value' => $valorId));

        /** Apellido y Nombre * */
        $apellidos = $this->createElement('select', 'ayn', array('decorators' => $this->elementDecorators, 'value' => $valorId));
        $apellidos->setOptions(array('multiOptions' => $this->_listaDocentes));
        $apellidos->setLabel('Apellido y Nombre:');

        //Agrego todos los elementos
//        $this->addElement($id);
        $this->addElement($apellidos);        

        return $this;
    }

}
