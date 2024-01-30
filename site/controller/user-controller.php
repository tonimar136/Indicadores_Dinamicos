<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once '../class/user.class.php';
    $usuario = new Usuario();
/*******************************LOGAR****************************************/
    if(isset($_POST['btnLogar'])){
        $dados['user'] = $_POST['email'];
        $dados['password'] = $_POST['password'];
        
        if($dados != null){
            $usuario->validaUser($dados);
        }else{
            echo "erro login";
        }
    }
    
/*******************************SENHA****************************************/
    if(isset($_POST['alterSenha'])){
        $dados['email']      = $_POST['email'];
        $dados['senhaatual'] = $_POST['senhaatual'];
        $dados['novasenha']  = $_POST['novasenha'];
        if($dados != null){
            $usuario->queryUpdatePass($dados);
        }else{
            echo "erro no envio";
        }
    }
  
 /*******************************ADD****************************************/
    if(isset($_POST['addUser'])){
        $dados['nome']   = $_POST['nome'];
        $dados['email']  = $_POST['email'];
        $dados['senha']  = $_POST['senha'];
        $dados['filial'] = implode(',', $_POST['filiais']);
        $dados['grupo']  = $_POST['grupo'];
        if($dados != null){
            $usuario->insertUser($dados);
        }else{
            echo "erro no envio";
        }
    } 
  
  /*******************************ALTER****************************************/
    if(isset($_POST['alterUser'])){
        $dados['id']  = $_POST['id'];
        $dados['nome']  = $_POST['nome'];
        $dados['email'] = $_POST['email'];
        $dados['filial'] = implode(',', $_POST['filiais']);
        $dados['grupo'] = $_POST['grupo'];
        $dados['status'] = $_POST['status'];
        if($dados != null){
            $usuario->updateUser($dados);
        }else{
            echo "erro no envio";
        }
    }
  
  /*******************************DELETE****************************************/
    if(isset($_POST['deleteUser'])){
        $dados['id']  = $_POST['id'];
        if($dados != null){
            $usuario->deleteUser($dados);
        }else{
            echo "erro no envio";
        }
    }
  
?>
