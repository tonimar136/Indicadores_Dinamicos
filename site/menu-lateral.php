<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<div style="background: #dee2e6">
		<center>
			<img src="images/logo.png" alt="logo" width="85px" style="margin: 10px">
		</center>
	</div>
	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="info" style="width: 100%">
				<a href="#" class="d-block">
					<center>
						<b>Bem-vindo(a)</b><br>
						<?php
							echo $_SESSION['UserNome'] . '<br>';
							if(isset($_SESSION['UserFilialLogadaNome'])){
								echo '<span style="font-size: 10px">'. $_SESSION['UserFilialLogadaNome'].'</span>';
								echo '<a href="index.php?url=alter-filial" class="btn btn-success btn-sm">Trocar Filial</a>';
							}
						?>
					</center>
				</a>
			</div>
		</div>

		<!-- SidebarSearch Form 
			<div class="form-inline">
			  <div class="input-group" data-widget="sidebar-search">
			    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
			    <div class="input-group-append">
			      <button class="btn btn-sidebar">
			        <i class="fas fa-search fa-fw"></i>
			      </button>
			    </div>
			  </div>
			</div>
			-->
		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<?php
					if($_SESSION['UserGroup'] == '1' || $_SESSION['UserGroup'] == '2'){
						?>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="nav-icon fas fa-copy"></i>
									<p>
										Cadastros
										<i class="fas fa-angle-left right"></i>
									</p>
								</a>
								<ul class="nav nav-treeview" style="padding-left: 20px">
									<li class="nav-item">
										<a href="index.php?url=formulario" class="nav-link">
											<i class="nav-icon fas fa-file"></i>
											<p>Formulários</p>
										</a>
									</li>
								</ul>
							</li>
					<?php }
				?>

				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-edit"></i>
						<p>
							Indicadores
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview" style="padding-left: 20px">
						<li class="nav-item">
							<a href="index.php?url=indicadores" class="nav-link">
								<i class="nav-icon fas fa-file"></i>
								<p>Formulários</p>
							</a>
						</li>
						
						<li class="nav-item">
							<a href="index.php?url=meus-indicadores" class="nav-link">
								<i class="nav-icon fas fa-file"></i>
								<p>Meus Formulários</p>
							</a>
						</li>
					</ul>
				</li>







				
				<!--<li class="nav-item">
					<a href="index.php?url=pesquisa" class="nav-link">
						<i class="nav-icon fas fa-edit"></i>
						<p>Pesquisa de Satisfação</p>
					</a>
				</li>
				
				<li class="nav-item">
					<a href="index.php?url=visitas" class="nav-link">
						<i class="nav-icon fas fa-file"></i>
						<p>Relatórios de Visitas</p>
					</a>
				</li>
				-->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-user"></i>
						<p>
							Administração
							<i class="fas fa-angle-left right"></i>
							<!--<span class="badge badge-info right">2</span>-->
						</p>
					</a>

					<ul class="nav nav-treeview" style="padding-left: 20px">
						<?php
							if($_SESSION['UserGroup'] == '1'){
								?>
									<li class="nav-item">
										<a href="index.php?url=filial" class="nav-link">
											<i class="fas fa-industry nav-icon"></i>
											<p>Filial</p>
										</a>
									</li>

									<li class="nav-item">
										<a href="index.php?url=group" class="nav-link">
											<i class="fas fa-users nav-icon"></i>
											<p>Grupos</p>
										</a>
									</li>

									<li class="nav-item">
										<a href="index.php?url=admin" class="nav-link">
											<i class="fas fa-user nav-icon"></i>
											<p>Usuários</p>
										</a>
									</li>
								<?php
							}
						?>

						<li class="nav-item">
							<a href="index.php?url=alterpass" class="nav-link">
								<i class="fas fa-key nav-icon"></i>
								<p>Alterar Senha</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="parts/admin/sair.php" class="nav-link">
								<i class="fa fa-window-close nav-icon"></i>
								<p>Sair</p>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>