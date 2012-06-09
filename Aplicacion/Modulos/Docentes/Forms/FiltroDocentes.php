<?php

require_once LIBRERIAS . 'Zend/Form.php';

/**
 *  Clase para armar el formulario donde se cargan los docentes
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Docentes
 */
class Form_FiltroDocentes extends Zend_Form
{

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
    
    private $_campos = '';

    function __construct($campos)
    {
        $this->_campos = $campos;
        parent::__construct();
    }

    public function mostrar()
    {
        $this->setAttrib('id', 'frmFiltro');
        /** Campo **/
        $campo = $this->createElement('select', 'campo', array('decorators' => $this->elementDecorators, 'value'=>''));
        $campo->setOptions(array('multiOptions' => $this->_campos));
        $campo->setLabel('Seleccione un campo de la lista:');

        /** Valor **/
        $valor = $this->createElement('text', 'valor', array('decorators' => $this->elementDecorators, 'value' => ''));
        $valor->setLabel('Ingrese el valor a buscar:');
        $valor->setRequired(true);

        $this->addElement($campo);
        $this->addElement($valor);

        return $this;
    }


}
