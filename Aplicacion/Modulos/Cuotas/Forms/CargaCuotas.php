<?php
require_once 'Zend/Form.php';
require_once 'Aplicacion/Librerias/Config.php';
require_once 'Aplicacion/Librerias/Form/Decorator/IconoInformacion.php';

class Form_CargaCuotas extends Zend_Form
{
    private $_cuenta = array();
    private $_alumnos = array();
    private $_varForm = array();
    private $_tipos_de_comprobantes = array('A'=>'A', 'B'=>'B', 'C'=>'C');
    private $_lista_comprobantes = array('FACTURA'=>'FACTURA', 'RECIBO'=>'RECIBO', 'TICKET'=>'TICKET');
    private $_condicion_de_venta = array('CONTADO'=>'CONTADO', 'CHEQUE'=>'CHEQUE');
    private $_anio = array('2011'=>'2011','2012'=>'2012');
    private $_meses = Array(
        "Inscripción"=>'Inscripción',
        "Marzo"=>'Marzo',
        "Abril"=>'Abril',
        "Mayo"=>'Mayo',
        "Junio"=>'Junio',
        "Julio"=>'Julio',
        "Agosto"=>'Agosto',
        "Septiembre"=>'Septiembre',
        "Octubre"=>'Octubre',
        "Noviembre"=>'Noviembre',
        "Diciembre"=>'Diciembre');

