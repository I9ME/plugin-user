<style type="text/css">
	.user-info{
		display: flex;
    	align-items: end;	
	}
	.user-info img{
	    float: left;
	    margin-right: 3%;	
	}
</style>
<?php

	if ( is_user_logged_in() ) {
	    global $current_user;
	    get_currentuserinfo();
	    $user = wp_get_current_user();
	
	   
  		echo '<div class="user-info">'. 
  				'<img src=' .  esc_url( get_avatar_url( $user->ID ) ) .' />		
			 	<div>
			 		<strong>Usuário: </strong>' . $current_user->user_login.'<br/><br/>
	    	 		<strong>Nome: </strong>' . $current_user->nickname. '<br/>
				</div>
			</div><br/>';
				
				      
  	} else {
      	echo '<div class="user-info">
				<a href="" "Minha conta">Login
		
			<div class="user__img">'. 
				get_avatar( get_the_author_meta(), '96' ) .
	 		'</div></a>'.
	'</div>';
  	}
				
			
     			
	
?>