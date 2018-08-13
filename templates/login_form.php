<style type="text/css" media="screen">
    .form-login{

        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #ebebeb;
        padding: 25px;
        border-radius: 6px;
        box-shadow: 0 0 1px 0 #d3d3d3;
    }
    .form-login form{
        width: 100%;
    }
    .form-login p{
        display: flex;
        width: 100%;
        flex-direction: column;
        margin-bottom: 30px;
    }
    .form-login p label{
        margin-bottom: 5px;
    }

    .form-login p input{
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    

    .form-login .login-submit {
        width: 100%;
        background: #009045;
        color: #fff;
        border: 0;
        width: 100%;
        height: 60px;
        border-radius: 6px;
        text-transform: uppercase;
        transition: all .5s;
    }
    .form-login .login-submit:hover{
        border: 2px solid #009045;
        background: transparent;
        color: #009045;
    }
    .form-login .login-info{
        font-size: 18px;
        display: flex;
        align-items: center;
        background: #0fae5b;
        padding: 10px;
        height: 66px;
        color: #fff;
        border-radius: 5px;
        border: 2px solid #0ba254;
    }
    .form-login .login--infoPass{
        font-size: 15px;
        text-align: center;
        height: 62px;
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

<div class="login-form-container form-login">
    <!-- Show errors if there are any -->
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p class="alert-error">
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if ( $attributes['registered'] ) : ?>
        <p class="login-info">
            <?php
                printf(
                    __( 'Você se registrou com sucesso em  <strong>%s</strong>.', 'personalize-login' ),
                    get_bloginfo( 'name' )
                );
            ?>
        </p>
        <p class="login-info login--infoPass">
            <?php 
                printf(
                    __( 'Enviamos sua senha por e-mail para o endereço de e-mail que você digitou.', 'personalize-login' ),
                    get_bloginfo( 'name' )
                );
            ?>
        </p>
    <?php endif; ?>
    <?php if ( $attributes['password_updated'] ) : ?>
        <p class="login-info">
            <?php _e( 'Sua senha foi mudada. Você pode entrar agora.', 'personalize-login' ); ?>
        </p>
    <?php endif; ?> 
    <form method="post" action="<?php echo wp_login_url(); ?>">
        <p class="login-username">
            <label for="user_login"><?php _e( 'Digite seu e-mail', 'personalize-login' ); ?></label>
            <input type="text" name="log" id="user_login">
        </p>
        <p class="login-password">
            <label for="user_pass"><?php _e( 'Senha', 'personalize-login' ); ?></label>
            <input type="password" name="pwd" id="user_pass">
        </p>
         
            <input class="login-submit" type="submit" value="<?php _e( 'Entrar', 'personalize-login' ); ?>">
        
    </form>
</div>