    public $elementRequeridoDecorators = array(
        'ViewHelper',
        array('Description', 	array('tag' => 'span', 'class' => 'element-description')),
    	array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array('IconoInformacion',array('placement'=>'append')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementDecorators = array(
        'ViewHelper',
        array('Description', 	array('tag' => 'span', 'class' => 'element-description')),
    	array('Errors'),
        //array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
        array('Label', array('separator' => ' ')),
        array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element-group')),
    );
    public $elementZendDecorators = array(
        'UiWidgetElement',
        array('Description', 	array('tag' => 'span', 'class' => 'element-description')),
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
    
    function __construct($cuenta, $alumnos, $cuota = null)
    {
        $this->addPrefixPath('Aplicacion_Librerias_Form_Decorator', 
                             'Aplicacion/Librerias/Form/Decorator',
                             'decorator');
        $this->addPrefixPath('Aplicacion_Librerias_ZendX_JQuery_Form_Decorator', 
                             'Aplicacion/Librerias/ZendX/JQuery/Form/Decorator',
                             'decorator');
        $this->_cuenta = $cuenta;
        $this->_alumnos = $alumnos;
        $this->_varForm = $cuota;
        parent::__construct();
    }
    
    public function mostrar()
    {
        if (count($this->_varForm) > 0){
            $id=$this->_varForm['id'];
            $cuenta=$this->_varForm['cuenta'];
            $alumno=$this->_varForm['alumno'];
            $fecha_comprobante=$this->_varForm['fecha_comprobante'];
            $tipo_comprobante=$this->_varForm['tipo_comprobante'];
            $comprobante=$this->_varForm['comprobante'];
            $nro_comprobante = $this->_varForm['nro_comprobante'];
            $condicion_venta = $this->_varForm['condicion_venta'];
            $total = $this->_varForm['total'];
            $observaciones=$this->_varForm['observaciones'];
            $mes=$this->_varForm['mes'];
            $anio=$this->_varForm['anio'];
        }else{
            $this->_varForm['id'] = '';
            $this->_varForm['cuenta'] = '20';
            $this->_varForm['alumno'] = '';
            $this->_varForm['fecha_comprobante'] = '';
            $this->_varForm['tipo_comprobante'] = '';
            $this->_varForm['comprobante'] = '';
            $this->_varForm['nro_comprobante'] = '';
            $this->_varForm['condicion_venta']='';
            $this->_varForm['total'] = '';
            $this->_varForm['observaciones']='';
            $this->_varForm['mes']='';
            $this->_varForm['anio']='';
        }
        $this->setMethod("POST");
        if ($this->_varForm['id']==0){
            $this->setAction('index.php?option=cuotas&sub=agregar');
        }else{
            $this->setAction('index.php?option=cuotas&sub=editar&id='.$this->_varForm['id']);
        }
        $this->setMethod("POST");
        $this->setAttrib('id', 'frmalumnos');
        /** Id  **/
        $id = $this->createElement('hidden', 'id',array( 'value'=>$this->_varForm['id']));
        /** Cuenta **/
        $cuenta = $this->createElement('hidden', 'cuenta',array( 'value'=>$this->_varForm['cuenta']));
        /** Alumno **/
        $alumno = $this->createElement('select', 'alumno', array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['alumno']));
        $alumno->setOptions(array('multiOptions' => $this->_alumnos));
        $alumno->setLabel('Alumno:');
        $alumno->setRequired(true);
        
        /** Fecha de Compra **/
        $fecha_compra = $this->createElement('text', 'fecha_comprobante',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['fecha_comprobante']));
        $fecha_compra->setLabel('Fecha:');
        $fecha_compra->setRequired(true);
        /** Comprobante **/
        $comprobante = $this->createElement('select', 'comprobante',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['comprobante']));
        $comprobante->setOptions(array('multiOptions' => $this->_lista_comprobantes));
        $comprobante->setLabel('Comprobante:');
        $comprobante->setRequired(true);
        /** Tipo de comprobante **/
        $tipoComprobante = $this->createElement('select', 'tipo_comprobante',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['tipo_comprobante']));
        $tipoComprobante->setOptions(array('multiOptions' => $this->_tipos_de_comprobantes));
        $tipoComprobante->setLabel('Tipo:');
        $tipoComprobante->setRequired(true);
        /** Número de comprobante **/
        $nro_comprobante = $this->createElement('text', 'nro_comprobante',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['nro_comprobante']));
        $nro_comprobante->setLabel('Nro:');
        $nro_comprobante->setRequired(true);
        $nro_comprobante->addValidator('Alnum');
        $nro_comprobante->addValidator('StringLength', false, array(12 , 12));
        /** Condición de Venta **/
        $condicionVenta = $this->createElement('select', 'condicion_venta',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['condicion_venta']));
        $condicionVenta->setOptions(array('multiOptions' => $this->_condicion_de_venta));
        $condicionVenta->setLabel('Condicion Pago:');
        $condicionVenta->setRequired(true);
        /** Importe Total **/
        $total = $this->createElement('text', 'total',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['total']));
        $total->setLabel('Total:');
        $total->setRequired(true);
        /** Meses **/
        $meses = $this->createElement('Multiselect', 'mes',array('decorators' => $this->elementDecorators));
        $meses->setOptions( array(
            'multiOptions' => $this->_meses));
        $meses->setLabel('Mes:');
        /** Año **/
        $anio = $this->createElement('select', 'anio',array('decorators' => $this->elementRequeridoDecorators, 'value'=>$this->_varForm['anio']));
        $anio->setOptions(array('multiOptions' => $this->_anio));
        $anio->setLabel('Año:');
        $anio->setRequired(true);
        /** Observaciones **/
        $observaciones = $this->createElement('text', 'observaciones',array('decorators' => $this->elementDecorators, 'value'=>$this->_varForm['observaciones']));
        $observaciones->setLabel('Observaciones:');
       
        //Agrego todos los elementos
        $this->addElement($id);
        $this->addElement($cuenta);
        $this->addElement($alumno);
        $this->addElement($fecha_compra);
        $this->addElement($comprobante);
        $this->addElement($tipoComprobante);
        $this->addElement($nro_comprobante);
        $this->addElement($condicionVenta);
        $this->addElement($total);
        $this->addElement($meses);
        $this->addElement($anio);
        $this->addElement($observaciones);
        return $this;        
    }
}
