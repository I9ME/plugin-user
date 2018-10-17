<style type="text/css">
	.card-cupom h3{
		margin: 6px 0;
	    font-size: 17px;
    	text-transform: uppercase;
	}
	.card-cupom h3 strong{
    	text-transform: capitalize;
	}
	.card-cupom{
		background: #eee;
    	border: 1px dashed #b7b7b7;
    	padding: 10px;
    	border-radius: 3px;
	}
	.card-cupom .status strong{
		color: #286319;
	}
	.card-cupom .status.cancel strong{
		color: #b92323;
	}
	.card-cupom .status.sale strong{
		color: #124e9a;
	}
	
	.card-cupom .cupom strong{
	
	}
	.card-cupom .promocao strong{
		font-size: 16px;
    	font-weight: 400;
	}
	.linkButton{
		position: relative;
		display: inline-block;
		padding: 4px 10px;
		margin: 0 0 0 15px;
		font-size: 13px;
		font-weight: bold;
	}
	.linkButton.cancel{
		border: 2px solid #b92323;
		color: #b92323;
	}
	.linkButton.cancel:hover{
		background: #b92323;
		color: #FFF;
	}
	.linkButton.sale{
		border: 2px solid #124e9a;
		color: #124e9a;
	}
	.linkButton.sale:hover{
		background: #124e9a;
		color: #FFF;
	}
	.actionResult{
		position: relative;
		display: none;
		align-items: center;
		justify-content: center;
		padding: 25px;
		font-size: 14px;
		text-align: center;
		margin: 0 0 20px 0;
	}
	.actionResult.success{
		background: #bbffa9;
		border: 1px solid #82d26d;
		color: #286319;
	}
	.actionResult.error{
		background: #ffd1d1;
		border: 1px solid #fd8787;
		color: #b92323;
	}
	.actions{
		margin: 20px 0 0 0;
	}
</style>

<?php
	if ( is_user_logged_in() ) {
	    global $current_user;
	    $user = wp_get_current_user();

	    if( isset($_GET['action']) && !empty($_GET['action']) ){
	    	$action_ = $_GET['action'];
	    	$id_ticket = $_GET['id'];

	    	update_post_meta($id_ticket, 'meta_box-status_ingresso', $action_);

	    	?>

	    	<style type="text/css">
	    		.id-<?php echo $id_ticket; ?>{
	    			background: #ffd1d1;
					border: 1px solid #fd8787;
	    		}
	    		.actionResult.success{
	    			display: block;
	    		}
	    	</style>

	    	<?php

	    	echo'<div class="actionResult success">A sua solicitação foi concluída com <strong> sucesso!</strong></div>';
	    	echo'<div class="actionResult error">Falha!</div>';
	    }
	
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
			<header class="Section-header">
				<h3 class="Section-header-title u-marginBottom--inter">
					Meus ingressos
				</h3>
			</header>
			<div class="Section-content">

	<?php 
		$current_user_id = get_current_user_id();


			$newsArgs = array( 'post_type' => 'ingresso', 'posts_per_page' => 50, 'author' => $current_user_id, 'orderby' => 'meta_value_num', 'order' => 'DESC');

			$newsLoop = new WP_Query( $newsArgs );
				
			if ( $newsLoop->have_posts() ):
	?>
		<ul>
			<?php while ( $newsLoop->have_posts() ) : $newsLoop->the_post();

				$id_post = get_the_ID();

				$meta_box_id_evento  = get_post_meta( $id_post, 'meta_box-id_evento', true );
				$meta_box_id_ingresso  = get_post_meta( $id_post, 'meta_box-id_ingresso', true );
				$meta_box_valor_ingresso  = get_post_meta( $id_post, 'meta_box-valor_ingresso', true );
				$meta_box_date_negociacao = get_post_meta( $id_post, 'meta_box-date_negociacao', true );
				$tipo_oferta_ingresso      = get_post_meta( $id_post, 'meta_box-tipo_oferta', true );
				$meta_box_vendedor      = get_post_meta( $id_post, 'meta_box-vendedor_ingresso', true );
				$meta_box_comprador      = get_post_meta( $id_post, 'meta_box-comprador_ingresso', true );
				$meta_box_status_negociacao   = get_post_meta( $id_post, 'meta_box-status_ingresso', true );

			?>

			<li class="card-cupom id-<?php echo $id_post; ?>">
				<?php
  					
					echo '<h3 class="promocao">Evento: <strong>' . get_the_title( $meta_box_id_evento )  . '</strong></h3>';
	   				echo '<h3 class="cupom">Ingresso: <strong>'   . get_the_title( $meta_box_id_ingresso ) . '</strong> </h3>';
  					echo '<p class="tipo">Tipo de Negociação: <strong>' . intepreta_labels('tipo_oferta', $tipo_oferta_ingresso) . '</strong> </p>';
  					echo '<p class="valor">Valor: <strong>R$ ' . $meta_box_valor_ingresso . '</strong> </p>';
  					echo '<p class="data">Data: <strong>' . $meta_box_date_negociacao . '</strong> </p>';
  					echo '<p class="status ' .  intepreta_labels('slug_status', $meta_box_status_negociacao) . '">Status: <strong>' . intepreta_labels('status_ingresso', $meta_box_status_negociacao) . '</strong></p>';
  					if( $meta_box_status_negociacao == 1 ) {

  						echo '<p class="actions"><a class="linkButton cancel" href="' . get_home_url() . '/dashboard/?action=4&id=' . $id_post . '">CANCELAR OFERTA</a><a class="linkButton sale" href="' . get_home_url() . '/dashboard/?action=2&id=' . $id_post . '">INGRESSO JÁ NEGOCIADO</a></p>';
						
					}
				?>
			</li><br/>

		<?php endwhile; ?>

	</ul>
	<?php else: ?>
		<div class="u-displayBlock u-positionRelative u-alignCenter u-marginHorizontal--vrt"><h3>Não encontramos nenhuma promoção.</h3></div>
	<?php 
		endif; wp_reset_postdata(); 
	?>
</div>
</div>
</div>
</div>

	<?php			      
	  	} else {
	      	echo '<h2>Você não está logado!</h2>';
	  	}				
	?>