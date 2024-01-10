<?php
    // Inicia a sessão
    session_start();
    
    // Finaliza a sessão atual
    session_unset();
    session_destroy();
    
    // Redireciona para outra página
    header("Location: ../../index.php?id=out");
    exit;
?>
