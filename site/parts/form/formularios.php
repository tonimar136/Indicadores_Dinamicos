<?php
	require_once 'class/form.class.php';
	$formulario	= new Formulario();
	$form 		= $formulario->consultaForm();
    $grup       = $formulario->consultaGrupos();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
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
					<h1 class="m-0">Formulários</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a> / Administração de Formulários</li>
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
            		<h3 class="card-title">Cadastrar Formulário</h3>
            	</div>

            	<div style="padding: 20px;">
            		<form action="controller/form-controller.php" method="POST">
						<div class="form-group">
							<label for="nome">Nome do Formulário</label>
							<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do seu novo formulário..." required="">
						</div>
						<div class="form-group">
							<label for="descricao">Descrição</label>
							<input type="text" class="form-control" id="descricao" name="descricao" aria-describedby="descHelp" placeholder="Descrição...">
							<small id="descHelp" class="form-text text-muted">Digite uma breve descrição para seu formulário.</small>
						</div>
                        <div class="form-group">
                            <label for="nomeForm">Selecione os grupos que terão acesso a este formulário: </label><br>
                            <?php
                                $i = 0;
                                $ct = count($grup);
                                while($i < $ct){
                                        echo '<input type="checkbox" value="'.$grup[$i]['id'].'" name="grupo[]"/> '.$grup[$i]['descricao'].' <br>';
                                    $i++;
                                }
                            ?>
                        </div>
						
						<button type="submit" name="insertForm" class="btn btn-info" style="float: right;" title="Salvar formulário">SALVAR</button>
					</form>
            	</div>
			    
			</div>
		</div>
		<!-- /.container-fluid -->

		<hr>

		<div class="container-fluid">
			<div class="card card-info">
            	<div class="card-header" style="background: #74b2d2">
            		<h3 class="card-title">Formulários Cadastrados</h3>
            	</div>

				<div style="padding: 30px">
                	<!-- /.card-header -->
                	<table id="tableForm" class="table table-striped" style="width:100%">
    					<thead>
    						<tr>
    							<th scope="col">ID</th>
    							<th scope="col">NOME</th>
    							<th scope="col">DESCRIÇÃO</th>
    							<th scope="col">STATUS</th>
    							<th scope="col">AÇÃO</th>
    						</tr>
    					</thead>
    					<tbody>
    						<?php
    							$j = 0;
    							$cont = count($form);
    							while($j < $cont){
                                    $id_form = base64_encode($form[$j]['id']);
    								echo '
    									<tr data-id="'.$form[$j]['id'].'">
			    					    	<td>'.$form[$j]['id'].'</td>
			    					    	<td>'.$form[$j]['nome'].'</td>
			    					    	<td>'.$form[$j]['descricao'].'</td>
			    					    	<td>'.$form[$j]['status'].'</td>
			    					    	<td>
                                                <button type="button" class="btn btn-info btn-sm" onclick="window.location.href = \'index.php?url=form-detail&reg='.$id_form.'\';">Editar</button>
                                                <!--<button type="button" class="btn btn-warning btn-sm btnExcluir" data-toggle="modal" data-target="#excluir" data-id="'.$form[$j]['id'].'">Excluir</button>-->
                                            </td>
			    					    </tr>
    								';
    								$j++;
    							}
    						?>    					    
                	    </tbody>
                	</table>
            	</div>
            </div>
		</div>

        <!-- Modal -->
        <div class="modal fade" id="excluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Excluir Registro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="conteudoModal">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary delete-form">Confirmar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>




	</section>
	<!-- /.content -->
</div>

<script>
    $(document).ready(function(){
        $('#tableForm').DataTable({
            "language": {"url": "//cdn.datatables.net/plug-ins/1.13.3/i18n/pt-BR.json"}
        });
    });


    $(document).ready(function() {
        $('#tableForm').on('click', '.btnExcluir', function() {
            var idParaExcluir = $(this).data('id');
            $('#excluir').find('.delete-form').data('id', idParaExcluir);
            $('#excluir').find('#conteudoModal').text('Deseja realmente excluir o Formulário ID Nº: ' + idParaExcluir + ' ?');
        });

        // Submissão dos dados via AJAX quando o botão "Confirmar" no modal for clicado
        $('#excluir').on('click', '.delete-form', function() {
            var idParaExcluir = $(this).data('id');
            $.ajax({
                url: 'controller/form-controller.php',
                method: 'POST',
                data: { 'delete-formulario': '', 'id': idParaExcluir },

                success: function(data) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Dados excluídos sucesso!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Fechar o modal após a exclusão
                    $('#excluir').modal('hide');
                },
                error: function(err) {
                    // Em caso de erro na requisição, você pode lidar com isso aqui
                    console.error('Erro ao excluir:', err);
                }
            });
        });
    });
</script>

<?php
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



?>