<?php

$servidor="localhost";
$usuario="root";
$senha="";
$banco="primeiro_banco";

//CONEXAO
try{ //TENTA ESTA CONEXAO
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $erro){ //SENAO ERRO
    echo "Falha ao se conectar com o banco ".$erro->getMessage();
}


//FUNCAO PARA LIMPAR ENTRADAS

function limpar_dado($dado){
    $dado= trim($dado);
    $dado= stripslashes($dado);
    $dado= htmlspecialchars($dado);
    return $dado;
}


?>