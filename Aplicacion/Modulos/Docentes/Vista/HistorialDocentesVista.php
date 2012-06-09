<?php
require_once 'Zend/Form.php';


/** Desde aquí contenido propio del menú **/
echo $this->barraherramientas;
echo $this->mensajes;
echo $this->form->render($this);
echo '<table id="grilla"></table>';
echo '<div id="pager2"></div>';
echo $this->grid;
/** Fin del contenido del menu  **/
