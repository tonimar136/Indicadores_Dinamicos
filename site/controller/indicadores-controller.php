<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once '../class/indicadores.class.php';
    $indicadores = new Indicadores();
/*******************************LOGAR****************************************/
    if(isset($_POST['salvarIndicadores'])){
        $pr = $_POST;
        unset($pr['formulario']);
        unset($pr['id_user']);
        unset($pr['salvarIndicadores']);
        unset($pr['controle']);

        $dados['formulario'] = $_POST['formulario'];
        $dados['user']       = $_POST['id_user'];
        $dados['controle']   = $_POST['controle'];

        if($dados != null){
            $indicadores->formRespostas($dados, $pr);        
        }else{
            echo "erro insert";
        }
    }

    if(isset($_POST['atualizarIndicadores'])){
        $pr = $_POST;
        unset($pr['formulario']);
        unset($pr['id_user']);
        unset($pr['atualizarIndicadores']);
        unset($pr['controle']);

        $dados['formulario'] = $_POST['formulario'];
        $dados['user']       = $_POST['id_user'];
        $dados['controle']   = $_POST['controle'];

        if($dados != null){
            $indicadores->updateFormRespostas($dados, $pr);        
        }else{
            echo "erro insert";
        }
    }

    if(isset($_POST['excluirIndicadores'])){
        echo '<pre>';
        print_r($_POST);
        die;
        $dados['formulario'] = $_POST['formulario'];
        $dados['user']       = $_POST['id_user'];
        $dados['controle']   = $_POST['controle'];

        if($dados != null){
            $indicadores->updateFormRespostas($dados);        
        }else{
            echo "erro insert";
        }
    }

    

?>
