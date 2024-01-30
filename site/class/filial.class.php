<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    
    /****************************************USUARIO*********************************************************/
    class Filial{
        public function __construct() {
            require_once 'conexao.class.php';
            $this->conexao = $conexao;
        }

        public function addFilial($dados){
            try{
                $nome = $dados['nome'];
                $ins = $this->conexao->query("INSERT INTO `tb_filial` (`descricao`, `status`) VALUES ('".$nome."', 'A')");

                if($ins){
                    header("Location: ../index.php?url=filial-ins"); exit;
                }else{
                    header("Location: ../index.php?url=filial-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaFilial(){
            try{
                $consulta = $this->conexao->query("SELECT `id`, `descricao`, CASE WHEN `status` = 'A' THEN 'ATIVO' ELSE 'INATIVO' END AS status FROM `tb_filial`");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function editFilial($dados){
            try{
                $id         = $dados['id'];
                $descricao  = $dados['descricao'];
                $status     = $dados['status'];

                $query = $this->conexao->query("UPDATE `tb_filial` SET `descricao` = '".$descricao."', `status` = '".$status."' WHERE `id` = '".$id."'");    

                if($query){
                    header("Location: ../index.php?url=filial-success"); exit;
                }else{
                    header("Location: ../index.php?url=filial-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function delFilial($dados){
            $id = $dados['id'];
            try{
                #VERIFICA SE TEM REGISTROS CORRELACIONADOS
                $query_check = $this->conexao->query("SELECT COUNT(*) as count FROM `tb_usuario` WHERE FIND_IN_SET('".$id."', filiais) > 0");
                $result = $query_check->fetch(PDO::FETCH_ASSOC);
                if($result['count'] > 0){
                    header("Location: ../index.php?url=filial-fail"); exit;
                }else{
                    $query = $this->conexao->query("DELETE FROM `tb_filial` WHERE (`id` = '".$id."')");
                    if($query){
                        header("Location: ../index.php?url=filial-success"); exit;
                    }else{
                        header("Location: ../index.php?url=filial-fail"); exit;
                    }
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaUser(){
            try{
                $consulta = $this->conexao->query("SELECT u.id as id, u.nome as nome, u.email as email, g.id as grupo_id, g.descricao as grupo, CASE WHEN u.status = 'A' THEN 'Ativo' ELSE 'Inativo' END as status FROM tb_usuario u INNER JOIN tb_group g ON g.id = u.fk_group");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }
  }
?>
