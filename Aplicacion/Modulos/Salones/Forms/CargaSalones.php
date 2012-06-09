<?php

require_once LIBRERIAS . 'Zend/Form.php';
require_once LIBRERIAS . 'Form/Decorator/IconoInformacion.php';

/**
 *  Clase para armar el formulario donde se cargan los salones
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Salones
 */
class Form_CargaSalones extends Zend_Form
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
    
    private $_docentes;
    private $_turnos;

    function __construct($docentes, $turnos, $salones = null)
    {
        $this->addPrefixPath('Aplicacion_Librerias_Form_Decorator', 'Aplicacion/Librerias/Form/Decorator', 'decorator');
        $this->addPrefixPath('Aplicacion_Librerias_ZendX_JQuery_Form_Decorator', 'Aplicacion/Librerias/ZendX/JQuery/Form/Decorator', 'decorator');
        $this->_varForm = $salones;
        $this->_docentes = $docentes;
        $this->_turnos = $turnos;
        parent::__construct();
    }

    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorSalon = $this->_varForm['salon'];
            $valorDivision = $this->_varForm['division'];
            $valorTurno = $this->_varForm['turno'];
            $valorDocente = $this->_varForm['docente'];
        }else{
            $valorId = 0;
            $valorSalon = '';
            $valorDivision = '';
            $valorTurno = '';
            $valorDocente = '';
        }
        if ($valorId == 0) {
            $this->setAction('index.php?option=salones&sub=agregar');
        } else {
            $this->setAction('index.php?option=salones&sub=editar&id=' . $valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmsalones');
        $this->setAttrib('enctype', 'multipart/form-data');

        /** Id  * */
        $id = $this->createElement('hidden', 'id', array('value' => $valorId));

        /** Salón * */
        $salon = $this->createElement('text', 'salon', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorSalon));
        $salon->setLabel('Salón:');
        $salon->setRequired(true);
        /** Division * */
        $division = $this->createElement('text', 'division', array('decorators' => $this->elementDecorators, 'value' => $valorDivision));
        $division->setLabel('División:');
        /** Turno * */
        $turno = $this->createElement('select', 'turno', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorTurno));
//        $turno->setOptions(array('multiOptions' => array('MAÑANA'=>'MAÑANA', 'TARDE'=>'TARDE')));
        $turno->setOptions(array('multiOptions' => $this->_turnos));
        $turno->setLabel('Turno:');
        $turno->setRequired(true);
        /** Docente **/
        $docente = $this->createElement('select', 'docente',array('decorators' => $this->elementDecorators, 'value'=>$valorDocente));
        $docente->setOptions(array('multiOptions' => $this->_docentes));
        $docente->setLabel('Docente:');
        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($salon);
        $this->addElement($division);
        $this->addElement($turno);        
        $this->addElement($docente);        


        return $this;
    }

}
