<?php
	require_once 'class/group.class.php';
	$group 	= new Group();
	$grupos = $group->consultaGroup();
	$user 	= $group->consultaUser();

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
					<h1 class="m-0">Usuários</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a> / Administração de Usuários</li>
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
            		<h3 class="card-title">Cadastrar Usuário</h3>
            	</div>

            	<div style="padding: 20px;">
            		<form action="controller/user-controller.php" method="POST">
						<div class="form-group" style="width: 48%; float: left; margin-right: 4%">
							<label for="nome">Nome do Usuário</label>
							<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do usuário..." required="">
						</div>

						<div class="form-group" style="width: 48%; float: left;">
							<label for="email">E-mail</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="mail@seuemail.com.br" required="">
						</div>

						<div class="form-group" style="width: 48%; float: left; margin-right: 4%">
							<label for="senha">Senha</label>
							<input type="password" class="form-control" id="senha" name="senha" placeholder="Sua senha ***" required="" oninput="validarSenha()">
						</div>

						<div class="form-group" style="width: 48%; float: left;">
							<label for="confirm">Repetir Senha</label>
							<input type="password" class="form-control" id="confirm" name="confirm" placeholder="Repetir senha ***" oninput="validarSenha()">
							<span id="mensagemErro" style="color: red;"></span>
						</div>
						
						<div class="form-group">
							<label for="grupo">Grupo</label>
							<select class="form-control" id="grupo" name="grupo" required="">
								<option value="">-- SELECIONE --</option>
								<?php
									$j = 0;
									$cont = count($grupos);
									while($j < $cont){
										echo '<option value="'.$grupos[$j]['id'].'">'.$grupos[$j]['id'] . ' - ' . $grupos[$j]['descricao'].'</option>';
										$j++;
									}
								?>
							</select>
						</div>

						<button type="submit" class="btn btn-info" name="addUser" id="addUser" style="float: right;" title="Salvar formulário">SALVAR</button>
					</form>
            	</div>
			    
			</div>
		</div>
		<!-- /.container-fluid -->

		<hr>

		<div class="container-fluid">
			<div class="card card-info">
            	<div class="card-header" style="background: #74b2d2">
            		<h3 class="card-title">Usuários Cadastrados</h3>
            	</div>

				<div style="padding: 30px">
                	<!-- /.card-header -->
                	<table id="tableForm" class="table table-striped" style="width:100%">
    					<thead>
    						<tr>
    							<th scope="col">ID</th>
    							<th scope="col">NOME</th>
    							<th scope="col">E-MAIL</th>
    							<th scope="col">GRUPO</th>
    							<th scope="col">STATUS</th>
    							<th scope="col">AÇÃO</th>
    						</tr>
    					</thead>
    					<tbody>
    						<?php
    							$g = 0;
    							$cont = count($user);
    							while($g < $cont){
    								echo '
    									<tr>
			    					    	<td>'.$user[$g]['id'].'</td>
			    					    	<td>'.$user[$g]['nome'].'</td>
			    					    	<td>'.$user[$g]['email'].'</td>
			    					    	<td>'.$user[$g]['grupo'].'</td>
			    					    	<td>'.$user[$g]['status'].'</td>
			    					    	<td>
			    					    		<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editUser'.$user[$g]['id'].'">
			    					    			EDITAR
		    					    			</button>
		    					    			<!-- Modal Editar-->
												<div class="modal fade" id="editUser'.$user[$g]['id'].'" tabindex="-1" role="dialog" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="exampleModalLabel">Editar Usuário</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<form action="controller/user-controller.php" method="POST">
																<div class="modal-body">
																	<input type="hidden" name="id" value="'.$user[$g]['id'].'">
																	<b>ID</b><input type="text" class="form-control" value="'.$user[$g]['id'].'" disabled><br>
																	<b>NOME DO USUÁRIO</b><input type="text" class="form-control" name="nome" value="'.$user[$g]['nome'].'"><br>

																	<b>EMAIL</b><input type="email" class="form-control" name="email" value="'.$user[$g]['email'].'"><br>
																	<b>GRUPO</b>
																		<select class="form-control" name="grupo">
																			<option value="">-- SELECIONE --</option>';
																			$g1 = 0;
																			$gr = count($grupos);
																			while($g1 < $gr){
																				?>
																					<option <?php echo 'value="'.$user[$g]['grupo_id']. '"';?>
																						<?php
																							if($user[$g]['grupo_id'] == $grupos[$g1]['id']){
																								echo 'selected';
																							}
																						?>
																					>
																						<?=$grupos[$g1]['descricao']?>
																					</option>
																				<?php
																				$g1++;
																			}
																	echo '</select><br>

																	<b>STATUS</b>
																		<select class="form-control" name="status">';
																			if($user[$g]['status'] == 'Ativo'){
																				echo '
																					<option value="A" selected>Ativo</option>
																					<option value="I">Inativo</option>';
																			}else{
																				echo '
																					<option value="A">Ativo</option>
																					<option value="I" selected>Inativo</option>';
																			}
																		echo '</select>
																	<br>
																</div>
																
																<div class="modal-footer">
																	<button type="submit" name="alterUser" class="btn btn-primary">Salvar</button>
																	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
																</div>
															</form>
														</div>
													</div>
												</div>

		    					    			<button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#delUser'.$user[$g]['id'].'">
			    					    			EXCLUIR
		    					    			</button>
		    					    			<!-- Modal Excluir-->
												<div class="modal fade" id="delUser'.$user[$g]['id'].'" tabindex="-1" role="dialog" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="exampleModalLabel">Excluir Grupo</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																Deseja realmente excluir o usuário <b>'.$user[$g]['nome'].'</b>?
															</div>
															<form action="controller/user-controller.php" method="POST">
																<input type="hidden" name="id" value="'.$user[$g]['id'].'">
																<div class="modal-footer">
																	<button type="submit" name="deleteUser" class="btn btn-primary">Excluir</button>
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


    function validarSenha() {
        var novaSenha = document.getElementById("senha").value;
        var repetirSenha = document.getElementById("confirm").value;
        var mensagemErro = document.getElementById("mensagemErro");

        if (novaSenha !== repetirSenha) {
            mensagemErro.textContent = "As senhas não são iguais.";
            var botao = document.getElementById("addUser");
            botao.disabled = true;
            return false;
        } else {
            mensagemErro.textContent = "";
            var botao = document.getElementById("addUser");
            botao.disabled = false;
            return true;
        }
    }
</script>

<?php
    if($_GET['url'] == 'admin-ins'){
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

    if($_GET['url'] == 'admin-success'){
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

    if($_GET['url'] == 'admin-fail'){
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
?>


