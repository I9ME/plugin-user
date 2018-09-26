<?php
	if ( is_user_logged_in() ) {
	    global $current_user;
	    $user = wp_get_current_user();
	
	?>
	   <div class="SiteMain u-hasSideBar u-displayFlex u-flexDirectionColumn u-flexSwitchRow u-sizeFull">
	   	<div class="SiteMain-sideBar is-border left u-size6of24 u-marginBottom">
		  	<nav class="Navigation Navigation--sideBar">
		  		<ul class="Navigation-items u-displayFlex u-flexDirectionColumn">
		  			<li class="Navigation-items-item">
		  				<a class="NavigationLink u-paddingHorizontal--inter--half u-displayBlock" href="<?php echo get_home_url(); ?>/member-account/"><strong>Meus dados</strong></a>
		  			</li>
		  			<li class="Navigation-items-item">
		  				<a class="NavigationLink u-paddingHorizontal--inter--half u-displayBlock" href="<?php echo get_home_url(); ?>/dashboard/"><strong>Meus ingressos</strong></a>
		  			</li>
		  			<li class="Navigation-items-item">
		  				<a class="NavigationLink u-paddingHorizontal--inter--half u-displayBlock" href="<?php echo get_home_url(); ?>/wp-login.php?action=logout"><strong>Sair</strong></a>
		  			</li>
		  		</ul>
		  	</nav>
		</div>
		<div class="SiteMain-main right u-size18of24">
			<section class="Section Section--MeusDados u-displayFlex u-flexDirectionColumn">
		  				<header class="Section-header">
		  					<h3 class="Section-header-title u-marginBottom--inter">
		  						Meus Dados
		  					</h3>
		  				</header>
		  				<div class="Section-content">
			  				<!-- <figure class="Section--figure u-marginBottom--inter">
			  					<img src="<?php // echo esc_url( get_avatar_url( $user->ID ) ); ?>"  />		
						 	</figure> -->
						 	
						 	<ul class="Section-items">
							 	<li class="Section-items-item u-marginBottom--inter--half">
							 		<strong>Usuário: </strong><?php echo $current_user->user_login; ?>
								</li>
								<li class="Section-items-item u-marginBottom--inter--half">
							 		<strong>Nome: </strong><?php echo $current_user->nickname; ?>
								</li>
								<li class="Section-items-item u-marginBottom--inter--half">
							 		<strong>E-mail: </strong><?php echo $current_user->user_email; ?>
								</li>
								<li class="Section-items-item u-marginBottom--inter--half">
							 		<strong>Telefone: </strong><?php echo $current_user->user_phone; ?>
								</li>
								<li class="Section-items-item u-marginBottom--inter--half">
							 		<strong>CPF: </strong><?php echo $current_user->user_cpf; ?>
								</li>
								<li class="Section-items-item u-marginBottom--inter--half">
							 		<strong>Gênero: </strong><?php echo ucfirst( $current_user->user_gender ); ?>
								</li>
							</ul>
						</div>
						<div class="u-positionRelative u-alignLeft u-marginTop--inter">
							<a class="Button Buttom--mediumSize Button--border style1 hover u-paddingHorizontal--inter--half  u-paddingVertical--inter--px u-positionRelative u-displayInlineFlex  is-animating" href="<?php echo get_home_url(); ?>/member-edit/">ALTERAR MEUS DADOS</a>
						</div>

			</section>
		</div>

	<?php			      
	  	} else {
	      	echo '<h2>Você não está logado!</h2>';
	  	}				
	?>