<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    require_once '../class/group.class.php';
    $group = new Group();
/*******************************LOGAR****************************************/
    if(isset($_POST['addGroup'])){
        $dados['nome'] = $_POST['nome'];
        
        if($dados != null){
            $group->addGroup($dados);
        }else{
            echo "erro add";
        }
    }
    
    if(isset($_POST['editGroup'])){
        $dados['id'] = $_POST['id'];
        $dados['descricao'] = $_POST['descricao'];
        $dados['status'] = $_POST['status'];
        
        if($dados != null){
            $group->editGroup($dados);
        }else{
            echo "erro edit";
        }
    }

    if(isset($_POST['delGroup'])){
        $dados['id'] = $_POST['id'];
        
        if($dados != null){
            $group->delGroup($dados);
        }else{
            echo "erro del";
        }
    }
  
?>
