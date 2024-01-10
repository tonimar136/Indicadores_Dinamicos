<?php
	error_reporting(E_ALL);
    ini_set('display_errors', 1);

	require_once 'class/group.class.php';
    $groups = new Group();
    $resultado_groups = $groups->consultaGroup();
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
					<h1 class="m-0">Grupos</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a> / Administração de Grupos</li>
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
            		<h3 class="card-title">Cadastrar Grupos</h3>
            	</div>

            	<div style="padding: 20px;">
            		<form action="controller/group-controller.php" method="POST">
						<div class="form-group">
							<label for="nome">Descrição do Grupo</label>
							<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do grupo..." required="">
						</div>
						
						<button type="submit" class="btn btn-info" style="float: right;" name="addGroup" title="Salvar formulário">SALVAR</button>
					</form>
            	</div>
			    
			</div>
		</div>
		<!-- /.container-fluid -->

		<hr>

		<div class="container-fluid" style="width: 50%">
			<div class="card card-info">
            	<div class="card-header" style="background: #74b2d2">
            		<h3 class="card-title">Grupos Cadastrados</h3>
            	</div>

				<div style="padding: 30px">
                	<!-- /.card-header -->
                	<table id="tableForm" class="table table-striped" style="width:100%">
    					<thead>
    						<tr>
    							<th scope="col">ID</th>
    							<th scope="col">DESCRIÇÃO</th>
    							<th scope="col">STATUS</th>
    							<th scope="col">AÇÃO</th>
    						</tr>
    					</thead>
    					<tbody>
							<?php
								$g = 0;
								$cont = count($resultado_groups);
								while($g < $cont){
									echo '
										<tr>
			    					    	<td>'.$resultado_groups[$g]['id'].'</td>
			    					    	<td>'.$resultado_groups[$g]['descricao'].'</td>
			    					    	<td>'.$resultado_groups[$g]['status'].'</td>
			    					    	<td>
			    					    		<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editGroup'.$resultado_groups[$g]['id'].'">
			    					    			EDITAR
		    					    			</button>
		    					    			<!-- Modal Editar-->
												<div class="modal fade" id="editGroup'.$resultado_groups[$g]['id'].'" tabindex="-1" role="dialog" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="exampleModalLabel">Editar Grupo</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<form action="controller/group-controller.php" method="POST">
																<div class="modal-body">
																	<input type="hidden" name="id" value="'.$resultado_groups[$g]['id'].'">
																	<b>ID</b><input type="text" class="form-control" value="'.$resultado_groups[$g]['id'].'" disabled><br>
																	<b>DESCRIÇÃO</b><input type="text" class="form-control" name="descricao" value="'.$resultado_groups[$g]['descricao'].'"><br>
																	<b>STATUS</b>
																	<select class="form-control" name="status">
																		<option value="A">Ativo</option>
																		<option value="I">Inativo</option>
																	</select>
																</div>
																<div class="modal-footer">
																	<button type="submit" name="editGroup" class="btn btn-primary">Editar</button>
																	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
																</div>
															</form>
														</div>
													</div>
												</div>

			    					    		<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#delGroup'.$resultado_groups[$g]['id'].'">
			    					    			EXCLUIR
		    					    			</button>
		    					    			<!-- Modal Editar-->
												<div class="modal fade" id="delGroup'.$resultado_groups[$g]['id'].'" tabindex="-1" role="dialog" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="exampleModalLabel">Excluir Grupo</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																Deseja realmente excluir o registro?<br>
																<b>ID: </b>'.$resultado_groups[$g]['id'].'<br>
																<b>Descrição: </b>'.$resultado_groups[$g]['descricao'].'
															</div>
															<form action="controller/group-controller.php" method="POST">
																<input type="hidden" name="id" value="'.$resultado_groups[$g]['id'].'">
																<div class="modal-footer">
																	<button type="submit" name="delGroup" class="btn btn-primary">Excluir</button>
																	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
																</div>
															</form>
														</div>
													</div>
												</div>
			    					    	</td>
			    					    </tr> 
									';
									$g++;
								}

							?>   					    
                	    </tbody>
                	</table>
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
</script>

<?php
    if($_GET['url'] == 'group-ins'){
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

    if($_GET['url'] == 'group-fail'){
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

    if($_GET['url'] == 'group-success'){
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
