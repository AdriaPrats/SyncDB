<?php

# CONNEXIÓ A LA BASE DE DADES AMB PDO
require 'models/connectionEditor.php';

$id = $_POST['carta'];

$edt = new ConexionEditor();

echo "<script> console.log('Query Controller: Connection successful!'); </script>";

$rest = $edt->RestaurantInfo($id);

# Extreim el nom del restaurant
$nombre = $rest[0]['car_nombre'];

# En base al ID que hem escollit en la primera pàgina, busquem els grups que corresponen a aquest en concret
$group = $edt->GrupoInfo($id);

# Dels grups escollits, agafem els ID de cadascun i el guardem dins un array
$gru_id = array();
foreach ($group as $idCarta) {
    array_push($gru_id, $idCarta['gru_id']);
}

# De l'array anterior, agafem cadascun dels ID i inserim la informció dels plats dins un nou array
$info = array();
foreach ($gru_id as $id) {
    array_push($info, $edt->PlatoInfo($id));
}


# PASSEM LES DADES A LA PRIMERA VISTA
require 'views/2-Query.php';

$close_editor = $edt->DisconnectEdt();
