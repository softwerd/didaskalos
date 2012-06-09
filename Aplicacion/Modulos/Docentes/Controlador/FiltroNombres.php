<?php
require_once DIR_MODULOS . 'Docentes/Controlador/CriterioFiltro.php';

/**
 * Clase usada para crear un filtro por nombres
 * en la clase Docentes
 * @see CriterioFiltro.php
 * @author Walter Ruiz Diaz
 */
class FiltroNombres implements CriterioFiltro
{
    private $_filtro = '';
    
    function __construct($valor)
    {
        $this->_filtro = 'nombres LIKE "' . $valor . '%"';
    }
 
    public function __toString()
    {
        return $this->_filtro;
    }
}

