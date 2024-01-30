<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    
    /****************************************USUARIO*********************************************************/
    class Usuario{
        public function __construct() {
            require_once 'conexao.class.php';
            $this->conexao = $conexao;
        }

        public function validaUser($dados){
            try{
                $usuario = $dados['user'];
                $senha = md5($dados['password']);
               
                $consulta = $this->conexao->query("SELECT `id`,`nome`, `email`, `senha`, `filiais`, `fk_group`, `status` FROM `tb_usuario` WHERE (`email` = '".$usuario."') AND (`senha` = '".$senha."') AND (`status` = 'A') LIMIT 1");
                $return = $consulta->fetchAll(PDO::FETCH_ASSOC);

                $cont = count($return);
                if ($cont != '1') {
                    $erro = 'erro';
                    header("Location: ../../index.php?id=".$erro);
                }else{
                    if (!isset($_SESSION)) session_start();
                    $_SESSION['UserID']         = $return[0]['id'];
                    $_SESSION['UserNome']       = $return[0]['nome'];
                    $_SESSION['UsuarioEmail']   = $return[0]['email'];
                    $_SESSION['UserSenha']      = $return[0]['senha'];
                    $_SESSION['UserFilial']     = $return[0]['filiais'];
                    $_SESSION['UserGroup']      = $return[0]['fk_group'];
                    $_SESSION['UserStatus']     = $return[0]['status'];
                    #print_r($_SESSION); die;
                  header("Location: ../index.php"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

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
                $filial = $dados['filial'];
                $grupo = $dados['grupo'];

                $ins = $this->conexao->query("INSERT INTO `tb_usuario` (`nome`, `email`, `senha`, `filiais`, `fk_group`, `status`) VALUES ('".$nome."', '".$email."', '".$senha."', '".$filial."', '".$grupo."', 'A')");

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
                $filial = $dados['filial'];
                $status = $dados['status'];

                $ins = $this->conexao->query("UPDATE `tb_usuario` SET `nome` = '".$nome."', `email` = '".$email."', `fk_group` = '".$grupo."', `filiais` = '".$filial."', `status` = '".$status."' WHERE `id` = '".$id."'");

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
        }
    }
?>
