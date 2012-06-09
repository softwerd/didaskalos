<?php

$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=docentes&sub=agregar\" target=\"_self\" title=\"Agregar Docentes\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Docentes/Vista/docentes_add.png\" alt=\"Nuevo Docente\" class=\"toolbar2\"/>Agregar Docentes\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=docentes&sub=listar\" target=\"_self\" title=\"Lista de Docentes\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Docentes/Vista/lista_docentes.png\" alt=\"Docentes\" class=\"toolbar2\"/>Lista de Docentes\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=docentes&sub=historial\" target=\"_self\" title=\"Historial Docentes\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Docentes/Vista/historial_docente.png\" alt=\"Historial Docentes\" class=\"toolbar2\"/>Historial Docentes\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
