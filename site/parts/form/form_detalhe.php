<?php
	require_once 'class/form.class.php';
	$formulario	= new Formulario();
    $id   = base64_decode($_GET['reg']);
	$form = $formulario->formDetalhe($id);
    $resp = $formulario->consultaTipoResposta();
    $perg = $formulario->listaPergunta($id);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<style type="text/css">
	#tableForm_filter{
		float: right;
	}
    .respostas{
        margin-bottom: 10px;
        width: 50%;
        float: left;
        margin-right: 10px;
    }
    .botao-remover {
        margin-bottom: 15px;
    }
    .btn-mod:hover {
      background-color: lightblue; /* Cor do fundo quando o mouse passa por cima */
    }
</style>

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Formulário</h1>
				</div>
				<!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Inicio</a> / Formulário Detalhe</li>
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
		<div class="container-fluid" style="width: 40% !important; display: flow-root; float: left;">
			<div class="card card-info">
            	<div class="card-header" style="background: #74b2d2">
            		<h3 class="card-title">Dados do Formulário</h3>
            	</div>

            	<div style="padding: 20px;">
					<div class="form-group">
						<label for="nome">Nome do Formulário: </label> <span><?=$form[0]['nome']?></span>
					</div>
					<div class="form-group">
						<label for="descricao">Descrição: </label> <span><?=$form[0]['descricao']?></span>
					</div>

                    <button type="button" class="btn" data-toggle="modal" data-target="#editDados" title="Editar" style="float: right; box-shadow: 0 14px 28px rgba(0,0,0,.25),0 10px 10px rgba(0,0,0,.22) !important">
                        <img src="images/edit.png" width="25px"><br>
                        <span style="font-size: 10px"><b>EDITAR</b></span>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="editDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editar Dados do Formulário</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                ...
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

        	   </div>
			</div>
		</div>
        <div class="container-fluid" style="width: 60% !important; display: flow-root;">
            <div class="card card-info">
                <div class="card-header" style="background: #74b2d2">
                    <h3 class="card-title">Cadastro de Perguntas</h3>
                </div>

                <form action="controller/form-controller.php" method="POST">
                    <input type="hidden" name="formulario" value="<?=$id?>">
                    <div style="padding: 20px;">
                        <div class="form-group">
                            <label for="pergunta">Pergunta: </label>
                            <input type="text" class="form-control" id="pergunta" name="pergunta" placeholder="Digite aqui a descrição de sua pergunta..." required="">
                        </div>
                        <div class="form-group">
                            <label for="seq">Sequência no Formulário: </label>
                            <input type="number" class="form-control" id="seq" name="seq" placeholder="Sequência que a pergunta vai aparecer no formulário..." required="">
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo de Resposta: </label>
                            <img src="images/interrogacao.png" alt="Aviso" style="width: 20px; cursor: pointer;" data-toggle="modal" data-target="#exemplo">
                            <!-- Modal -->
                            <div class="modal fade" id="exemplo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Exemplo dos Tipos de Campo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p style="text-align: justify;">Os campos que possuem <b>*</b> são campos onde você precisará cadastrar opções, e no momento de preenchimento do formulário tará que selecionar uma dessas opções cadastradas.</p>
                                            <br><br>
                                            Ex: Radio
                                            <br>
                                            <img src="images/radio_ex.png">
                                            <br>
                                            Ex: Select
                                            <br>
                                            <img src="images/select_ex.png">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <select class="form-control" id="tipo" name="tipo" onchange="habilitarBtn()" required="">
                                    <option value="">-- SELECIONE --</option>
                                    <?php
                                        $j = 0;
                                        $cont = count($resp);
                                        while($j < $cont){
                                            echo '<option value="'.$resp[$j]['id'].'">' . $resp[$j]['descricao'].'</option>';
                                            $j++;
                                        }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <button type="button" id="add" class="btn btn-primary btn-sm" onclick="adicionarInput()" style="display: none; margin-bottom: 10px"><b>ADICIONAR RESPOSTA</b></button>

                                <div id="inputsContainer"></div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-info" name="cadPergunta" id="cadPergunta" style="float: right; margin-bottom: 15px;" title="Salvar formulário">SALVAR</button>
                    </div>
                </form>
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
    							<th scope="col">DESCRIÇÃO</th>
    							<th scope="col">SEQUENCIA</th>
    							<th scope="col">STATUS</th>
    							<th scope="col">AÇÃO</th>
    						</tr>
    					</thead>
    					<tbody>
    						<?php
    							$j = 0;
    							$cont = count($perg);
    							while($j < $cont){
                                    $detail = $formulario->listaPerguntaResposta($perg[$j]['id']);
    								if($perg[$j]['status'] == 'Ativo'){
                                        $ativo   = 'selected';
                                        $inativo = '';
                                    }else{
                                        $ativo   = '';
                                        $inativo = 'selected';
                                    }

                                    echo '
    									<tr>
			    					    	<td>'.$perg[$j]['id'].'</td>
			    					    	<td>'.$perg[$j]['descricao'].'</td>
			    					    	<td>'.$perg[$j]['sequencia'].'</td>
                                            <td>'.$perg[$j]['status'].'</td>
			    					    	<td>
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editar-'.$perg[$j]['id'].'">Editar</button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="editar-'.$perg[$j]['id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Editar Pergunta Nº '.$perg[$j]['id'].'</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id" value="'.$perg[$j]['id'].'">
                                                                    <div style="text-align: center;"><h3>Pergunta</h3></div>
                                                                    <div class="form-group" style="width: 96%; float: left; margin-right: 4%">
                                                                        <label for="descricao">Descrição</label>
                                                                        <input type="text" class="form-control" id="descricao" name="descricao" value="'.$perg[$j]['descricao'].'" required="">
                                                                    </div>

                                                                    <div class="form-group" style="width: 46%; float: left; margin-right: 4%">
                                                                        <label for="sequencia">Sequência</label>
                                                                        <input type="number" class="form-control" id="sequencia" name="sequencia" value="'.$perg[$j]['sequencia'].'" required="">
                                                                    </div>

                                                                    <div class="form-group" style="width: 46%; float: left; margin-right: 4%">
                                                                        <label for="status">Status</label>
                                                                        <select class="form-control" id="status" name="status" value="" required="">
                                                                            <option '.$ativo.' value="A">Ativo</option>
                                                                            <option '.$inativo.' value="I">Inativo</option>
                                                                        </select>
                                                                    </div>

                                                                    <center><button id="editaPergunta" type="button" class="btn btn-info" >Salvar</button></center>
                                                                </div>
                                                            </form>
                                                            <div style="text-align: center; border-top: 1px solid #e9ecef"><h3>Respostas</h3></div>';

                                                            $i = 0;
                                                            $c = count($detail);
                                                            if($c != '0'){
                                                                while($i < $c){
                                                                    if($detail[$i]['status_resposta'] == 'Ativo'){
                                                                        $ativo   = 'selected';
                                                                        $inativo = '';
                                                                    }else{
                                                                        $ativo   = '';
                                                                        $inativo = 'selected';
                                                                    }

                                                                    echo '
                                                                        <div class="modal-body conteudo" style="padding: 0 1rem 0 1rem;">
                                                                            <form>
                                                                                <input type="hidden" name="id" value="'.$detail[$i]['id_resposta'].'">
                                                                                <div style="width: 27%; float: left; margin-right: 4%; padding: 0 1rem 0 1rem;">
                                                                                    <label for="resposta">Resposta</label>
                                                                                    <input type="text" class="form-control" id="resposta" name="resposta" value="'.$detail[$i]['resposta'].'" required="">
                                                                                </div>

                                                                                <div style="width: 27%; float: left; margin-right: 4%; padding: 0 1rem 0 1rem;">
                                                                                    <label for="sequencia">Sequência</label>
                                                                                    <input type="number" class="form-control" id="sequencia" name="sequencia" value="'.$detail[$i]['sequencia_resposta'].'" required="">
                                                                                </div>

                                                                                <div style="width: 27%; float: left;; padding: 0 1rem 0 1rem;">
                                                                                    <label for="status">Status</label>
                                                                                    <select class="form-control" id="status" name="status"required="">
                                                                                        <option '.$ativo.'>Ativo</option>
                                                                                        <option '.$inativo.'>Inativo</option>
                                                                                    <select>
                                                                                </div>
                                                                                <div style="float: left; align-items: end; justify-content: center; height: 70px; display: flex; margin-left: 2%;">
                                                                                    <button type="button" id="editaResposta'.$detail[$i]['id_resposta'].'" class="btn btn-mod" style="width: 20px; border: 1px solid #ddd; margin-right: 10px;" title="Editar">
                                                                                        <img src="images/edit_min.png" style="width: 20px; margin-left: -9px;">
                                                                                    </button>

                                                                                    <button type="button" id="deletaResposta'.$detail[$i]['id_resposta'].'"class="btn btn-mod" style="width: 20px; border: 1px solid #ddd;" title="Excluir">
                                                                                        <img src="images/delete_min.png" style="width: 20px; margin-left: -9px;">
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>

                                                                        <script>
                                                                            //EDITA RESPOSTA---------------------------------------------------------------------------
                                                                            $("#editaResposta'.$detail[$i]['id_resposta'].'").click(function() {
                                                                                var form = $(this).closest("form"); // Encontra o formulário pai do botão clicado
                                                                                var formData = form.serialize(); // Serializa os dados do formulário
                                                                                formData += "&form-edit=form-edit";

                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "controller/form-controller.php",
                                                                                    data: formData,
                                                                                    success: function(response) {
                                                                                        console.log(response);
                                                                                        Swal.fire({
                                                                                            position: "top-end",
                                                                                            icon: "success",
                                                                                            title: "Dados alterados com sucesso!",
                                                                                            showConfirmButton: false,
                                                                                            timer: 1500
                                                                                        });

                                                                                    },
                                                                                    error: function(error) {
                                                                                        console.error("Erro:", error);
                                                                                        Swal.fire({
                                                                                            position: "top-end",
                                                                                            icon: "error",
                                                                                            title: "Ocorreu um erro ao executar a ação!",
                                                                                            showConfirmButton: false,
                                                                                            timer: 1500
                                                                                        });
                                                                                    }
                                                                                });
                                                                            });

                                                                            //DELETA RESPOSTA---------------------------------------------------------------------------
                                                                            $("#deletaResposta'.$detail[$i]['id_resposta'].'").click(function() {
                                                                                var form = $(this).closest("form"); // Encontra o formulário pai do botão clicado
                                                                                var formData = form.serialize(); // Serializa os dados do formulário
                                                                                formData += "&del-resp=del-resp";
console.log(formData);
                                                                                $.ajax({
                                                                                    type: "POST",
                                                                                    url: "controller/form-controller.php",
                                                                                    data: formData,
                                                                                    success: function(response) {
                                                                                        console.log(response);
                                                                                        Swal.fire({
                                                                                            position: "top-end",
                                                                                            icon: "success",
                                                                                            title: "Dados alterados com sucesso!",
                                                                                            showConfirmButton: false,
                                                                                            timer: 1500
                                                                                        });
                                                                                        
                                                                                    },
                                                                                    error: function(error) {
                                                                                        console.error("Erro:", error);
                                                                                        Swal.fire({
                                                                                            position: "top-end",
                                                                                            icon: "error",
                                                                                            title: "Ocorreu um erro ao executar a ação!",
                                                                                            showConfirmButton: false,
                                                                                            timer: 1500
                                                                                        });
                                                                                    }
                                                                                });
                                                                            });
                                                                        </script>
                                                                    ';
                                                                    $i++;
                                                                }
                                                            }else{
                                                                echo '<center>O tipo de resposta é '.$perg[$j]['tipo_resposta'].'!</center>';
                                                            }
                                                            
                                                            echo '<div class="modal-footer" style="float: left; width: 100%; margin-top: 10px;">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                                </div>                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#excl'.$perg[$j]['id'].'">Excluir</button>
                                                <!-- Modal -->
                                                <div class="modal fade" id="excl'.$perg[$j]['id'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Excluir Registro</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <center>Deseja realmente excluir a pergunta e as respostas vinculadas a pergunta <b>Nº '.$perg[$j]['id'].'</b>?</center>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form>
                                                                    <input type="hidden" name="id" value="'.$perg[$j]['id'].'">
                                                                    <button type="button" id="delPergunta" class="btn btn-warning">Excluir</button>
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
	if($_GET['url'] == 'form-detail-ins'){
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

    if($_GET['url'] == 'form-detail-fail'){
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

    if($_GET['url'] == 'form-detail-success'){
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



<script>
    let contador = 0;

    function adicionarInput() {
      contador++;

      const novaDiv = document.createElement('div');
      novaDiv.style.display = 'flow-root';

      const novoInput = document.createElement('input');
      novoInput.type = 'text';
      novoInput.name = `resposta_${contador}`;
      novoInput.classList.add('form-control');
      novoInput.classList.add('respostas');

      const botaoRemover = document.createElement('button');
      botaoRemover.textContent = 'Remover';
      botaoRemover.classList.add('btn');
      botaoRemover.classList.add('btn-warning');
      botaoRemover.classList.add('btn-sm');
      botaoRemover.classList.add('btn-remover');
      botaoRemover.onclick = function() {
        novaDiv.remove(); // Remove a div que encapsula o input e o botão
        //contador--; // Reduz o contador quando o input é removido
      };

      novaDiv.appendChild(novoInput);
      novaDiv.appendChild(botaoRemover);

      const container = document.getElementById('inputsContainer');
      container.appendChild(novaDiv);
    }


    function habilitarBtn() {
        var select = document.getElementById("tipo");
        var textarea = document.getElementById("add");

        if(select.value === "4" || select.value === "5"){
        textarea.style.display = "block"; // Exibe o campo textarea
        textarea.disabled = false; // Habilita o campo textarea
        }else{
            textarea.style.display = "none"; // Esconde o campo textarea
            textarea.disabled = true; // Desabilita o campo textarea
        }
    }



    $(document).ready(function(){
        //EDITA PERGUNTA---------------------------------------------------------------------------
        $('#editaPergunta').click(function() {
            var form = $(this).closest('form'); // Encontra o formulário pai do botão clicado
            var formData = form.serialize(); // Serializa os dados do formulário
            formData += '&edita-pergunta=edita-pergunta';

            $.ajax({
                type: 'POST',
                url: 'controller/form-controller.php',
                data: formData,
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Dados alterados com sucesso!",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    setTimeout(function() {
                        location.reload();
                    }, 1490);
                },
                error: function(error) {
                    console.error('Erro:', error);
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Ocorreu um erro ao executar a ação!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });



        //DELETA PERGUNTA---------------------------------------------------------------------------
        $('#delPergunta').click(function() {
            var form = $(this).closest('form'); // Encontra o formulário pai do botão clicado
            var formData = form.serialize(); // Serializa os dados do formulário
            formData += '&form-del=form-del';

            $.ajax({
                type: 'POST',
                url: 'controller/form-controller.php',
                data: formData,
                success: function(response) {
                    console.log(response);
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Dados excluídos com sucesso!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(function() {
                        window.location.href = 'index.php?url=form-detail&reg=<?=$_GET['reg']?>';
                    }, 1490);
                },
                error: function(error) {
                    console.error('Erro:', error);
                    Swal.fire({
                        position: "top-end",
                        icon: "error",
                        title: "Ocorreu um erro ao executar a ação!",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

    });
</script>
