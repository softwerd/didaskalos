<?php

require_once LIBRERIAS . 'Zend/Form.php';
require_once LIBRERIAS . 'Form/Decorator/IconoInformacion.php';

/**
 *  Clase para armar el formulario donde se cargan los alumnos
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Alumnos
 */
class Form_InscribirAlumnos extends Zend_Form
{

    private $_varForm = array();
    private $_varSalones;
    private $_varCicloLectivos;
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

    function __construct($alumnos = null, $salones = null, $cicloLectivoArray = null)
    {
        $this->addPrefixPath('Aplicacion_Librerias_Form_Decorator', 'Aplicacion/Librerias/Form/Decorator', 'decorator');
        $this->addPrefixPath('Aplicacion_Librerias_ZendX_JQuery_Form_Decorator', 'Aplicacion/Librerias/ZendX/JQuery/Form/Decorator', 'decorator');
        $this->_varForm = $alumnos;
        $this->_varSalones = $salones;
        $this->_varCicloLectivos = $cicloLectivoArray;
        parent::__construct();
    }

    public function mostrar()
    {
        $opcionListaSalones = array();
        $opcionListaAlumnos = array();
        $this->setMethod("POST");
        $this->setAction('index.php?option=alumnos&sub=inscribir');
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmalumnos');
//        print_r($this->_varForm);
        /** Año Lectivo 
         * @todo traer lo aLectivos de la BD
         * **/
        $ciclo = date('Y');
        $aLectivo = $this->createElement('select', 'aLectivo',array('decorators' => $this->elementDecorators, 'value'=>$ciclo));
        $aLectivo->setOptions( array(
            'multiOptions' => $this->_varCicloLectivos ));
        $aLectivo->setLabel('Año Lectivo:');
        
        /** Lista de alumnos **/
        foreach ($this->_varForm as $alumno) {
            $opcionListaAlumnos[]=array($alumno->id => $alumno->apellidos .', ' . $alumno->nombres);
        }
        $listaAlumnos = $this->createElement('Multiselect', 'alumnos',array('decorators' => $this->elementDecorators));
        $listaAlumnos->setOptions( array(
            'multiOptions' => $opcionListaAlumnos ));
        $listaAlumnos->setLabel('Alumnos:');
        /** Lista de salones */
        foreach ($this->_varSalones as $salon){
            $opcionListaSalones[] = array($salon['id']=>$salon['salon']);
        }
        $listaSalones = $this->createElement('select', 'salones',array('decorators' => $this->elementDecorators));
        $listaSalones->setOptions( array(
            'multiOptions' => $opcionListaSalones ));
        $listaSalones->setLabel('Salón:');
        $listaSalones->setAttrib('maxLength', 45);
        $listaSalones->setAttrib('size', 8);
        
        //Agrego todos los elementos
        $this->addElement($aLectivo);
        $this->addElement($listaAlumnos);
        $this->addElement($listaSalones);
        return $this;
    }

}
