<?php
require_once 'Zend/Form.php';


/** Desde aquí contenido propio del menú **/
echo $this->barraherramientas;
//echo $this->mostrarFiltroAlumnos;
echo '<table id="grilla"></table>';
echo '<div id="pager2"></div>';
echo $this->grid;
echo $this->grafico;
/** Fin del contenido del gastos **/
