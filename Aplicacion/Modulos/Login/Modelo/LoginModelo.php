<?php
include_once LIBRERIAS . 'ModelBase.php';

/**
 *  Clase para interactuar con la BD en el modulo Login
 *  @author Walter Ruiz Diaz
 *  @see Librerias_ModelBase
 *  @category Modelo
 *  @package Login
 */
class LoginModelo extends ModelBase
{
    function __construct()
    {
        parent::__construct();
    }

    public function guardar($datos=array())
    {
        $sql = new Sql();
        $sql->addFuncion("insert");
        $sql->addTable(self::$_table);
        foreach ($datos as $key => $value) {
            $sql->addSelect($key);
            $sql->addValue($value);
        }
        
    }
    
    public function actualizar($datos=array(),$where='')
    {
        $sql = new Sql();
        $sql->addFuncion("UPDATE");
        $sql->addTable('conta_usuarios');
        foreach ($datos as $key => $value) {
            $sql->addSelect($key);
            $sql->addValue($value);
        }
        $sql->addWhere($where);
        $n = $this->_db->update('conta_usuarios',$datos, $where);
        return $n;
    }
    
    public function buscarUsuario($where = array())
    {
        if (!is_array($where)){
            throw new Zend_Exception("La condición de consulta no es válida");
        }
        $sql = new Sql();
        $sql->addFuncion('Select');
        $sql->addSelect('conta_usuarios.id,
        		conta_usuarios.nombre,
        		conta_usuarios.username,
        		conta_usuarios.email,
        		conta_usuarios.password,
        		conta_usuarios.categoria,
        		conta_usuarios.bloqueado,
        		conta_usuarios.enviarMail,
                        conta_usuarios.fechaRegistro,
                        conta_usuarios.ultimaVisita
                        ');
        $sql->addTable('conta_usuarios');
        foreach($where as $condicion){
           $sql->addWhere($condicion); 
        }
        $sql->addFuncion('SELECT');
        //$sql->addWhere($where);
        $this->_db->setFetchMode(Zend_Db::FETCH_OBJ);
        $resultado = $this->_db->fetchRow($sql);    
        return $resultado;
    }
}
