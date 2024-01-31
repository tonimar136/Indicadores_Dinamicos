<?php
	require_once 'class/user.class.php';
	$usr    = new Usuario();
	$filial = $usr->consultaFilial($_SESSION['UserFilial']);
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Sistema de Indicadores Dinâmicos</h1>
			</div>
			<!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="#">Inicio</a></li>
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
	<div class="container-fluid">
	    <center>
            <h3>Bem Vindo!</h3>
	        <img src="images/chart-bar.png" width="25%" style="margin-top: 60px">
	        <?php
	        	if($_SESSION['UserFilialLogada'] == null){
	        		?>
	        			<!-- Modal -->
						<div class="modal" tabindex="-1" role="dialog" id="filialModal" data-backdrop="static" data-keyboard="false">
						    <div class="modal-dialog" role="document">
						        <div class="modal-content">
						            <div class="modal-header">
						                <h5 class="modal-title">Selecione a Filial</h5>
						            </div>
						            <div class="modal-body">
						            	<select id="filialSelect" class="form-control">
						            		<?php
						            			$t = 0;
						            			$ct = count($filial);
						            			while($t < $ct){
						            				echo '<option value="'.$filial[$t]['id'].'">'.$filial[$t]['descricao'].'</option>';
						            				$t++;
						            			}
						            		?>
						                </select>
						            </div>
						            <div class="modal-footer">
						                <button type="button" class="btn btn-primary" onclick="selecionarFilial()">Selecionar Filial</button>
						            </div>
						        </div>
						    </div>
						</div>

						<script>
							$(document).ready(function() {
							    $("#filialModal").modal("show");
							});

							// Função para selecionar a filial
							function selecionarFilial() {
							    // Obtém o valor selecionado
							    var filialSelecionada = $("#filialSelect").val();

							    // Executa uma chamada AJAX para definir a variável de sessão
							    // Certifique-se de ajustar o caminho para o seu script PHP de definição de sessão
							    $.ajax({
							        type: "POST",
							        url: "parts/definir_filial.php",
							        data: { filialSelecionada: filialSelecionada },
							        success: function(response) {
							            if (response === "success") {
							                // Fecha o modal após a seleção
							                $("#filialModal").modal("hide");
							                window.location.href = 'index.php?url=home';
							            } else {
							                // Trate possíveis erros ou exiba mensagens de erro
							                console.error("Erro ao definir a filial.");
							            }
							        },
							        error: function(xhr, status, error) {
							            // Trate erros de requisição AJAX
							            console.error("Erro na requisição AJAX:", status, error);
							        }
							    });
							}
						</script>';
					<?php
	        	}
	        ?>
	    </center>
	    
	</div>
	<!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>