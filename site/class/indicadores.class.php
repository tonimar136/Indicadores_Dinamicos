<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    /****************************************FORMULARIO*********************************************************/
    if (isset($_POST['action'])) {
        $indicadores = new Indicadores();
        $controle = $_POST['controle'];

        if($_POST['action'] == 'dadosForm') {
            $resultado = $indicadores->dadosForm($controle);
            echo json_encode($resultado); // Envia a resposta como JSON, ajuste conforme necessário
        }
    }


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
                            DATE_FORMAT(tfr.data_insert, '%d/%m/%Y %H:%i') as data,
                            tfr.controle as controle
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

        public function dadosForm($id){
            
            try{
                $consulta = $this->conexao->query("
                    SELECT
                        tfr.controle,
                        tp.descricao AS pergunta,
                        CASE WHEN tfr.resposta IS NULL THEN tr.descricao ELSE tfr.resposta END AS resposta
                    FROM
                        tb_formulario_reposta tfr
                        INNER JOIN tb_pergunta tp ON tp.id = tfr.fk_pergunta
                        LEFT JOIN tb_respostas tr ON tr.id = tfr.fk_resposta
                    WHERE
                        tfr.controle = '".$id."'
                    ORDER BY
                        tp.sequencia");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                #echo '<pre>';
                #print_r($retorno);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }
    }



    
?>
