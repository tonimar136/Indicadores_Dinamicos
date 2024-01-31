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

        if($_POST['action'] == 'excluirForm') {
            $resultado = $indicadores->deleteForm($controle);
            echo json_encode('OK'); // Envia a resposta como JSON, ajuste conforme necessário
        }
    }


    class Indicadores{
        public function __construct() {
            require_once 'conexao.class.php';
            $this->conexao = $conexao;
        }


        public function consultaIndicadores($gp, $f){
            try{
                $filiais = explode(',', $f);
                $g = 0;
                $ct = count($filiais);
                $condicao = '';
                while($g < $ct){
                    if($g == 0)
                        $condicao .= "AND FIND_IN_SET('".$filiais[$g]."', f.filial) > 0 ";
                    else{
                        $condicao .= " OR FIND_IN_SET('".$filiais[$g]."', f.filial) > 0 ";
                    }
                    $g++;
                }

                $consulta = $this->conexao->query("
                        SELECT
                            f.id as id,
                            f.nome as nome,
                            f.descricao as descricao,
                            u.nome as criador
                        FROM tb_formulario f
                            INNER JOIN tb_usuario u ON u.id = f.fk_user_criador
                        WHERE
                            f.status = 'A'
                            AND FIND_IN_SET('".$gp."', f.grupos) > 0
                            " . $condicao);

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

        public function consRespEsp($id, $ctrl){
            try{
                $consulta = $this->conexao->query("SELECT * FROM tb_formulario_reposta WHERE controle = '".$ctrl."' and fk_resposta = '".$id."'");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                #echo '<pre>';
                #print_r($retorno);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }

        public function consRespEsp1($id, $ctrl){
            try{
                $consulta = $this->conexao->query("SELECT * FROM tb_formulario_reposta WHERE fk_pergunta = '".$id."' and controle = '".$ctrl."'");
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
                if(!isset($_SESSION['UserID'])){
                    session_start();
                }
                $fl = $_SESSION['UserFilialLogada'];
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
                            $ins = $this->conexao->query("INSERT INTO `tb_formulario_reposta` (`controle`, `fk_usuario`, `fk_formulario`, `fk_filial`, `fk_pergunta`, `fk_resposta`, `data_insert`) VALUES ('".$controle."', '".$usuario."', '".$formulario."', '".$fl."', '".$partesArray[$g][0]."', '".$partesArray[$g][$t]."', now())");
                            $t++;
                        }
                    }else{
                        if(($partesArray[$g][1] == 'SELECT *') || ($partesArray[$g][1] == 'RADIO *')){
                            $ins = $this->conexao->query("INSERT INTO `tb_formulario_reposta` (`controle`, `fk_usuario`, `fk_formulario`, `fk_filial`, `fk_pergunta`, `fk_resposta`, `data_insert`) VALUES ('".$controle."', '".$usuario."', '".$formulario."', '".$fl."', '".$partesArray[$g][0]."', '".$partesArray[$g][2]."', now())");
                        }else{
                            $ins = $this->conexao->query("INSERT INTO `tb_formulario_reposta` (`controle`, `fk_usuario`, `fk_formulario`, `fk_filial`, `fk_pergunta`, `resposta`, `data_insert`) VALUES ('".$controle."', '".$usuario."', '".$formulario."', '".$fl."', '".$partesArray[$g][0]."', '".$partesArray[$g][2]."', now())");
                        }
                    }
                    $g++;
                }

                if($ins){
                    header("Location: ../index.php?url=meus-indicadores-ins"); exit;
                }else{
                     header("Location: ../index.php?url=meus-indicadores-fail"); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }



        public function updateFormRespostas($dados, $pr){
            try{
                $usuario    = $dados['user'];
                $formulario = $dados['formulario'];
                $controle   = $dados['controle'];

                $frm = base64_encode($formulario);
                $crt = base64_encode($controle);

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
                $ins = $this->conexao->query("UPDATE `tb_formulario_reposta` SET `fk_resposta` = null WHERE `controle` = '".$dados['controle']."'");

                while($g < $count){
                    if($partesArray[$g][1] == 'CHECKBOX *'){
                        $t = 2;
                        $cont = count($partesArray[$g]);
                        $sel  = $this->conexao->query("SELECT data_insert FROM `tb_formulario_reposta` WHERE `controle` = '".$dados['controle']."'");
                        $dt_ins = $sel->fetch(PDO::FETCH_ASSOC);
                        $ins  = $this->conexao->query("DELETE FROM `tb_formulario_reposta` WHERE `controle` = '".$dados['controle']."' AND `fk_pergunta` = '".$partesArray[$g][0]."'");
                        while($t < $cont){
                            $ins = $this->conexao->query("INSERT INTO `tb_formulario_reposta` (`controle`, `fk_usuario`, `fk_formulario`, `fk_pergunta`, `fk_resposta`, `data_insert`) VALUES ('".$controle."', '".$usuario."', '".$formulario."', '".$partesArray[$g][0]."', '".$partesArray[$g][$t]."', '".$dt_ins['data_insert']."')");
                            $t++;
                        }
                    }else{
                        if(($partesArray[$g][1] == 'SELECT *') || ($partesArray[$g][1] == 'RADIO *')){
                            $ins = $this->conexao->query("UPDATE `tb_formulario_reposta` SET `controle` = '".$controle."', `fk_usuario` = '".$usuario."', `fk_formulario` = '".$formulario."', `fk_pergunta` = '".$partesArray[$g][0]."', `fk_resposta` = '".$partesArray[$g][2]."' WHERE `controle` = '".$dados['controle']."' AND `fk_pergunta` = '".$partesArray[$g][0]."'");
                        }else{
                            $ins = $this->conexao->query("UPDATE `tb_formulario_reposta` SET `controle` = '".$controle."', `fk_usuario` = '".$usuario."', `fk_formulario` = '".$formulario."', `fk_pergunta` = '".$partesArray[$g][0]."', `resposta` = '".$partesArray[$g][2]."' WHERE `controle` = '".$dados['controle']."' AND `fk_pergunta` = '".$partesArray[$g][0]."'");
                        }
                    }
                    $g++;
                }
                if($ins){
                    header("Location: ../index.php?url=indicadores-form-edit-ins&id=".$frm."&ctrl=".$crt); exit;
                }else{
                     header("Location: ../index.php?url=indicadores-form-edit-fail&id=".$frm."&ctrl=".$crt); exit;
                }
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }






        public function meusIndicadores($id){
            try{
                if(!isset($_SESSION['UserID'])){
                    session_start();
                }
                $fl = $_SESSION['UserFilialLogada'];

                $consulta = $this->conexao->query("
                        SELECT DISTINCT
                            tf.id as id_form,
                            tf.nome as formulario,
                            DATE_FORMAT(tfr.data_insert, '%d/%m/%Y %H:%i') as data,
                            tfr.controle as controle
                        FROM
                            tb_formulario_reposta tfr
                            INNER JOIN tb_formulario tf ON tf.id = tfr.fk_formulario
                        WHERE
                            tfr.fk_usuario = '".$id."'
                            AND tfr.fk_filial = '".$fl."'");
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
                        f.nome as formulario,
                        tfr.controle,
                        tp.descricao AS pergunta,
                        CASE WHEN tfr.resposta IS NULL THEN tr.descricao ELSE tfr.resposta END AS resposta
                    FROM
                        tb_formulario_reposta tfr
                        INNER JOIN tb_formulario f ON f.id = tfr.fk_formulario
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

        public function dadosFormulario($id, $ctrl){
            
            try{
                $consulta = $this->conexao->query("
                    SELECT
                        f.id as id,
                        f.nome as nome,
                        f.descricao as formulario,
                        u.nome as criador,
                        p.id as id_pergunta,
                        p.descricao as pergunta,
                        tr.descricao as tipo_resposta,
                        r.id,
                        r.descricao,
                        CASE WHEN p.fk_tipo_resposta = '3' AND tfr.fk_resposta IS NOT NULL THEN 'checked'
                             WHEN p.fk_tipo_resposta = '4' AND tfr.fk_resposta IS NOT NULL THEN 'selected'
                             WHEN p.fk_tipo_resposta = '5' AND tfr.fk_resposta IS NOT NULL THEN 'checked'
                             ELSE tfr.resposta END AS resposta
                    FROM
                        tb_pergunta p
                        INNER JOIN tb_tipo_resposta tr ON tr.id = p.fk_tipo_resposta
                        INNER JOIN tb_formulario f ON f.id = p.fk_formulario
                        INNER JOIN tb_usuario u ON u.id = f.fk_user_criador
                        LEFT JOIN tb_respostas r ON r.fk_pergunta = p.id
                        LEFT JOIN tb_formulario_reposta tfr on tfr.fk_pergunta = p.id
                    WHERE
                        f.id = '".$id."'
                        AND controle = '".$ctrl."'
                    GROUP BY
                        f.id,
                        f.nome,
                        f.descricao,
                        u.nome,
                        p.id,
                        p.descricao,
                        tr.descricao,
                        r.id,
                        r.descricao,
                        CASE WHEN p.fk_tipo_resposta = '3' AND tfr.fk_resposta IS NOT NULL THEN 'checked'
                             WHEN p.fk_tipo_resposta = '4' AND tfr.fk_resposta IS NOT NULL THEN 'selected'
                             WHEN p.fk_tipo_resposta = '5' AND tfr.fk_resposta IS NOT NULL THEN 'checked'
                             ELSE tfr.resposta END
                    ORDER BY
                        p.sequencia,
                        r.sequencia");
                $retorno = $consulta->fetchAll(PDO::FETCH_ASSOC);
                #echo '<pre>';
                #print_r($retorno);
                return $retorno;
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }


        public function deleteForm($ctrl){
            
            try{
                $consulta = $this->conexao->query("DELETE FROM `tb_formulario_reposta` WHERE controle = '".$ctrl."'");
            }catch(PDOException $erro){
                return 'error'.$erro->getMessage();
            }
        }



    }



    
?>
