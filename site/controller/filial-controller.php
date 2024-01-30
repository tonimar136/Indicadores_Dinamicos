<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require_once '../class/filial.class.php';
    $filial = new Filial();
/*******************************LOGAR****************************************/
    if(isset($_POST['addFilial'])){
        $dados['nome'] = $_POST['nome'];
        
        if($dados != null){
            $filial->addFilial($dados);
        }else{
            echo "erro add";
        }
    }
    
    if(isset($_POST['editFilial'])){
        $dados['id'] = $_POST['id'];
        $dados['descricao'] = $_POST['descricao'];
        $dados['status'] = $_POST['status'];
        
        if($dados != null){
            $filial->editFilial($dados);
        }else{
            echo "erro edit";
        }
    }

    if(isset($_POST['delFilial'])){
        $dados['id'] = $_POST['id'];
        
        if($dados != null){
            $filial->delFilial($dados);
        }else{
            echo "erro del";
        }
    }
  
?>
