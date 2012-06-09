<?php
require_once DIR_MODULOS . 'Salones/Controlador/CriterioFiltro.php';

/**
 * Clase usada para crear un filtro por domicilio
 * en la clase Docentes
 * @see CriterioFiltro.php
 * @author Walter Ruiz Diaz
 */
class FiltroTurno implements CriterioFiltro
{
    private $_filtro = '';
    
    function __construct($valor)
    {
        $this->_filtro = 'turno LIKE "' . $valor . '%"';
    }
 
    public function __toString()
    {
        return $this->_filtro;
    }
}

