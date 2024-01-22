<?php
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
		<div class="container-fluid" style="width: 50% !important; display: flow-root;">
			<div class="card card-info">
            	<div class="card-header" style="background: #74b2d2">
            		<h3 class="card-title">Meus Indicadores</h3>
            	</div>

                <div class="card-body">
                    <table class="table table-bordered">
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
                                while($g < $count){
                                    ?>
                                        <tr>
                                            <td style="width: 50%"><?=$meus[$g]['formulario']?></td>
                                            <td style="width: 20%"><?=$meus[$g]['data']?></td>
                                            <td style="width: 30%">
                                                <button type="button" class="btn btn-info btn-sm" onclick="carregarConteudo(<?=$meus[$g]['controle']?>)">Visualizar</button>
                                                <button type="button" class="btn btn-info btn-sm">Editar</button>
                                                <button type="button" class="btn btn-info btn-sm">Excluir</button>
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
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
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
    });
        
    // Função para carregar conteúdo usando AJAX e chamar uma função específica com os dados
    function carregarConteudo(numeroControle) {
        $.ajax({
            url: 'class/indicadores.class.php',
            type: 'POST',
            data: { controle: numeroControle, action: 'dadosForm' },
            success: function(response) {
                console.log('Dados recebidos:', response);

                var dadosArray = JSON.parse(response);
                var cont = 1;

                $.each(dadosArray, function(index, dados) {
                    // Cria novos elementos no modal para cada linha de retorno
                    var linhaHTML = "<div class='dados-form'>" +
                        "<label>" + cont++ + " - " + dados.pergunta + "</label><br>" +
                        "<span>" + dados.resposta + "</span><br><br>"
                        "</div>";

                    // Adiciona a linha HTML ao modal
                    $('#meuModal .modal-body').append(linhaHTML);
                });

                $('#meuModal').modal('show');
            },
            error: function() {
                console.log('Erro ao carregar conteúdo.');
            }
        });
    }
</script>



<?php /*
	if($_GET['url'] == 'form-ins'){
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

    if($_GET['url'] == 'form-fail'){
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