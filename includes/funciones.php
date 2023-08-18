<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Funcion que revisa que el usuario este autenticado
function isAuth(){
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

// Es abministrador
function isAdmin(): void {
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}

// Es ultimo

function esUltimo(string $actual, string $proximo ){
    if($actual !== $proximo){
        return true;
    }

    return false;
}