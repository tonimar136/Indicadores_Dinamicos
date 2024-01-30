<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require_once '../class/form.class.php';
    $form = new Formulario();
/*******************************LOGAR****************************************/
    if(isset($_POST['insertForm'])){
        $dados['nome']     = $_POST['nome'];
        $dados['desricao'] = $_POST['descricao'];
        $dados['filiais']   = $_POST['filial'];
        $dados['grupos']   = $_POST['grupo'];
        
        if($dados != null){
            $form->insertFormulario($dados);
        }else{
            echo "erro insert";
        }
    }

    if(isset($_POST['editarForm'])){
        $dados['id']        = $_POST['id'];
        $dados['nomeForm']  = $_POST['nomeForm'];
        $dados['descricao'] = $_POST['descricao'];
        $dados['filiais']   = $_POST['filial'];
        $dados['grupos']    = $_POST['grupo'];
        if($dados != null){
            $form->editaFormulario($dados);
        }else{
            echo "erro insert";
        }
    }

    if(isset($_POST['cadPergunta'])){
        #PERGUNTAS
        $pergunta['formulario'] = $_POST['formulario'];
        $pergunta['pergunta']   = $_POST['pergunta'];
        $pergunta['sequencia']  = $_POST['seq'];
        $pergunta['tipo']       = $_POST['tipo'];

        if(($pergunta['tipo'] == '3') || ($pergunta['tipo'] == '4') || ($pergunta['tipo'] == '5')){
            #RESPOSTAS
            $respostas = $_POST;
            unset($respostas['formulario']);
            unset($respostas['pergunta']);
            unset($respostas['seq']);
            unset($respostas['tipo']);
            unset($respostas['cadPergunta']);
        }else{
            $respostas = 'nada';
        }

        if($pergunta != null){
            $form->cadastraPerguntas($pergunta, $respostas);
        }else{
            echo "erro insert";
        }
    }

    if(isset($_POST['form-edit'])){
        $dados['id']        = $_POST['id'];
        $dados['resposta']  = $_POST['resposta'];
        $dados['sequencia'] = $_POST['sequencia'];
        $dados['status']    = $_POST['status'];
        if($dados != null){
            $form->updateRespostas($dados);
        }else{
            echo "erro insert";
        }
    }


    if(isset($_POST['edita-pergunta'])){
        $dados['id']        = $_POST['id'];
        $dados['pergunta']  = $_POST['descricao'];
        $dados['sequencia'] = $_POST['sequencia'];
        $dados['status']    = $_POST['status'];
        if($dados != null){
            $form->updatePergunta($dados);
        }else{
            echo "erro insert";
        }
    }

    if(isset($_POST['del-resp'])){
        $dados['id']        = $_POST['id'];
        if($dados != null){
            $form->delResposta($dados);
        }else{
            echo "erro insert";
        }
    }

    if(isset($_POST['form-del'])){
        $dados['id']        = $_POST['id'];
        if($dados != null){
            $form->delAll($dados);
        }else{
            echo "erro insert";
        }
    }

    if(isset($_POST['delete-formulario'])){
        $dados['id'] = $_POST['id'];
        if($dados != null){
            $form->delForm($dados);
        }else{
            echo "erro insert";
        }
    }
?>
