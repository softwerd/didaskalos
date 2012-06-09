<?php
require_once DIR_MODULOS . 'Alumnos/Controlador/CriterioFiltro.php';

/**
 * Clase usada para crear un filtro por nro_doc
 * en la clase alumnos
 * @see CriterioFiltro.php
 * @author Walter Ruiz Diaz
 */
class FiltroNro_doc implements CriterioFiltro
{
    private $_filtro = '';
    
    function __construct($valor)
    {
        $this->_filtro = 'nro_doc LIKE "' . $valor . '%"';
    }
 
    public function __toString()
    {
        return $this->_filtro;
    }
}

