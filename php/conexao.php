<?php
$hostname = "localhost";
$bancodedados = "avaliacao";
$usuario = "root";
$senha = "";

$mysqli = new mysqli( $hostname,$usuario,$senha,$bancodedados);

if ($mysqli->connect_error){
    echo "Falha ao conectar ao banco de dados: (".$mysqli->connect_errno.")".$mysqli->connect_error;
}
?>