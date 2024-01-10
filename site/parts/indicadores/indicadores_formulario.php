<?php
    $id = base64_decode($_GET['id']);
	require_once 'class/indicadores.class.php';
	$ind	= new Indicadores();
	$formulario	= $ind->consultaFormulario($id);
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
                    <center>
                    	<?php #echo '<pre>'; print_r($formulario); die;?>
                        <h3><?php echo $formulario[0]['id'] . ' - ' . $formulario[0]['formulario'] ?></h3>
                        <span>Criado por <?=$formulario[0]['criador']?>
                    </center>

                    <hr>
                    <form action="" method="POST">
                        <?php
                            $j = 0;
                            $cont = count($formulario);
                            while ($j < $cont) {
                                ?>
                                    <div class="form-group">
                                        <label for="descricao"><?=$formulario[$j]['pergunta']?></label>
                                        <input type="hidden" name="formulario" value="<?=$formulario[$j]['id']?>">
                                        <input type="hidden" name="id_pergunta" value="<?=$formulario[$j]['id_pergunta']?>">
                                        <input type="text" class="form-control" name="resposta" aria-describedby="descHelp" placeholder="Descrição...">
                                    </div>
                                <?php
                                $j++;
                            }
                        ?>
                    </form>
                </div>		    
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>


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