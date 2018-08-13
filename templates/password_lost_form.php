<div id="password-lost-form" class="widecolumn">
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Esqueceu sua senha?', 'personalize-login' ); ?></h3>
    <?php endif; ?>
 
    <p>
        <?php
            _e(
                "Digite seu endereço de e-mail e enviaremos um link para você escolher uma nova senha.",
                'personalize_login'
            );
        ?>
    </p>
    <?php if ( $attributes['lost_password_sent'] ) : ?>
        <p class="login-info">
            <?php _e( 'Verifique no seu email um link para redefinir sua senha.', 'personalize-login' ); ?>
        </p>
    <?php endif; ?>
    
    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
        <p class="form-row">
            <label for="user_login"><?php _e( 'Email', 'personalize-login' ); ?>
            <input type="text" name="user_login" id="user_login">
        </p>
 
        <p class="lostpassword-submit">
            <input type="submit" name="submit" class="lostpassword-button"
                   value="<?php _e( 'Reset Password', 'personalize-login' ); ?>"/>
        </p>
    </form>
</div>