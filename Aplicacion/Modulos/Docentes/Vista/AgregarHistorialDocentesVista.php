<?php
require_once 'Zend/Form.php';


/** Desde aquí contenido propio del menú **/
echo $this->barraherramientas;
echo $this->mensajes;
echo $this->datos;
echo $this->form->render($this);
/** Fin del contenido del menu  **/
