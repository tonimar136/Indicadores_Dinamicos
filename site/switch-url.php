<?php
	if(isset($_GET['url'])){
		$url = $_GET['url'];
	}else{
		$url = null;
	}


	switch($url){
    	/*****************************HOME*************************************/
    	case '':
    	include 'parts/home.php';
    	break;
    	case 'home':
    	include 'parts/home.php';
    	break;
        /*****************************FORM*************************************/
        case 'formulario':
        include 'parts/form/formularios.php';
        break;
        case 'form-ins':
        include 'parts/form/formularios.php';
        break;
        case 'form-success':
        include 'parts/form/formularios.php';
        break;
        case 'form-fail':
        include 'parts/form/formularios.php';
        break;
        /**********DETALHE**********/
        case 'form-detail':
        include 'parts/form/form_detalhe.php';
        break;
        case 'form-detail-ins':
        include 'parts/form/form_detalhe.php';
        break;
        case 'form-detail-fail':
        include 'parts/form/form_detalhe.php';
        break;
        case 'form-detail-success':
        include 'parts/form/form_detalhe.php';
        break;
        /******************************ADMIN***********************************/
        case 'indicadores':
        include 'parts/indicadores/indicadores.php';
        break;
        case 'indicadores-ins':
        include 'parts/indicadores/indicadores.php';
        break;
        case 'indicadores-fail':
        include 'parts/indicadores/indicadores.php';
        break;
        case 'meus-indicadores':
        include 'parts/indicadores/meus_indicadores.php';
        break;
        case 'indicadores-form':
        include 'parts/indicadores/indicadores_formulario.php';
        break;
        case 'indicadores-form-edit':
        include 'parts/indicadores/indicadores_formulario_editar.php';
        break;
        case 'indicadores-form-edit-ins':
        include 'parts/indicadores/indicadores_formulario_editar.php';
        break;
        case 'indicadores-form-edit-fail':
        include 'parts/indicadores/indicadores_formulario_editar.php';
        break;

        /****GERAR INDICADORES****/
        case 'gerar-indicadores':
        include 'parts/indicadores/gerar_indicadores.php';
        break;

    	/******************************ADMIN***********************************/
        case 'admin':
        include 'parts/admin/admin.php';
        break;
        case 'admin-ins':
        include 'parts/admin/admin.php';
        break;
        case 'admin-success':
        include 'parts/admin/admin.php';
        break;
        case 'admin-fail':
        include 'parts/admin/admin.php';
        break;
        case 'group':
        include 'parts/admin/group.php';
        break;
        case 'group-ins':
        include 'parts/admin/group.php';
        break;
        case 'group-fail':
        include 'parts/admin/group.php';
        break;
        case 'group-success':
        include 'parts/admin/group.php';
        break;
        case 'alterpass':
        include 'parts/admin/alterar_senha.php';
        break;
        case 'sair':
    	include 'parts/admin/sair.php';
    	break;
    	
    	
    	
	}
	
?>