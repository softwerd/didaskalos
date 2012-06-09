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
class Form_CargaDocentes extends Zend_Form
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

    function __construct($docentes = null)
    {
        $this->addPrefixPath('Aplicacion_Librerias_Form_Decorator', 'Aplicacion/Librerias/Form/Decorator', 'decorator');
        $this->addPrefixPath('Aplicacion_Librerias_ZendX_JQuery_Form_Decorator', 'Aplicacion/Librerias/ZendX/JQuery/Form/Decorator', 'decorator');
        $this->_varForm = $docentes;
        parent::__construct();
    }

    public function mostrar()
    {
        global $nacionalidades;
        $this->setMethod("POST");
        if (count($this->_varForm)>0){
            $valorId = $this->_varForm['id'];
            $valorApellidos = $this->_varForm['apellidos'];
            $valorNombres = $this->_varForm['nombres'];
            $valorDomicilio = $this->_varForm['domicilio'];
            $valorNro_doc = $this->_varForm['nro_doc'];
            $valorFechaNac = $this->_varForm['fechaNac'];
            $valorNacionalidad = $this->_varForm['nacionalidad'];
            $valorSexo = $this->_varForm['sexo'];
            $valorIdFoto = $this->_varForm['id'];
        }else{
            $valorId = 0;
            $valorApellidos = '';
            $valorNombres = '';
            $valorDomicilio = '';
            $valorNro_doc = 0;
            $valorFechaNac = '';
            $valorNacionalidad = '';
            $valorSexo = '';
            $valorIdFoto = 'sin_imagen';
        }
        if ($valorId == 0) {
            $this->setAction('index.php?option=docentes&sub=agregar');
        } else {
            $this->setAction('index.php?option=docentes&sub=editar&id=' . $valorId);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmdocentes');
        $this->setAttrib('enctype', 'multipart/form-data');

        /** Id  * */
        $id = $this->createElement('hidden', 'id', array('value' => $valorId));

        /** Apellidos * */
        $apellidos = $this->createElement('text', 'apellidos', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorApellidos));
        $apellidos->setLabel('Apellidos:');
        $apellidos->setRequired(true);
        /** Nombres * */
        $nombres = $this->createElement('text', 'nombres', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorNombres));
        $nombres->setLabel('Nombres:');
        $nombres->setRequired(true);
        /** Domicilio * */
        $domicilio = $this->createElement('text', 'domicilio', array('decorators' => $this->elementDecorators, 'value' => $valorDomicilio));
        $domicilio->setLabel('Domicilio:');
        /**  DNI  **/
        $nro_doc = $this->createElement('text', 'nro_doc', array('decorators' => $this->elementRequeridoDecorators, 'value' => $valorNro_doc));
        $nro_doc->setLabel('DNI:');
        $nro_doc->setRequired(true);
        $nro_doc->addValidator('Digits');
        $nro_doc->addValidator('StringLength', false, array(8, 8));
        /** Sexo **/
        $sexo = $this->createElement('select', 'sexo',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorSexo));
        $sexo->setOptions(array('multiOptions' => array('VARON'=>'VARON','MUJER'=>'MUJER')));
        $sexo->setLabel('Sexo:');
        /** Fecha de Nacimiento **/
        $fechaNac = $this->createElement('text', 'fechaNac',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorFechaNac));
        $fechaNac->setLabel('Fecha Nacimiento:');
        $fechaNac->setRequired(true);
        /** Nacionalidad **/
        $nacionalidad = $this->createElement('select', 'nacionalidad',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$valorNacionalidad));
        $nacionalidad->setOptions(array('multiOptions' => array('ARGENTINA'=>'ARGENTINA','PARAGUAY'=>'PARAGUAY')));
        $nacionalidad->setLabel('Nacionalidad:');
        $nacionalidad->setRequired(true);
        /** Foto **/      
        $foto = new Zend_Form_Element_File('foto');
//        $foto->setLabel('Upload an image:');
        $ruta = realpath(IMG.'/fotos/');
//        echo $ruta;
        $foto->setDestination($ruta);
        $foto->addValidator('Count', false, 1);
        $foto->addValidator('Size', false, 409600);
        $foto->addValidator('Extension', false, 'jpg,png,gif');
        $foto->setValueDisabled(true);

        
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($apellidos);
        $this->addElement($nombres);
        $this->addElement($domicilio);
        $this->addElement($nro_doc);
        $this->addElement($sexo);
        $this->addElement($fechaNac);
        $this->addElement($nacionalidad);
        $this->addElement($foto);
        $this->addElement('image', 'mostrarFoto', array()); 
        $this->mostrarFoto->setImage(IMG .'fotos/id'.$valorIdFoto.'.png');
        $this->mostrarFoto->setAttrib('onclick','return false');
        

        /** establezco ubicaci�n * */
//        $local = new Zend_Locale();
        //creo un translate
//        $translate = new Zend_Translate('array', 'Aplicacion/Librerias/Idiomas/es/' . $local . '.php', 'es');
        //establezco el idioma del decorador
//        $this->setDefaultTranslator($translate);
        return $this;
    }

}
