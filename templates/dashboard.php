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
		color: #29cb00;
	}
	.card-cupom .cupom strong{
		color: #9d0a0e;
    	background: #ffd800;
    	font-size: 15px;
    	padding: 2px 5px;
    	border: 1px dashed;
	}
	.card-cupom .promocao strong{
		font-size: 16px;
    	font-weight: 400;
	}
</style>

<?php
	if ( is_user_logged_in() ) {
	    global $current_user;
	    get_currentuserinfo();
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
		  				<a class="NavigationLink u-paddingHorizontal--inter--half u-displayBlock" href="<?php echo get_home_url(); ?>/dashboard/"><strong>Meus cupons</strong></a>
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
					Meus Cupons
				</h3>
			</header>
			<div class="Section-content">

	<?php 
		$current_user_id = get_current_user_id();

			$newsArgs = array( 'post_type' => 'coupons', 'posts_per_page' => 33, 'meta_key' => '_id_user',
			'meta_query' => array(
       			array(
		           'key' => '_id_user',
		           'value' => array($current_user_id),
		           'compare' => 'IN',
   				)
  			), 'orderby' => 'meta_value_num', 'order' => 'ASC');

			$newsLoop = new WP_Query( $newsArgs );
				
			if ( $newsLoop->have_posts() ):
	?>
		<ul>
			<?php while ( $newsLoop->have_posts() ) : $newsLoop->the_post();

				$id_post = get_the_ID();
				$_id_promo      = get_post_meta( $id_post, '_id_promo', true );
				$_id_user       = get_post_meta( $id_post, '_id_user', true );
				$_titulo_promo  = get_post_meta( $id_post, '_titulo_promo', true );
				$_status_coupon = get_post_meta( $id_post, '_status_coupon', true );
			?>

			<li class="card-cupom">
				<?php
  					
					echo '<h3 class="promocao">Promoção: <strong>' . $_titulo_promo  . '</strong></h3>';
	   				echo '<h3 class="cupom">Cupom: <strong>'   . get_the_title() . '</strong> </h3>';
  					echo '<h3 class="status">Status: <strong>' . $_status_coupon . '</strong> </h3>';
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