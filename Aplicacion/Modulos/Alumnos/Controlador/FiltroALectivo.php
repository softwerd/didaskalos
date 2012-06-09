<?php
require_once DIR_MODULOS . 'Alumnos/Controlador/CriterioFiltro.php';

/**
 * Clase usada para crear un filtro por año lectivo
 * en la clase alumnos
 * @see CriterioFiltro.php
 * @author Walter Ruiz Diaz
 */
class FiltroALectivo implements CriterioFiltro
{
    private $_filtro = '';
    
    function __construct($valor)
    {
        $this->_filtro = 'aLectivo LIKE "' . $valor . '%"';
    }
 
    public function __toString()
    {
        return $this->_filtro;
    }
}

