<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css">

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Alterar Senha</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a></li>
						<li class="breadcrumb-item">Alterar Senha</li>
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
		    <div class="card card-info">
            	<div class="card-header" style="background: #74b2d2">
            		<h3 class="card-title">Alterar Senha</h3>
            	</div>
            	<!-- /.card-header -->
            	<!-- form start -->
            	<form class="form-horizontal" action="controller/user-controller.php" method="POST" onsubmit="return validarSenha()">
            		<div class="card-body">
            			<div class="form-group row">
            				<label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
            				<div class="col-sm-10">
            				    <input type="hidden" class="form-control" value="<?php echo $_SESSION['UsuarioEmail'];?>" name="email">
            					<input type="text" class="form-control" value="<?php echo $_SESSION['UsuarioEmail'];?>" disabled>
            				</div>
            			</div>
            			<div class="form-group row">
            				<label for="inputPassword3" class="col-sm-2 col-form-label">Senha Atual</label>
            				<div class="col-sm-10">
            					<input type="password" class="form-control" placeholder="Password" name="senhaatual">
            				</div>
            			</div>
            			<div class="form-group row">
            				<label for="inputPassword3" class="col-sm-2 col-form-label">Nova Senha</label>
            				<div class="col-sm-10">
            					<input type="password" class="form-control" placeholder="Password" name="novasenha" id="novasenha">
            				</div>
            			</div>
            			<div class="form-group row">
            				<label for="inputPassword3" class="col-sm-2 col-form-label">Repetir Nova Senha</label>
            				<div class="col-sm-10">
            					<input type="password" class="form-control" placeholder="Password" name="rnovasenha" id="rnovasenha" oninput="validarSenha()">
            					<span id="mensagemErro" style="color: red;"></span>
            				</div>
            			</div>
            		</div>
            		<!-- /.card-body -->
            		<div class="card-footer">
            			<button type="submit" class="btn btn-info float-right" name="alterSenha">Salvar</button>
            		</div>
            		<!-- /.card-footer -->
            	</form>
            	
            	<script>
                    function validarSenha() {
                        var novaSenha = document.getElementById("novasenha").value;
                        var repetirSenha = document.getElementById("rnovasenha").value;
                        var mensagemErro = document.getElementById("mensagemErro");
                
                        if (novaSenha !== repetirSenha) {
                            mensagemErro.textContent = "As senhas não são iguais.";
                            return false;
                        } else {
                            mensagemErro.textContent = "";
                            return true;
                        }
                    }
                </script>
            </div>
            <!-- /.card -->
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

<?php
    if($_GET['url'] == 'alterpass-fail'){
        echo '
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "error",
                    title: "A senha atual não é a mesma digitada!",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        ';
    }
?>