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
</style>>

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
                                            <td><?=$meus[$g]['formulario']?></td>
                                            <td><?=$meus[$g]['data']?></td>
                                            <td><button type="button" class="btn btn-info" onclick="window.location.href='index.php?url=indicadores-form&id=<?=$id?>'">Acessar</button></td>
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