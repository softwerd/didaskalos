<?php

require_once LIBRERIAS . 'Zend/Form.php';
require_once LIBRERIAS . 'Form/Decorator/IconoInformacion.php';

/**
 *  Clase para armar el formulario donde se cargan los alumnos
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Alumnos
 */
class Form_CargaCalendarioEscolar extends Zend_Form
{

    private $_varForm = array();
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

    function __construct($alumnos = null)
    {
        $this->addPrefixPath('Aplicacion_Librerias_Form_Decorator', 'Aplicacion/Librerias/Form/Decorator', 'decorator');
        $this->addPrefixPath('Aplicacion_Librerias_ZendX_JQuery_Form_Decorator', 'Aplicacion/Librerias/ZendX/JQuery/Form/Decorator', 'decorator');
        $this->_varForm = $alumnos;
        parent::__construct();
    }

    public function mostrar()
    {
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorALectivo = $this->_varForm['aLectivo'];
            $valorInicio = $this->_varForm['inicio'];
            $valorFin = $this->_varForm['fin'];
            $valorInicio_Bimestre1 = $this->_varForm['inicio_bimestre1'];
            $valorFin_Bimestre1 = $this->_varForm['fin_bimestre1'];
            $valorInicio_Bimestre2 = $this->_varForm['inicio_bimestre2'];
            $valorFin_Bimestre2 = $this->_varForm['fin_bimestre2'];
            $valorInicio_Bimestre3 = $this->_varForm['inicio_bimestre3'];
            $valorFin_Bimestre3 = $this->_varForm['fin_bimestre3'];
            $valorInicio_Bimestre4 = $this->_varForm['inicio_bimestre4'];
            $valorFin_Bimestre4 = $this->_varForm['fin_bimestre4'];
        }else{
            $valorId = '';
            $valorALectivo = '';
            $valorInicio = '';
            $valorFin = '';
            $valorInicio_Bimestre1 = '';
            $valorFin_Bimestre1 = '';
            $valorInicio_Bimestre2 = '';
            $valorFin_Bimestre2 = '';
            $valorInicio_Bimestre3 = '';
            $valorFin_Bimestre3 = '';
            $valorInicio_Bimestre4 = '';
            $valorFin_Bimestre4 = '';
        }
        if ($valorId == 0) {
            $this->setAction('index.php?option=CalendarioEscolar&sub=agregar');
        }else{
            $this->setAction('index.php?option=CalendarioEscolar&sub=editar&id='.$valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmalumnos');

        /** Id  * */
        $id = $this->createElement('hidden', 'id', array('value' => $valorId));

        /** Año Lectivo * */
        $aLectivo = $this->createElement('text', 'aLectivo', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorALectivo));
        $aLectivo->setLabel('Año Lectivo:');
        $aLectivo->setRequired(true);
        /** Inicio **/
        $inicio = $this->createElement('text', 'inicio', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorInicio));
        $inicio->setLabel('Inicio:');
        $inicio->setRequired(true);
        /** Fin **/
        $fin = $this->createElement('text', 'fin', array('decorators' => $this->elementDecorators, 'value' => $valorFin));
        $fin->setLabel('Fin:');

        /** Inicio 1º Bimestre **/
        $inicio_Bimestre1 = $this->createElement('text', 'inicio_bimestre1', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorInicio_Bimestre1));
        $inicio_Bimestre1->setLabel('Inicio 1º Bim.:');
        $inicio_Bimestre1->setRequired(true);
        /** Fin del 1º Bimestre **/
        $fin_Bimestre1 = $this->createElement('text', 'fin_bimestre1', array('decorators' => $this->elementDecorators, 'value' => $valorFin_Bimestre1));
        $fin_Bimestre1->setLabel('Fin 1º Bim.:');
        /** Inicio 2º Bimestre **/
        $inicio_Bimestre2 = $this->createElement('text', 'inicio_bimestre2', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorInicio_Bimestre2));
        $inicio_Bimestre2->setLabel('Inicio 2º Bim.:');
        $inicio_Bimestre2->setRequired(true);
        /** Fin del 2º Bimestre **/
        $fin_Bimestre2 = $this->createElement('text', 'fin_bimestre2', array('decorators' => $this->elementDecorators, 'value' => $valorFin_Bimestre2));
        $fin_Bimestre2->setLabel('Fin 2º Bim.:');
        
        /** Inicio 3º Bimestre **/
        $inicio_Bimestre3 = $this->createElement('text', 'inicio_bimestre3', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorInicio_Bimestre3));
        $inicio_Bimestre3->setLabel('Inicio 3º Bim.:');
        $inicio_Bimestre3->setRequired(true);
        /** Fin del 1º Bimestre **/
        $fin_Bimestre3 = $this->createElement('text', 'fin_bimestre3', array('decorators' => $this->elementDecorators, 'value' => $valorFin_Bimestre3));
        $fin_Bimestre3->setLabel('Fin 3º Bim.:');
        /** Inicio 4º Bimestre **/
        $inicio_Bimestre4 = $this->createElement('text', 'inicio_bimestre4', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorInicio_Bimestre4));
        $inicio_Bimestre4->setLabel('Inicio 4º Bim.:');
        $inicio_Bimestre4->setRequired(true);
        /** Fin del 4º Bimestre **/
        $fin_Bimestre4 = $this->createElement('text', 'fin_bimestre4', array('decorators' => $this->elementDecorators, 'value' => $valorFin_Bimestre4));
        $fin_Bimestre4->setLabel('Fin 4º Bim.:');
        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($aLectivo);
        $this->addElement($inicio);
        $this->addElement($fin);

        $this->addElement($inicio_Bimestre1);
        $this->addElement($fin_Bimestre1);
        $this->addElement($inicio_Bimestre2);
        $this->addElement($fin_Bimestre2);
        $this->addElement($inicio_Bimestre3);
        $this->addElement($fin_Bimestre3);
        $this->addElement($inicio_Bimestre4);
        $this->addElement($fin_Bimestre4);

        return $this;
    }

}
