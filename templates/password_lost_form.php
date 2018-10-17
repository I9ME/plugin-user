<div id="password-lost-form" class="widecolumn">
  
        <h3>Esqueceu sua senha?</h3>
 
    <p>
        <?php
            _e(
                "Digite seu endereço de e-mail.",
                'personalize_login'
            );
        ?>
    </p>
        <p class="login-info">
           Verifique no seu email um link para redefinir sua senha.
        </p>

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