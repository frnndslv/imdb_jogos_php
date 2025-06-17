<?php
function form_nao_enviado($msg) {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit('<h3 class="alert alert-warning">'.$msg.'</h3>');
    }
}

function ha_campos_em_branco_login($msg) {
    if ((empty($_POST['login'])) || (empty($_POST['senha']))) {
        exit('<h3 class="alert alert-warning">'.$msg.'</h3>');
    }
}
function ha_campos_em_branco_cadastro($msg) {
    if ((empty($_POST['nomeUsuario'])) || (empty($_POST['senha'])) || (empty($_POST['login'])) ) {
        exit('<h3 class="alert alert-warning">'.$msg.'</h3>');
    }
}

function verificar_erro_stmt($stmt) {
    if (!$stmt) {
        exit('<h3 class="alert alert-danger">Erro na preparação da consulta.</h3>');
    }
}


function verificar_erro_execucao($exe, $stmt, $msg) {
    if (!$exe) {
        exit('<h3 class="alert alert-danger">'. $msg .': ' . mysqli_stmt_error($stmt) . "</h3>");
    }
}

function verificar_savamento($bind) {
    if (!$bind) {
        exit('<h3 class="alert alert-danger">Erro ao vincular parâmetros. Impossível salvar./h3>');
    }
}


?>