<?php 
require_once("include/bd.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
	<!-- Meta tags Obrigatórias -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	
	<title>Painel de Gerenciamento</title>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<a class="navbar-brand" href="home.php">MENU</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#conteudoNavbarSuportado" aria-controls="conteudoNavbarSuportado" aria-expanded="false" aria-label="Alterna navegação">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="Imagens.php">BANNER<span class="sr-only">(página atual)</span></a>
				</li>
				<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle font-weight-normal" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					CATALÓGO
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
					<a class="dropdown-item" href="Produtos.php">PRODUTOS</a>
					<a class="dropdown-item" href="Linhas.php">LINHAS</a>
					<a class="dropdown-item" href="Categoria.php">CATEGORIA</a>
					<a class="dropdown-item" href="CadastrarProduto.php">NOVO PRODUTO</a>
					</div>
				</li>
				<li class="nav-item active">
                        <a class="nav-link" href="Representantes.php">REPRESENTANTES<span class="sr-only"></span></a>
                </li>
			</ul>
			<form class="form-inline my-2 my-lg-0">
				<input class="form-control mr-sm-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Pesquisar</button>
			</form>
		</div>
	</nav>
</body>