<?php
	require_once 'class/indicadores.class.php';
	$ind	= new Indicadores();
	$form	= $ind->consultaIndicadores();
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
					<h1 class="m-0">Indicadores</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a> / Indicadores</li>
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
            		<h3 class="card-title">Indicadores</h3>
            	</div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Criador</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $g = 0;
                                $count = count($form);
                                while($g < $count){
                                    $id = base64_encode($form[$g]['id']);
                                    ?>
                                        <tr>
                                            <td><?=$form[$g]['id']?></td>
                                            <td><?=$form[$g]['nome']?></td>
                                            <td><?=$form[$g]['descricao']?></td>
                                            <td><?=$form[$g]['criador']?></td>
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


<?php
	if($_GET['url'] == 'indicadores-ins'){
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

    if($_GET['url'] == 'indicadores-fail'){
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