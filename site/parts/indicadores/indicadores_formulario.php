<?php
    $id = base64_decode($_GET['id']);
	require_once 'class/indicadores.class.php';
	$ind	= new Indicadores();
	$formulario	= $ind->consultaFormulario($id);
    #session_start();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>
<style type="text/css">
	#tableForm_filter{
		float: right;
	}
</style>>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Responder Formulário</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a> / Responder Formulário</li>
					</ol>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->
	<!-- Main content -->
	<section class="content">
		<div class="container-fluid" style="width: 50% !important; display: flow-root;">
			<div class="card card-info">
            	<div class="card-header" style="background: #74b2d2">
            		<h3 class="card-title">Formulário</h3>
            	</div>

                <div class="card-body">
                    
                    <?php
                        if(empty($formulario)){
                            echo '<center><h5>Não foram cadastrado perguntas neste formulário, informe ao responsável.</h5></center>';
                        }else{
                    ?>
                        <center>
                            <h3><?php echo $formulario[0]['id'] . ' - ' . $formulario[0]['formulario'] ?></h3>
                            <span>Criado por <?=$formulario[0]['criador']?>
                        </center>

                        <hr>

                        <form action="controller/indicadores-controller.php" method="POST">
                            <div class="form-group">
                                <?php $agora = date('YmdHis');?>
                                <input type="hidden" name="formulario" value="<?=$formulario[0]['id']?>">
                                <input type="hidden" name="id_user" value="<?=$_SESSION['UserID']?>">
                                <input type="hidden" name="controle" value="<?=$agora?>">
                                <?php
                                    $j = 0;
                                    $t = 1;
                                    $cont = count($formulario);
                                    while ($j < $cont) {
                                        ?>
                                            <div class="form-group">
                                                <b><?=$t?> - </b><label for="descricao"><?=$formulario[$j]['pergunta']?></label>
                                                <input type="hidden" name="id_pergunta<?=$t?>" value="<?=$formulario[$j]['id_pergunta']?>">
                                                <input type="hidden" name="tiporesposta<?=$t?>" value="<?=$formulario[$j]['tipo_resposta']?>">
                                                <?php
                                                    $select = $ind->consultaRespostas($formulario[$j]['id_pergunta']);

                                                    if($formulario[$j]['tipo_resposta'] == 'SELECT *'){
                                                        echo '<select class="form-control" name="resposta'.$t.'">';
                                                            echo '<option>--SELECIONE--</option>';
                                                            $g = 0;
                                                            $conta = count($select);
                                                            while($g < $conta){
                                                                echo '<option value="'.$select[$g]['id'].'">'.$select[$g]['descricao'].'</option>';
                                                                $g++;
                                                            }
                                                        echo '</select>';
                                                    }elseif($formulario[$j]['tipo_resposta'] == 'RADIO *'){
                                                        $g = 0;
                                                        $conta = count($select);
                                                        while($g < $conta){
                                                            echo '<br><input type="radio" name="resposta'.$t.'" value="'.$select[$g]['id'].'">'.$select[$g]['descricao'];
                                                            $g++;
                                                        }
                                                    }elseif($formulario[$j]['tipo_resposta'] == 'CHECKBOX *'){
                                                        $g = 0;
                                                        $conta = count($select);
                                                        while($g < $conta){
                                                            echo '<br><input type="checkbox" name="resposta-ck'.$t.'-'.$g.'" value="'.$select[$g]['id'].'">'.$select[$g]['descricao'];
                                                            $g++;
                                                        }
                                                    }else{
                                                        if($formulario[$j]['tipo_resposta'] == 'DATA'){
                                                            echo '<input type="date" class="form-control" name="resposta'.$t.'"';
                                                        }elseif($formulario[$j]['tipo_resposta'] == 'APENAS NÚMEROS'){
                                                            echo '<input type="text" class="form-control apenas-numeros" name="resposta'.$t.'">';
                                                        }elseif($formulario[$j]['tipo_resposta'] == 'CEP'){
                                                            echo '<input type="text" class="form-control cep-input" name="resposta'.$t.'" maxlength="9" placeholder="Digite o CEP">';
                                                        }elseif($formulario[$j]['tipo_resposta'] == 'CPF/CNPJ'){
                                                            echo '<input type="text" class="form-control cpf-cnpj" name="resposta'.$t.'" placeholder="Digite sua resposta...">';
                                                        }elseif($formulario[$j]['tipo_resposta'] == 'TEXTO ÁREA'){
                                                            echo '<textarea class="form-control" name="resposta'.$t.'" rows="3"></textarea>';
                                                        }else{
                                                            echo '<input type="text" class="form-control" name="resposta'.$t.'" placeholder="Digite sua resposta...">';
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        <?php
                                        $j++;
                                        $t++;
                                    }
                                ?>
                            </div>
                            <input style="float: right;" type="submit" class="btn btn-primary" name="salvarIndicadores" value="Salvar">
                        </form>
                    <?php } ?>
                </div>		    
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>

<script>
    //FUNÇÃO MASCARA CEP
    function formatarCEP() {
        // Obtém o valor do input
        let cepInput = document.querySelector('.cep-input');
        let cepValue = cepInput.value;
        // Remove caracteres não numéricos do valor
        cepValue = cepValue.replace(/\D/g, '');
        // Adiciona a máscara de CEP (XXXXX-XXX)
        cepValue = cepValue.replace(/^(\d{5})(\d{3})$/, '$1-$2');
        // Atualiza o valor no input
        cepInput.value = cepValue;
    }
    // Adiciona o evento input ao input com a classe 'cep-input'
    document.querySelector('.cep-input').addEventListener('input', formatarCEP);


    //FUNÇÃO APENAS NUMEROS
        function apenasNumeros(event) {
            const campoInput = event.target;
            let valorAtual = campoInput.value;

            // Remove caracteres não numéricos ou vírgula decimal extras
            valorAtual = valorAtual.replace(/[^\d,]/g, '');

            // Garante que haja no máximo uma vírgula decimal
            const virgulas = valorAtual.split(',').length - 1;
            if (virgulas > 1) {
                valorAtual = valorAtual.slice(0, valorAtual.lastIndexOf(','));
            }

            // Substitui ponto por vírgula
            valorAtual = valorAtual.replace('.', ',');

            // Atualiza o valor no input
            campoInput.value = valorAtual;
        }
        // Adiciona o evento input ao input com a classe 'numero-decimal-input'
        document.querySelector('.apenas-numeros').addEventListener('input', apenasNumeros);


    //FUNÇÃO CPF / CNPJ
        $(document).ready(function() {
            $('.cpf-cnpj').inputmask({
                mask: ['999.999.999-99', '99.999.999/9999-99'],
                greedy: false,
                definitions: {
                    '9': {
                        validator: '[0-9]',
                        cardinality: 1
                    }
                },
                onBeforePaste: function (pastedValue, opts) {
                    var replacedValue = pastedValue.replace(/\D/g, '');
                    return replacedValue.substring(0, 14);
                },
                
            });
        });
</script>
