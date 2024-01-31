<?php
	require_once '../class/user.class.php';
	$usr    = new Usuario();
	$filial = $usr->consultaFilialLogada($_POST['filialSelecionada']);

	session_start();

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filialSelecionada'])) {
	    // Lógica para validar a filialSelecionada (pode incluir mais validações)
	    $filialSelecionada = $_POST['filialSelecionada'];

	    // Define a variável de sessão com o valor selecionado
	    $_SESSION['UserFilialLogada'] = $filialSelecionada;
	    $_SESSION['UserFilialLogadaNome'] = $filial[0]['descricao'];

	    // Pode realizar outras ações conforme necessário

	    echo "success";
	} else {
	    echo "error";
	}
?>
