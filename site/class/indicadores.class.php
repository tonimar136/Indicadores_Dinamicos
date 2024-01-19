<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);    
    /****************************************FORMULARIO*********************************************************/
    class Indicadores{
        public function __construct() {
            require_once 'conexao.class.php';
            $this->conexao = $conexao;
        }


        public function consultaIndicadores(){
            try{
                $consulta = $this->conexao->query("
                        SELECT
                            f.id as id,
                            f.nome as nome,
                            f.descricao as descricao,
                            u.nome as criador
                        FROM tb_formulario f
                            INNER JOIN tb_usuario u ON u.id = f.fk_user_criador
                        WHERE
                            f.status = 'A'");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }


        public function consultaFormulario($id){
            try{
                $consulta = $this->conexao->query("
                        SELECT
                            f.id as id,
                            f.nome as nome,
                            f.descricao as formulario,
                            u.nome as criador,
                            p.id as id_pergunta,
                            p.descricao as pergunta,
                            tr.descricao as tipo_resposta
                        FROM tb_formulario f
                            INNER JOIN tb_usuario u ON u.id = f.fk_user_criador
                            INNER JOIN tb_pergunta p ON p.fk_formulario = f.id
                            INNER JOIN tb_tipo_resposta tr ON tr.id = p.fk_tipo_resposta
                            #LEFT JOIN tb_respostas r ON r.fk_pergunta = p.id
                        WHERE
                            f.id = '".$id."'
                        ORDER BY
                            p.sequencia");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                #echo '<pre>';
                #print_r($retorno);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaRespostas($id){
            try{
                $consulta = $this->conexao->query("SELECT * FROM tb_respostas WHERE fk_pergunta = '".$id."' ORDER BY sequencia");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                #echo '<pre>';
                #print_r($retorno);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function formRespostas($dados, $pr){
            try{
                #echo '<pre>'; print_r($pr);; die;
                $usuario    = $dados['user'];
                $formulario = $dados['formulario'];
                $controle   = $dados['controle'];


                // Variável para armazenar as partes divididas do array
                $partesArray = array();
                $parteAtual = array();


                // Iterar sobre o array
                foreach ($pr as $chave => $valor) {
                    // Verificar se a chave começa com "id_pergunta"
                    if (strpos($chave, 'id_pergunta') === 0) {
                        // Se sim, iniciar uma nova parte do array
                        if (!empty($parteAtual)) {
                            $partesArray[] = $parteAtual;
                        }
                        $parteAtual = array();
                    }
                    // Adicionar a chave e valor à parte atual do array
                    $parteAtual[$chave] = $valor;
                }

                // Adicionar a última parte do array
                if (!empty($parteAtual)) {
                    $partesArray[] = $parteAtual;
                }

                #TROCO OS NOMES POR INDICE
                foreach ($partesArray as $indice => $subArray) {
                    $partesArray[$indice] = array_values($subArray);
                }

                #INSERE DADOS
                $g = 0;
                $count = count($partesArray);
                while($g < $count){
                    if($partesArray[$g][1] == 'CHECKBOX *'){
                        $t = 2;
                        $cont = count($partesArray[$g]);
                        while($t < $cont){
                            $ins = $this->conexao->query("INSERT INTO `tb_formulario_reposta` (`controle`, `fk_usuario`, `fk_formulario`, `fk_pergunta`, `fk_resposta`, `data_insert`) VALUES ('".$controle."', '".$usuario."', '".$formulario."', '".$partesArray[$g][0]."', '".$partesArray[$g][$t]."', now())");
                            $t++;
                        }
                    }else{
                        if(($partesArray[$g][1] == 'SELECT *') || ($partesArray[$g][1] == 'RADIO *')){
                            $ins = $this->conexao->query("INSERT INTO `tb_formulario_reposta` (`controle`, `fk_usuario`, `fk_formulario`, `fk_pergunta`, `fk_resposta`, `data_insert`) VALUES ('".$controle."', '".$usuario."', '".$formulario."', '".$partesArray[$g][0]."', '".$partesArray[$g][2]."', now())");
                        }else{
                            $ins = $this->conexao->query("INSERT INTO `tb_formulario_reposta` (`controle`, `fk_usuario`, `fk_formulario`, `fk_pergunta`, `resposta`, `data_insert`) VALUES ('".$controle."', '".$usuario."', '".$formulario."', '".$partesArray[$g][0]."', '".$partesArray[$g][2]."', now())");
                        }
                    }
                    $g++;
                }

                if($ins){
                    header("Location: ../index.php?url=indicadores-ins"); exit;
                }else{
                     header("Location: ../index.php?url=indicadores-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }




        public function meusIndicadores($id){
            try{
                $consulta = $this->conexao->query("
                        SELECT
                            tf.nome as formulario,
                            DATE_FORMAT(tfr.data_insert, '%d/%m/%Y %H:%i') as data
                        FROM
                            tb_formulario_reposta tfr
                            INNER JOIN tb_formulario tf ON tf.id = tfr.fk_formulario
                        WHERE
                            tfr.fk_usuario = '".$id."'
                        GROUP BY
                            tf.nome,
                            DATE_FORMAT(tfr.data_insert, '%d/%m/%Y %H:%i'),
                            tfr.controle");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaMeusIndicadores($id){
            try{
                $consulta = $this->conexao->query("
                        SELECT
                            tfr.id as id_form_resposta,
                            tf.nome as formulario,
                            tp.descricao as pergunta,
                            CASE WHEN fk_resposta <> null THEN tr.descricao ELSE resposta END AS resposta
                        FROM
                            tb_formulario_reposta tfr
                            INNER JOIN tb_formulario tf ON tf.id = tfr.fk_formulario
                            INNER JOIN tb_pergunta tp ON tp.id = tfr.fk_pergunta
                            LEFT JOIN tb_respostas tr ON tr.id = tfr.fk_resposta
                        WHERE
                            tfr.fk_usuario = '".$id."'");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                echo '<pre>';
                print_r($retorno);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }





        /*
        public function insertFormulario($dados){
            try{
                if(!isset($_SESSION['UserID'])){
                    session_start();
                }
                
                $nome = $dados['nome'];
                $desc = $dados['desricao'];
                $user = $_SESSION['UserID'];

                $ins = $this->conexao->query("INSERT INTO `tb_formulario` (`nome`, `descricao`, `data_criacao`, `fk_user_criador`, `status`) VALUES ('".$nome."', '".$desc."', now(), '".$user."', 'A')");
                if($ins){
                    $consulta = $this->conexao->query("SELECT * FROM tb_formulario ORDER BY id DESC LIMIT 1");
                    $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                    $reg = base64_encode($retorno[0]['id']);
                    header("Location: ../index.php?url=form-detail-ins&reg=".$reg); exit;
                }else{
                    header("Location: ../index.php?url=form-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        

        public function consultaTipoResposta(){
            try{
                $consulta = $this->conexao->query("SELECT id, descricao FROM tb_tipo_resposta WHERE status = 'A' ORDER BY descricao");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function formDetalhe($id){
            try{
                $consulta = $this->conexao->query("SELECT id, nome, descricao, CASE WHEN status = 'A' THEN 'Ativo' else 'Inativo' END AS status FROM tb_formulario WHERE id = " . $id);
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function cadastraPerguntas($pergunta, $resposta){
            try{
                $formulario = $pergunta['formulario'];
                $perg       = $pergunta['pergunta'];
                $sequencia  = $pergunta['sequencia'];
                $tipo       = $pergunta['tipo'];
                
                $ins = $this->conexao->query("INSERT INTO `tb_pergunta` (`descricao`, `sequencia`, `fk_formulario`, `fk_tipo_resposta`, `status`) VALUES ('".$perg."', '".$sequencia."', '".$formulario."', '".$tipo."', 'A')");
                if($ins){
                    $consulta = $this->conexao->query("SELECT * FROM tb_pergunta ORDER BY id DESC LIMIT 1");
                    $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);

                    if($resposta != 'nada'){
                        $resposta = array_values($resposta);
                        $g = 0;
                        $j = 1;
                        $contador = count($resposta);
                        while($g < $contador){
                            $rsp = $this->conexao->query("INSERT INTO `tb_respostas` (`descricao`, `sequencia`, `fk_pergunta`, `status`) VALUES ('".$resposta[$g]."', '".$j."', '".$retorno[0]['id']."', 'A')");
                            $g++;
                            $j++;
                        }
                    }

                    $reg = base64_encode($formulario);
                    header("Location: ../index.php?url=form-detail-ins&reg=".$reg); exit;
                }else{
                    header("Location: ../index.php?url=form-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function listaPerguntaResposta($id){
            try{
                $consulta = $this->conexao->query("
                    SELECT
                        p.id as id_pergunta,
                        p.descricao as pergunta,
                        p.sequencia as sequencia_pergunta,
                        p.fk_formulario as formulario,
                        p.fk_tipo_resposta as id_tipo_resposta,
                        CASE WHEN p.status = 'A' THEN 'Ativo' ELSE 'Inativo' END as status_pergunta,
                        r.id as id_resposta,
                        r.descricao as resposta,
                        r.sequencia as sequencia_resposta,
                        CASE WHEN r.status = 'A' THEN 'Ativo' ELSE 'Inativo' END as status_resposta,
                        t.id as id_tipo_campo,
                        t.descricao as tipo_campo
                    FROM
                        tb_pergunta p
                        INNER JOIN tb_respostas r ON p.id = r.fk_pergunta
                        INNER JOIN tb_tipo_resposta t ON t.id = p.fk_tipo_resposta
                    WHERE 
                        p.id = '".$id."'");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function listaPergunta($form){
            try{
                $consulta = $this->conexao->query("SELECT p.id as id, p.descricao as descricao, p.sequencia as sequencia, CASE WHEN p.status = 'A' THEN 'Ativo' ELSE 'Inativo' END as status, tr.descricao as tipo_resposta FROM tb_pergunta p INNER JOIN tb_tipo_resposta tr ON p.fk_tipo_resposta = tr.id WHERE fk_formulario = '".$form."'");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }





        public function updatePergunta($dados){
            try{
                $id        = $dados['id'];
                $pergunta  = $dados['pergunta'];
                $sequencia = $dados['sequencia'];
                $status    = $dados['status'];

                #echo "<script>console.log('Dados recebidos: " . json_encode($q) . "');</script>";
                $ins = $this->conexao->query("UPDATE `tb_pergunta` SET `descricao` = '".$pergunta."', `sequencia` = '".$sequencia."', `status` = '".$status."' WHERE `id` = '".$id."'");
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

       






        public function updateRespostas($dados){
            try{
                $id        = $dados['id'];
                $resposta  = $dados['resposta'];
                $sequencia = $dados['sequencia'];
                $status    = $dados['status'];

                if($status == 'Ativo'){
                    $status = 'A';
                }else{
                    $status = 'I';
                }

                #echo "<script>console.log('Dados recebidos: " . json_encode($q) . "');</script>";
                $ins = $this->conexao->query("UPDATE `tb_respostas` SET `descricao` = '".$resposta."', `sequencia` = '".$sequencia."', `status` = '".$status."' WHERE `id` = '".$id."'");
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }



        public function delResposta($dados){
            $id = $dados['id'];
            try{
                #DELETA RESPOSTAS
                $query_p = $this->conexao->query("DELETE FROM `tb_respostas` WHERE `id` = '".$id."'");
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }



        public function delAll($dados){
            $id = $dados['id'];
            try{
                #DELETA RESPOSTAS
                $query_r = $this->conexao->query("DELETE FROM `tb_respostas` WHERE `fk_pergunta` = '".$id."'");
                
                #DELETA PERGUNTA
                $query_p = $this->conexao->query("DELETE FROM `tb_pergunta` WHERE `id` = '".$id."'");
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }





        public function delForm($dados){
            $id = $dados['id'];
            




            /*
            try{
                #DELETA RESPOSTAS
                $query_r = $this->conexao->query("DELETE FROM `tb_respostas` WHERE `fk_pergunta` = '".$id."'");
                
                #DELETA PERGUNTA
                $query_p = $this->conexao->query("DELETE FROM `tb_pergunta` WHERE `id` = '".$id."'");
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }*/
        #}


/*
        public function queryUpdatePass($dados){
            try{
                $email      = $dados['email'];
                $senhaatual = md5($dados['senhaatual']);
                $novasenha  = md5($dados['novasenha']);

                #CONSULTA PARA VERIFICAR SE A SENHA ATUAL É IGUAL
                $consulta = $this->conexao->query("SELECT `senha` FROM `tb_usuario` WHERE (`email` = '".$email."') AND (`senha` = '".$senhaatual."') AND (`status` = 'A') LIMIT 1");
                $return = $consulta->fetchAll(PDO::FETCH_ASSOC);
                $cont = count($return);
            
                if($cont == 1){
                    $cst = $this->conexao->query("UPDATE `tb_usuario` SET `senha` = '".$novasenha."' WHERE `email` = '".$email."'");
                    session_start();
                    session_destroy();
                    header('Location: ../index.php?id=pass');
                }else{
                    header("Location: ../site/index.php?url=alterpass-fail");
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function insertUser($dados){
            try{
                $nome  = $dados['nome'];
                $email = $dados['email'];
                $senha = md5($dados['senha']);
                $grupo = $dados['grupo'];

                $ins = $this->conexao->query("INSERT INTO `tb_usuario` (`nome`, `email`, `senha`, `fk_group`, `status`) VALUES ('".$nome."', '".$email."', '".$senha."', '".$grupo."', 'A')");

                if($ins){
                    header("Location: ../index.php?url=admin-success"); exit;
                }else{
                    header("Location: ../index.php?url=admin-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function updateUser($dados){
            try{
                $id     = $dados['id'];
                $nome   = $dados['nome'];
                $email  = $dados['email'];
                $grupo  = $dados['grupo'];
                $status = $dados['status'];

                $ins = $this->conexao->query("UPDATE `tb_usuario` SET `nome` = '".$nome."', `email` = '".$email."', `fk_group` = '".$grupo."', `status` = '".$status."' WHERE `id` = '".$id."'");

                if($ins){
                    header("Location: ../index.php?url=admin-success"); exit;
                }else{
                    header("Location: ../index.php?url=admin-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaUser(){
            try{
                $consulta = $this->conexao->query("SELECT u.id as id, u.nome as nome, u.email as email, g.descricao as grupo, CASE WHEN u.status = 'A' THEN 'Ativo' ELSE 'Inativo' END as status FROM tb_usuario u INNER JOIN tb_group g ON g.id = u.fk_group");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function deleteUser($dados){
            $id = $dados['id'];
            try{
                #VERIFICA SE TEM REGISTROS CORRELACIONADOS
                $query_check = $this->conexao->query("SELECT COUNT(*) as count FROM `tb_formulario` WHERE `fk_user_criador` = '".$id."'");
                $result = $query_check->fetch(PDO::FETCH_ASSOC);

                if($result['count'] > 0){
                    header("Location: ../index.php?url=admin-fail"); exit;
                }else{
                    $query = $this->conexao->query("DELETE FROM `tb_usuario` WHERE (`id` = '".$id."')");
                    if($query){
                        header("Location: ../index.php?url=admin-success"); exit;
                    }else{
                        header("Location: ../index.php?url=admin-fail"); exit;
                    }
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }*/
    }
?>
