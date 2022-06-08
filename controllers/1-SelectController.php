<?php

# CONNEXIÓ A LA BASE DE DADES AMB PDO
require 'models/connectionEditor.php';

$edt = new ConexionEditor();

echo "<script> console.log('Select Controller: Connection successful!'); </script>";

$data = $edt->ShowNames();

# PASSEM LES DADES A LA PRIMERA VISTA
require 'views/1-Select.php';
