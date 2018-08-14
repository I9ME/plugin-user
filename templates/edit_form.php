<?php
	if ( is_user_logged_in() ) {


	    global $current_user;
	    get_currentuserinfo();
	    $user = wp_get_current_user();

	?>

	<style type="text/css" media="screen">
    

    .form-register p input{
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    .Form-row .msg{
        font-size: 14px;
        background: #e8e8e8;
        padding: 10px;
        border: 1px solid #f4f4f4;
        border-radius: 6px;
        color: #969696;
        display: none;
    }
    

    .alert-error{
        font-size: 15px;
        color: #ffffff;
        font-weight: 700;
        border: 1px solid #ea0000;
        padding: 10px;
        border-radius: 6px;
        background: #da0000;
    }
    
</style>

	   <div class="SiteMain SiteMain--formRegister u-hasSideBar u-displayFlex u-flexDirectionColumn u-flexSwitchRow u-sizeFull">
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
						 <?php 

						  if (  $_SERVER['REQUEST_METHOD'] != 'POST' ) {

						   ?>
						 	<form class="Form Form--style1 u-sizeFull" id="signupform" action="<?php echo get_home_url() ?>/member-edit/" method="post">
						 		<div class="Form-row u-displayFlex u-flexDirectionColumn u-flexSwitchRow">
						            <p class="Form-coll u-positionRelative u-marginBottom--inter--half u-size12of24">
						                <label class="Form-label" for="nickname"><?php _e( 'Nome:', 'personalize-login' ); ?><strong>*</strong></label>
						                <input type="text" class="Form-input Form-input--text u-sizeFull" name="nickname" id="nickname" value="<?php echo $current_user->nickname; ?>" required="">
						            </p>
						           <p class="Form-coll u-positionRelative u-marginBottom--inter--half u-size12of24">
						                <label class="Form-label" for="email"><?php _e( 'E-mail:', 'personalize-login' ); ?><strong>*</strong></label>
						                <input type="text" class="Form-input Form-input--text u-sizeFull" name="user_email" id="user_email" value="<?php echo $current_user->user_email; ?>" required="required">
						            </p>
						        </div>
						        <div class="Form-row u-displayFlex u-flexDirectionColumn u-flexSwitchRow">
						            <p class="Form-coll u-positionRelative u-marginBottom--inter--half u-size12of24">
						                <label class="Form-label" for="user_cpf"><?php _e( 'CPF', 'personalize-login' ); ?><strong>*</strong></label>
						                <input type="text" class="Form-input Form-input--text u-sizeFull" name="user_cpf" id="user_cpf"  value="<?php echo $current_user->user_cpf; ?>" required="required">
						            </p>
						       				     
						            <p class="Form-coll u-positionRelative u-marginBottom--inter--half u-size12of24">
						                <label class="Form-label" for="user_phone"><?php _e( 'Telefone', 'personalize-login' ); ?><strong>*</strong></label>
						                <input type="tel" class="Form-input Form-input--text u-sizeFull" name="user_phone" id="user_phone" value="<?php echo $current_user->user_phone; ?>" required="required">
						            </p>
						        </div>
					            <div class="Form-row u-displayFlex u-flexDirectionColumn u-flexSwitchRow">
						            <p class="Form-coll u-positionRelative u-marginBottom--inter--half u-size12of24">
						                <label class="Form-label" for="user_gender"><?php _e( 'Gênero', 'personalize-login' ); ?><strong>*</strong></label>
						                <select class="Form-input Form-input--select  Form-input--text Form-select u-sizeFull" name="user_gender" id="user_gender" required="required">
						                    <option>Escolha</option>
						                    <option value="masculino" <?php if( $current_user->user_gender == 'masculino' ) { echo 'selected'; } ?>>Masculino</option>
						                    <option value="feminino" <?php if( $current_user->user_gender == 'feminino' ) { echo 'selected'; } ?>>Feminino</option>
						                </select>
						            </p>
						        </div>
						        <div class="Form-row">
						            <p class="form-row msg">
						                <?php _e( 'Seus dados foram alterados com sucesso!' ); ?>
						            </p>
						        </div>
						        <div class="Form-row"> 
						        	 <input name="action" type="hidden" id="action" value="update-user" />
					           		<input type="submit" name="submit" class="Button Buttom--mediumSize Button--border style1 hover u-paddingHorizontal--inter--half  u-paddingVertical--inter--px u-positionRelative u-displayInlineFlex  is-animating" value="<?php _e( 'Alterar', 'personalize-login' ); ?>"/>
					           	</div> 
					        </form>

					        

								<?php

							/* Get user info. */
							//global $current_user, $wp_roles;
							//get_currentuserinfo(); //deprecated since 3.1

							/* Load the registration file. */
							//require_once( ABSPATH . WPINC . '/registration.php' ); //deprecated since 3.1
							//$error = array();    
							/* If profile was saved, update profile. */
							} elseif ( !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

							   

							    if ( !empty( $_POST['first_name'] ) )
							        update_user_meta( $current_user->ID, 'first_name', esc_attr( $_POST['first_name'] ) );
							    if ( !empty( $_POST['last_name'] ) )
							        update_user_meta($current_user->ID, 'last_name', esc_attr( $_POST['last_name'] ) );
							    if ( !empty( $_POST['user_email'] ) )
							        update_user_meta( $current_user->ID, 'user_email', esc_attr( $_POST['user_email'] ) );
							    if ( !empty( $_POST['nickname'] ) )
							        update_user_meta( $current_user->ID, 'nickname', esc_attr( $_POST['nickname'] ) );
							    if ( !empty( $_POST['user_cpf'] ) )
							        update_user_meta( $current_user->ID, 'user_cpf', esc_attr( $_POST['user_cpf'] ) );
							    if ( !empty( $_POST['user_phone'] ) )
							        update_user_meta( $current_user->ID, 'user_phone', esc_attr( $_POST['user_phone'] ) );
							    if ( !empty( $_POST['user_gender'] ) )
							        update_user_meta( $current_user->ID, 'user_gender', esc_attr( $_POST['user_gender'] ) );
							    ?>


							  
							<div class="Success u-alignCenter u-marginHorizontal">
							<h3>
								Seus dados foram alterados com sucesso!
							</h3>
							</div>
								<?php 
							    
							    do_action('edit_user_profile_update', $current_user->ID);

							 		//wp_redirect( get_home_url() );
							    
							      //  exit;


							       

							} ?> 

						</div>

			</section>
		</div>
<?php
			      
	  	} else {
	      	echo '<h2>Você não está logado!</h2>';
	  	}				
	?>