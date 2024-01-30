<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    
    /****************************************USUARIO*********************************************************/
    class Group{
        public function __construct() {
            require_once 'conexao.class.php';
            $this->conexao = $conexao;
        }

        public function addGroup($dados){
            try{
                $nome = $dados['nome'];
                $ins = $this->conexao->query("INSERT INTO `tb_group` (`descricao`, `status`) VALUES ('".$nome."', 'A')");

                if($ins){
                    header("Location: ../index.php?url=group-ins"); exit;
                }else{
                    header("Location: ../index.php?url=group-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaGroup(){
            try{
                $consulta = $this->conexao->query("SELECT `id`, `descricao`, CASE WHEN `status` = 'A' THEN 'ATIVO' ELSE 'INATIVO' END AS status FROM `tb_group`");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
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

        public function editGroup($dados){
            try{
                $id         = $dados['id'];
                $descricao  = $dados['descricao'];
                $status     = $dados['status'];

                $query = $this->conexao->query("UPDATE `tb_group` SET `descricao` = '".$descricao."', `status` = '".$status."' WHERE `id` = '".$id."'");    

                if($query){
                    header("Location: ../index.php?url=group-success"); exit;
                }else{
                    header("Location: ../index.php?url=group-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function delGroup($dados){
            $id = $dados['id'];
            try{
                #VERIFICA SE TEM REGISTROS CORRELACIONADOS
                $query_check = $this->conexao->query("SELECT COUNT(*) as count FROM `tb_usuario` WHERE `fk_group` = '".$id."'");
                $result = $query_check->fetch(PDO::FETCH_ASSOC);

                if($result['count'] > 0){
                    header("Location: ../index.php?url=group-fail"); exit;
                }else{
                    $query = $this->conexao->query("DELETE FROM `tb_group` WHERE (`id` = '".$id."')");
                    if($query){
                        header("Location: ../index.php?url=group-success"); exit;
                    }else{
                        header("Location: ../index.php?url=group-fail"); exit;
                    }
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consultaUser(){
            try{
                $consulta = $this->conexao->query("
                    SELECT
                        u.id as id,
                        u.nome as nome,
                        u.email as email,
                        GROUP_CONCAT(f.id) as filial_id,
                        GROUP_CONCAT(f.descricao) as filial,
                        g.id as grupo_id,
                        g.descricao as grupo,
                        CASE WHEN u.status = 'A' THEN 'Ativo' ELSE 'Inativo' END as status,
                        u.filiais
                    FROM 
                        tb_usuario u
                        INNER JOIN tb_group g ON g.id = u.fk_group
                        LEFT JOIN tb_filial f ON FIND_IN_SET(f.id, REPLACE(u.filiais, ' ', '')) > 0
                    GROUP BY
                        u.id, u.nome, u.email, g.id, g.descricao, u.status, u.filiais");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }
  }
?>
