<?php

require_once LIBRERIAS . 'Zend/Form.php';
require_once LIBRERIAS . 'Form/Decorator/IconoInformacion.php';

/**
 *  Clase para armar el formulario donde se cargan los alumnos
 *  @author Walter Ruiz Diaz
 *  @category Forms
 *  @package Alumnos
 */
class Form_EditarInscripcion extends Zend_Form
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

    function __construct($inscripcion = null)
    {
        $this->addPrefixPath('Aplicacion_Librerias_Form_Decorator', 'Aplicacion/Librerias/Form/Decorator', 'decorator');
        $this->addPrefixPath('Aplicacion_Librerias_ZendX_JQuery_Form_Decorator', 'Aplicacion/Librerias/ZendX/JQuery/Form/Decorator', 'decorator');
        $this->_varForm = $inscripcion;
        parent::__construct();
    }

    public function mostrar()
    {
//        print_r($this->_varForm);
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorIdAlumno = $this->_varForm['idAlumno'];
            $valorIdSalon = $this->_varForm['idSalon'];
            $valorALectivo = $this->_varForm['aLectivo'];
            $valorApellidos = $this->_varForm['apellidos'];
            $valorNombres = $this->_varForm['nombres'];
            $valorSalon = $this->_varForm['salon'];
            $listaSalones = $this->_varForm['listaSalones'];
        }
        $this->setAction('index.php?option=alumnos&sub=editarInscripcion&id=' . $valorId);
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmalumnos');

        /** Id  * */
        $id = $this->createElement('hidden', 'id', array('value' => $valorId));
        /** Id  * */
        $idAlumno = $this->createElement('hidden', 'idAlumno', array('value' => $valorIdAlumno));
        /** Apellidos * */
        $apellidos = $this->createElement('text', 'apellidos', array('decorators' => $this->elementDecorators, 'value' => $valorApellidos));
        $apellidos->setLabel('Apellidos:');

        /** Nombres * */
        $nombres = $this->createElement('text', 'nombres', array('decorators' => $this->elementDecorators, 'value' => $valorNombres));
        $nombres->setLabel('Nombres:');
        
        /** Lista de salones */
        foreach ($listaSalones as $salon){
            $opcionListaSalones[] = array($salon['id']=>$salon['salon']);
        }
        $listaSalones = $this->createElement('select', 'idSalon',array('decorators' => $this->elementDecorators, 'value'=>$valorIdSalon));
        $listaSalones->setOptions( array(
            'multiOptions' => $opcionListaSalones ));
        $listaSalones->setLabel('Salón:');
        
        /** Año Lectivo **/
        $aLectivo = $this->createElement('select', 'aLectivo',array('decorators' => $this->elementDecorators, 'value'=>$valorALectivo));
        $aLectivo->setOptions(array('multiOptions' => array('2012'=>'2012','2011'=>'2011')));
        $aLectivo->setLabel('Año Lectivo:');
//
//        /** Salón **/
//        $salon = $this->createElement('select', 'salon',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorSalon));
//        $salon->setOptions(array('multiOptions' => array('ARGENTINA'=>'ARGENTINA','PARAGUAY'=>'PARAGUAY')));
//        $salon->setLabel('Salón:');
//        $salon->setRequired(true);
//        
//        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($idAlumno);
        $this->addElement($apellidos);
        $this->addElement($nombres);
        $this->addElement($aLectivo);
        $this->addElement($listaSalones);

        return $this;
    }

}
