<?php

require_once LIBRERIAS . 'ModelBase.php';
require_once DIR_MODULOS . 'Alumnos/Controlador/CriterioFiltro.php';
/**
 *  Clase para interactuar con la BD en el modulo Login
 *  @author Walter Ruiz Diaz
 *  @see Librerias_ModelBase
 *  @category Modelo
 *  @package Login
 */
class AlumnosModelo extends ModelBase
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
            $this->_db->insert('conta_alumnos', $datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /**
     * Guarda los datos de inscripcion del alumno
     * @param array $datos corresponde a los datos a guardar
     * @return lastInsertId 
     * @access Public
     */
    public function inscribir($datos=array())
    {
        try{
            $this->_db->insert('conta_inscripciones',$datos);
            return $this->_db->lastInsertId();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    
    /**
     * Actualiza los datos de la tabla Alumnos
     * @param Array $datos son los datos a actualizar
     * @param string $where es la condición de la actualización
     */
    public function actualizar($tabla, $datos=array(), $where='')
    {
        $this->_db->update($tabla, $datos, $where);
    }
    
    /**
     * Actualiza los datos de la tabla Inscripcion
     * @param Array $datos son los datos a actualizar
     * @param string $where es la condición de la actualización
     */
    public function actualizarInscripcion($datos=array(), $where='')
    {
        $this->_db->update('conta_inscripciones', $datos, $where);
    }
    
    /**
     * Busca en la tabla inscripciones y alumnos los datos de un alumno
     * inscripto.
     * @param array $where es la condición = el alumno a buscar
     * @return Zend_Db_Table_Row_Abstract|null 
     */
    public function buscarInscripto($where = array())
    {
        if (!is_array($where)) {
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_inscripciones.id,
        		conta_inscripciones.idAlumno,
        		conta_inscripciones.idSalon,
                        conta_inscripciones.aLectivo
                        ');
        $sql->addTable('conta_inscripciones');
        foreach ($where as $condicion) {
            $sql->addWhere($condicion);
        }
        $sql->addFuncion('SELECT');
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        return $resultado;
    }
    
    /**
     * Lista los alumnos que no están inscriptos
     * @return Zend_Db_Table_Rowset_Abstract 
     */
    public function alumnosParaInscripcion($aLectivo)
    {
        $aLectivo = substr($aLectivo, -4);
        $sql = 'SELECT * FROM conta_alumnos as alumnos
               WHERE alumnos.eliminado = ' . $this->_verEliminados.
               ' AND alumnos.id NOT IN ( 
               SELECT idAlumno 
               FROM conta_inscripciones as inscripciones
               WHERE alumnos.id = inscripciones.idAlumno
               AND inscripciones.aLectivo=' . $aLectivo .
               ' AND inscripciones.eliminado = ' . $this->_verEliminados.
        ') ORDER BY apellidos';
//        echo $sql;
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    /**
     * Busca un alumno en la tabla alumnos
     * @param array $where la condicion de la consulta = el alumno a buscar
     * @return Zend_Db_Table_Row_Abstract|null 
     */
    public function buscarAlumno($where = array())
    {
        if (!is_array($where)) {
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_alumnos.id,
        		conta_alumnos.apellidos,
        		conta_alumnos.nombres,
        		conta_alumnos.nro_doc,
        		conta_alumnos.domicilio,
        		conta_alumnos.fechaNac,
        		conta_alumnos.nacionalidad,
                        conta_alumnos.sexo
                        ');
        $sql->addTable('conta_alumnos');
        foreach ($where as $condicion) {
            $sql->addWhere($condicion);
        }
        $sql->addFuncion('SELECT');
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);
        return $resultado;
    }
    
    /**public function listaInscriptos()
    {
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addTable('conta_inscripciones');
        try {
            $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
            $result = $this->_db->fetchAll($sql);
            return $result;
        }catch (Exception $e) {
            echo $e->getMessage();
        }
        return $result;
    }*/
    
    /**
     * Funcion publica que devuelve un array de las inscripciones realizadas
     * @param int $ciclo el ciclo lectivo que quiero mostrar
     * @param int $salon el salon que quiero mostrar
     * @param String $filtro el filtro.
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function listaAlumnosInscriptos($inicio, $fin, $ciclo,$orden, $salon='0', $filtro='')
    {
        $campos = array('inscripciones.id', 'alumnos.apellidos', 'alumnos.nombres', 'salones.salon', 'salones.division');
        $sql = new Sql;
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('
        	conta_inscripciones AS inscripciones
                LEFT JOIN conta_alumnos AS alumnos 
                ON alumnos.id = inscripciones.idAlumno
                LEFT JOIN conta_salones as salones 
                ON salones.id = inscripciones.idSalon
        ');
        $sql->addOrder($orden);
        $sql->addWhere('alumnos.eliminado=' . $this->_verEliminados);
        $sql->addWhere('inscripciones.aLectivo = '.$ciclo);
        $sql->addWhere('inscripciones.eliminado=' . $this->_verEliminados);
        
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
        return $result;
    }
    
    /**
     * Funcion publica que devuelve un array de las inscripciones realizadas
     * @param int $ciclo el ciclo lectivo que quiero mostrar
     * @param int $salon el salon que quiero mostrar
     * @param String $filtro el filtro.
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function listaAlumnosExport($inicio, $fin, $ciclo,$orden, $salon='0', $filtro='')
    {
        $campos = array(
            'inscripciones.id',
            'alumnos.apellidos',
            'alumnos.nombres',
            'alumnos.fechaNac',
            'alumnos.nro_doc',
            'salones.salon',
            'salones.division');
        $sql = new Sql;
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('
        	conta_inscripciones AS inscripciones
                LEFT JOIN conta_alumnos AS alumnos 
                ON alumnos.id = inscripciones.idAlumno
                LEFT JOIN conta_salones as salones 
                ON salones.id = inscripciones.idSalon
        ');
        $sql->addOrder($orden);
        if ($salon != '0'){
            $sql->addWhere("salones.id = $salon");
        }
        $sql->addWhere('alumnos.eliminado=' . $this->_verEliminados);
        $sql->addWhere('inscripciones.aLectivo = '.$ciclo);
        $sql->addWhere('inscripciones.eliminado=' . $this->_verEliminados);
        
        if ($filtro != ''){
            $sql->addWhere($filtro);
        }
        
        if ($fin != '0'){
            $sql->addLimit($inicio, $fin);
        }
//        echo $sql;
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
     * Funcion publica que devuelve un array de las inscripciones realizadas
     * @param int $ciclo el ciclo lectivo que quiero mostrar
     * @param int $salon el salon que quiero mostrar
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function listaAlumnosInscriptosId($ciclo, $salon='0', $filtro='')
    {
        $campos = array('id', 'apellidos', 'nombres');
        $sql = new Sql;
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('conta_alumnos');
        $sql->addOrder('apellidos');
        $sql->addOrder('nombres');
        $sql->addWhere('eliminado=' . $this->_verEliminados);
        if ($filtro != ''){
            $sql->addWhere($filtro);
        }
//        $sql = 'SELECT inscripciones.id, alumnos.apellidos, alumnos.nombres, salones.salon
//FROM conta_inscripciones AS inscripciones
//LEFT JOIN conta_alumnos AS alumnos ON alumnos.id = inscripciones.idAlumno
//LEFT JOIN conta_salones as salones ON salones.id = inscripciones.idSalon
//WHERE inscripciones.aLectivo = '.$ciclo.
//' ORDER BY salones.salon, alumnos.apellidos, alumnos.nombres;';

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
     * Lista los alumnos de la tabla alumnos
     * @param int $inicio. Desde donde se muestran los registros
     * @param string $orden. Los campos por los que se ordenan los datos
     * @param CriterioFiltro $filtro. Objeto con el criterio a filtrar
     * @param array $campos. Los campos a obtener de la tabla
     * @return Zend_Db_Table_Rowset_Abstract 
     */
    public function listadoAlumnos($inicio, $fin, $ciclo, $orden,  $filtro,  $campos=array('*'))
    {
        $campos = array(
            'alumnos.id',
            'alumnos.apellidos',
            'alumnos.nombres',
            'alumnos.fechaNac',
            'alumnos.domicilio',
            'alumnos.nro_doc',
            'alumnos.nacionalidad',
            'salones.salon',
            'salones.division'
            );
        $sql = new Sql();
        foreach ($campos as $campo) {
            $sql->addSelect($campo);
        }
        $sql->addFuncion('Select');
        $sql->addTable('
        	conta_alumnos AS alumnos
                LEFT JOIN conta_inscripciones AS inscripciones
                ON alumnos.id = inscripciones.idAlumno
                LEFT JOIN conta_salones as salones 
                ON salones.id = inscripciones.idSalon
        ');
        $sql->addOrder($orden);
        $sql->addWhere('alumnos.eliminado=' . $this->_verEliminados);
//        if (is_int($ciclo)){
            $sql->addWhere('inscripciones.aLectivo = '.$ciclo);
//        }
        $sql->addWhere('inscripciones.eliminado=' . $this->_verEliminados);
        if (is_object($filtro)){
            $sql->addWhere($filtro->__toString());
        }
//        $fin = $inicio + $this->_limite ;
        if ($fin > 0){
            $sql->addLimit($inicio, $fin);
        }
//        echo $sql;
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
        $sql->addSelect('conta_alumnos.id,
        		conta_alumnos.apellidos,
        		conta_alumnos.nombres
                        ');
        $sql->addTable('conta_alumnos');
        
        if (!$filtro == '') {
            $sql->addWhere($filtro);
        }
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $resultado = $this->_db->fetchAll($sql);
        return count($resultado);
    }

}
