<?php

//require_once LIBRERIAS . 'ModelBase.php';
//require_once DIR_MODULOS . 'CalendarioEscolar/Controlador/CriterioFiltro.php';
/**
 *  Clase para interactuar con la BD en el modulo CalendarioEscolar
 *  @author Walter Ruiz Diaz
 *  @see Librerias_ModelBase
 *  @category Modelo
 *  @package Login
 */
class CalendarioEscolarModelo extends ModelBase
{

    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Guarda en la tabla alumnos los datos del alumno
     * @param Array $datos corresponde a los datos a guardar
     * @return lastInsertId
     * @access Public 
     */
    public function guardar($datos=array())
    {
        try {
            $this->_db->insert('conta_calendarioescolar', $datos);
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
            $this->_db->update($tabla, $datos, $where);
        } catch (Exception $e){
            echo $e->getMessage();
        }
        
    }
    

    /**
     * Busca un calendario en la tabla calendarioescolar
     * @param array $where la condicion de la consulta = el calendario a buscar
     * @return Zend_Db_Table_Row_Abstract|null 
     */
    public function buscarCalendarioEscolar($where = array())
    {
        if (!is_array($where)) {
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_calendarioescolar.id,
                        conta_calendarioescolar.aLectivo,
        		conta_calendarioescolar.inicio,
        		conta_calendarioescolar.fin,
                        conta_calendarioescolar.inicio_bimestre1,
        		conta_calendarioescolar.fin_bimestre1,
                        conta_calendarioescolar.inicio_bimestre2,
        		conta_calendarioescolar.fin_bimestre2,
                        conta_calendarioescolar.inicio_bimestre3,
        		conta_calendarioescolar.fin_bimestre3,
                        conta_calendarioescolar.inicio_bimestre4,
        		conta_calendarioescolar.fin_bimestre4
                        ');
        $sql->addTable('conta_calendarioescolar');
        foreach ($where as $condicion) {
            $sql->addWhere($condicion);
        }
        $sql->addFuncion('SELECT');
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
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
    public function listadoCalendarios($inicio, $orden,  $filtro,  $campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_calendarioescolar');
        $sql->addOrder($orden);
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        if (is_object($filtro)){
            $sql->addWhere($filtro->__toString());
        }
        $fin = $inicio + $this->_limite ;
        if ($fin > 0){
            $sql->addLimit($inicio, $fin);
        }
        try {
            $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
            $result = $this->_db->fetchAll($sql);
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
