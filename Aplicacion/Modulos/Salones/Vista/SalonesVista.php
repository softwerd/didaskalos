<?php

$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=salones&sub=agregar\" target=\"_self\" title=\"Agregar Salones\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Salones/Vista/salones_add.png\" alt=\"Nuevo Salón\" class=\"toolbar2\"/>Agregar Salones\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=salones&sub=listar\" target=\"_self\" title=\"Lista de salones\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Salones/Vista/lista_salones.png\" alt=\"Salones\" class=\"toolbar2\"/>Lista de Salones\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
