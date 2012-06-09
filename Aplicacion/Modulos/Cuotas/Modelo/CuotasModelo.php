<?php

require_once LIBRERIAS . 'ModelBase.php';
//require_once DIR_MODULOS . 'CalendarioEscolar/Controlador/CriterioFiltro.php';
/**
 *  Clase para interactuar con la BD en el modulo Cuotas
 *  @author Walter Ruiz Diaz
 *  @see Librerias_ModelBase
 *  @category Modelo
 *  @package Cuotas
 */
class CuotasModelo extends ModelBase
{

    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Guarda en la tabla cuotas los datos de cuotas
     * @param Array $datos corresponde a los datos a guardar
     * @return lastInsertId
     * @access Public 
     */
    public function guardar($datos=array())
    {
        try {
            $this->_db->insert('conta_cuotas', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /**
     * Guarda en la tabla cuotas los datos de cuotas
     * @param Array $datos corresponde a los datos a guardar
     * @return lastInsertId
     * @access Public 
     */
    public function guardarDetalle($datos=array())
    {
        try {
            $this->_db->insert('conta_detalleCuota', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Actualiza los datos de la tabla calendarioescolar
     * @param Array $datos son los datos a actualizar
     * @param string $where es la condición de la actualización
     */
    public function actualizar($tabla='conta_calendarioescolar', $datos=array(), $where='')
    {
        try {
            $retorno = $this->_db->update($tabla, $datos, $where);
        } catch (Exception $e){
            $retorno = $e->getMessage();
        }
        return $retorno;
    }
    

    /**
     * Busca una cuota en la tabla conta_cuotas
     * @param array $where la condicion de la consulta
     * @return Zend_Db_Table_Row_Abstract|null 
     */
    public function buscarCuota($where)
    {
        if (!is_string($where)){
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_cuotas.id,
                        conta_cuotas.cuenta,
                        conta_cuotas.alumno,
                        conta_cuotas.fecha_comprobante,
                        conta_cuotas.comprobante,
                        conta_cuotas.tipo_comprobante,
                        conta_cuotas.nro_comprobante,
                        conta_cuotas.condicion_venta,
                        conta_cuotas.total,
                        conta_cuotas.observaciones
        ');
        $sql->addTable('conta_cuotas');
        $sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);    
        return $resultado;
    }
    
    public function buscarDetalle($where)
    {
        if (!is_string($where)){
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_detalleCuota.id,
                        conta_detalleCuota.idCuota,
                        conta_detalleCuota.mes,
                        conta_detalleCuota.anio
        ');
        $sql->addTable('conta_detalleCuota');
        $sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchAll($sql);    
        return $resultado;
    }

    /**
     * Lista los alumnos de la tabla alumnos
     * @param int $inicio. Desde donde se muestran los registros
     * @param string $orden. Los campos por los que se ordenan los datos
     * @param CriterioFiltro $filtro. Objeto con el criterio a filtrar
     * @param array $campos. Los campos a obtener de la tabla
     * @return Zend_Db_Table_Rowset_Abstract 
     */
    public function listadoCuotas($inicio, $fin, $orden,  $filtro,  $campos=array('cuotas.*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addSelect('alumnos.apellidos, alumnos.nombres');
        $sql->addFuncion('Select');
        $sql->addTable('
        	conta_cuotas as cuotas LEFT JOIN conta_alumnos as alumnos ON cuotas.alumno=alumnos.id
        ');
        $sql->addOrder($orden);
        $sql->addWhere('cuotas.eliminado=' . $this->_verEliminados);

        if (is_object($filtro)){
            $sql->addWhere($filtro->__toString());
        }
//        $fin = $inicio + $this->_limite ;
        if ($fin != '0'){
            $sql->addLimit($inicio, $fin);
        }
//        echo $fin;
//        echo $sql;
        try {
            $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
            $result = $this->_db->fetchAll($sql);
//            print_r($result);
            return $result;
        }catch (Exception $e) {
            echo $e->getMessage();
        }
        return $result;
    }

    /**
     * Obtiene la cantidad de registros de la tabla.
     * @param string $filtro. Filtro a considerar en la consulta.
     * @return int 
     */
    public function getCantidadRegistros($filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_calendarioescolar.id
                        ');
        $sql->addTable('conta_calendarioescolar');
        
        if (!$filtro == '') {
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }

}
