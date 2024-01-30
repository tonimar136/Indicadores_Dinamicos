<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);    
    /****************************************FORMULARIO*********************************************************/
    class Formulario{
        public function __construct() {
            require_once 'conexao.class.php';
            $this->conexao = $conexao;
        }

        public function insertFormulario($dados){
            try{
                if(!isset($_SESSION['UserID'])){
                    session_start();
                }
                $nome = $dados['nome'];
                $desc = $dados['desricao'];
                $user = $_SESSION['UserID'];
                $filiais = implode(',', $dados['filiais']);
                $grupo = implode(',', $dados['grupos']);

                $ins = $this->conexao->query("INSERT INTO `tb_formulario` (`nome`, `descricao`, `data_criacao`, `fk_user_criador`,`filial`, `grupos`, `status`) VALUES ('".$nome."', '".$desc."', now(), '".$user."', '".$filiais."', '".$grupo."', 'A')");
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

        public function editaFormulario($dados){
            try{
                #echo '<pre>'; print_r($dados); die;
                $id   = $dados['id'];
                $nome = $dados['nomeForm'];
                $desc = $dados['descricao'];
                $filiais = implode(',', $dados['filiais']);
                $grupo = implode(',', $dados['grupos']);

                $up = $this->conexao->query("UPDATE `tb_formulario` SET `nome` = '".$nome."', `descricao` = '".$desc."', `filial` = '".$filiais."', `grupos` = '".$grupo."' WHERE `id` = '".$id."'");
                if($up){
                    $reg = base64_encode($id);
                    header("Location: ../index.php?url=form-detail-ins&reg=".$reg); exit;
                }else{
                    header("Location: ../index.php?url=form-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }



        public function consultaForm($id, $g){
            try{
                if($g == '1'){
                    $consulta = $this->conexao->query("SELECT id, nome, descricao, CASE WHEN status = 'A' THEN 'Ativo' else 'Inativo' END AS status FROM tb_formulario");
                    $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                    return $retorno;
                }else{
                    $consulta = $this->conexao->query("SELECT id, nome, descricao, CASE WHEN status = 'A' THEN 'Ativo' else 'Inativo' END AS status FROM tb_formulario WHERE fk_user_criador = '".$id."'");
                    $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                    return $retorno;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultarFiliais(){
            if(!isset($_SESSION['UserID'])){
                session_start();
            }
             $filiais = str_replace(",", "','", $_SESSION['UserFilial']);

            try{
                $consulta = $this->conexao->query("SELECT id, descricao FROM tb_filial WHERE id IN ('".$filiais."')");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaFiliais(){
            try{
                $consulta = $this->conexao->query("SELECT * FROM tb_filial");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaGrupos(){
            try{
                $consulta = $this->conexao->query("SELECT * FROM tb_group");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
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
                $consulta = $this->conexao->query("SELECT id, nome, descricao, filial, grupos, CASE WHEN status = 'A' THEN 'Ativo' else 'Inativo' END AS status FROM tb_formulario WHERE id = " . $id);
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
        }


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
