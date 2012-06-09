<?php

//include_once LIBRERIAS . 'ModelBase.php';
//require_once DIR_MODULOS . 'Docentes/Controlador/CriterioFiltro.php';
/**
 *  Clase para interactuar con la BD en el modulo Login
 *  @author Walter Ruiz Diaz
 *  @see Librerias_ModelBase
 *  @category Modelo
 *  @package Login
 */
class DocentesModelo extends ModelBase
{

    function __construct()
    {
        parent::__construct();
    }

    public function guardar($datos=array())
    {
        try {
            $this->_db->insert('conta_docentes', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function guardarHistorial($datos=array())
    {
        try {
            $this->_db->insert('conta_historialdocente', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actualizar($datos=array(), $where='')
    {
        $this->_db->update('conta_docentes', $datos, $where);
    }

    public function buscarDocente($where = array())
    {
        if (!is_array($where)) {
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_docentes.id,
        		conta_docentes.apellidos,
        		conta_docentes.nombres,
        		conta_docentes.nro_doc,
        		conta_docentes.domicilio,
        		conta_docentes.fechaNac,
        		conta_docentes.nacionalidad,
                        conta_docentes.sexo
                        ');
        $sql->addTable('conta_docentes');
        foreach ($where as $condicion) {
            $sql->addWhere($condicion);
        }
        $sql->addFuncion('SELECT');
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        return $resultado;
    }

    public function listadoDocentes($inicio,$fin,$orden,  $filtro,  $campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_docentes');
        $sql->addOrder($orden);
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        if (is_object($filtro)){
            $sql->addWhere($filtro->__toString());
        }
        $fin = $inicio + 29;
        $sql->addLimit($inicio, 30);
        try {
            $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
            $result = $this->_db->fetchAll($sql);
            return $result;
        }catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    public function listadoHistorialDocente($inicio, $fin, $orden,  $filtro,  $campos=array('*'))
    {
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_historialdocente');
        $sql->addOrder($orden);
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        
        if ($filtro != ''){
            $sql->addWhere($filtro);
        }
        
        if ($fin != '0'){
            $sql->addLimit($inicio, $fin);
        }
        try {
            $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
            $result = $this->_db->fetchAll($sql);
            return $result;
        }catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getCantidadRegistros($filtro='')
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_docentes.id,
        		conta_docentes.apellidos,
        		conta_docentes.nombres
                        ');
        $sql->addTable('conta_docentes');
        
        if (!$filtro == '') {
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }

}
