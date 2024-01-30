<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

	require_once 'class/indicadores.class.php';
	$ind	= new Indicadores();
	$meus	= $ind->meusIndicadores($_SESSION['UserID']);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<style type="text/css">
	#tableForm_filter{
		float: right;
	}
    .dados-form{

    }
</style>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Meus Indicadores</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a> / Meus Indicadores</li>
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
		<div class="container-fluid" style="width: 50% !important; display: flow-root; min-width: 790px !important">
			<div class="card card-info">
            	<div class="card-header" style="background: #74b2d2">
            		<h3 class="card-title">Meus Indicadores</h3>
            	</div>

                <div class="card-body">
                    <table id="meusFormularios" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Formulário</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $g = 0;
                                $count = count($meus);
                                $dataAtual = new DateTime();
                                #echo '<pre>'; print_r($meus);
                                while($g < $count){
                                    $dataArmazenada = DateTime::createFromFormat('d/m/Y H:i', $meus[$g]['data']);
                                    $dif = $dataAtual->diff($dataArmazenada)->days;
                                    $id_ind = base64_encode($meus[$g]['id_form']);
                                    $ctrl = base64_encode($meus[$g]['controle']);
                                    if($dif > 15){
                                        $disable = 'disabled';
                                    }else{
                                        $disable = '';
                                    }

                                    ?>
                                        <tr>
                                            <td style="width: 50%"><?=$meus[$g]['formulario']?></td>
                                            <td style="width: 20%"><?=$meus[$g]['data']?></td>
                                            <td style="width: 30%">
                                                <button type="button" class="btn btn-info btn-sm" onclick="carregarConteudo(<?=$meus[$g]['controle']?>)">Visualizar</button>
                                                <a href="index.php?url=indicadores-form-edit&id=<?=$id_ind?>&ctrl=<?=$ctrl?>" <?=$disable?> class="btn btn-info btn-sm" >Editar</a>
                                                <button type="button" id="btnExcluir" class="btn btn-info btn-sm" <?=$disable?> onclick="excluirForm(<?=$meus[$g]['controle']?>)">Excluir</button>
                                            </td>
                                        </tr>
                                    <?php
                                    $g++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>		    
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>

<!--------------------MODAL VISUALIZAR-------------------->
<div class="modal fade bd-example-modal-lg" id="meuModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="confExcluir" class="btn btn-warning">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var dadosArray = null;

    $(document).ready(function() {
        // Evento disparado quando o modal é fechado
        $('#meuModal').on('hidden.bs.modal', function () {
            // "Zera" a variável dados
            dadosArray = null;
        });

        $('#meusFormularios').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
            },
        });
    });
        
    // Função para carregar conteúdo usando AJAX e chamar uma função específica com os dados
    function carregarConteudo(numeroControle){
        $.ajax({
            url: 'class/indicadores.class.php',
            type: 'POST',
            data: { controle: numeroControle, action: 'dadosForm' },
            success: function(response) {
                console.log('Dados recebidos:', response);

                var dadosArray = JSON.parse(response);
                var cont = 1;

                $.each(dadosArray, function(index, dados){
                    // Cria novos elementos no modal para cada linha de retorno
                    var linhaHTML = "<div class='dados-form'>" +
                        "<label>" + cont++ + " - " + dados.pergunta + "</label><br>" +
                        "<span>" + dados.resposta + "</span><br><br>"
                        "</div>";
                    // Adiciona a linha HTML ao modal
                    $('#meuModal .modal-body').append(linhaHTML);
                });
                var titulo = dadosArray[0].formulario;
                $('#meuModal #modalLabel').append(titulo);
                $('#meuModal').modal('show');
            },
            error: function() {
                console.log('Erro ao carregar conteúdo.');
            }
        });
    }


    function excluirForm(numeroControle) {
        // Configuração do modal
        var modal = $('#modalExcluir');
        modal.find('.modal-title').text('Excluir Registro');
        modal.find('.modal-body').html('Deseja realmente excluir o registro?');

        // Configuração dos botões do modal
        var btnSaveChanges = modal.find('#confExcluir');
        btnSaveChanges.off('click'); // Remove qualquer handler de clique anterior
        btnSaveChanges.on('click', function () {
            // Aqui você pode adicionar a lógica Ajax para excluir o registro
            // Certifique-se de ajustar a URL e outros parâmetros conforme necessário
            $.ajax({
                type: 'POST',
                url: 'class/indicadores.class.php',
                data: { controle: numeroControle, action: 'excluirForm'},
                dataType: 'json', // Indica que você espera uma resposta em JSON
                success: function(response) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Formulário Excluído!',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao enviar dados:', error);
                }
            });

            // Fechar o modal após o processamento Ajax
            modal.modal('hide');
        });

        // Exibe o modal
        modal.modal('show');
    }

</script>





<?php
	if($_GET['url'] == 'meus-indicadores-ins'){
        echo '
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Dados inseridos com sucesso!",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        ';
    }

    if($_GET['url'] == 'meus-indicadores-fail'){
        echo '
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "Ocorreu um erro ao executar a ação!",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        ';
    }
/*
    if($_GET['url'] == 'form-success'){
        echo '
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Ação bem sucedida!",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        ';
    }

*/

?>