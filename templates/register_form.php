<style type="text/css" media="screen">
    .form-register{
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #ebebeb;
        padding: 25px;
        border-radius: 6px;
        box-shadow: 0 0 1px 0 #d3d3d3;
    }
    .form-register p{
        display: flex;
        width: 100%;
        flex-direction: column;
        margin-bottom: 30px;
    }
    .form-register p label{
        margin-bottom: 5px;
    }

    .form-register p input{
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    .form-register .msg{
        font-size: 14px;
        background: #e8e8e8;
        padding: 10px;
        border: 1px solid #f4f4f4;
        border-radius: 6px;
        color: #969696;
    }
    

    .form-register .signup-submit {
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
    .form-register .signup-submit:hover{
        border: 2px solid #009045;
        background: transparent;
        color: #009045;
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

    <div id="register-form" class="widecolumn form-register">
        <?php if ( $attributes['show_title'] ) : ?>
            <h3><?php _e( 'Register', 'personalize-login' ); ?></h3>
        <?php endif; ?>
        <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
            <?php foreach ( $attributes['errors'] as $error ) : ?>
                <p class="alert-error"><?php echo $error; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
            <p class="form-row">
                <label for="first_name"><?php _e( 'Nome:', 'personalize-login' ); ?><strong>*</strong></label>
                <input type="text" name="first_name" id="first-name" required="">
            </p>

            <p class="form-row">
                <label for="email"><?php _e( 'E-mail:', 'personalize-login' ); ?><strong>*</strong></label>
                <input type="text" name="email" id="email" required="required">
            </p>

            <p class="form-row">
                <label for="user_cpf"><?php _e( 'CPF', 'personalize-login' ); ?></label>
                <input type="text" name="user_cpf" id="user_cpf">
            </p>
     
            <p class="form-row">
                <label for="user_phone"><?php _e( 'Telefone', 'personalize-login' ); ?><strong>*</strong></label>
                <input type="tel" name="user_phone" id="user_phone" required="required">
            </p>
            
            <p class="form-row">
                <label for="user_gender"><?php _e( 'Gênero', 'personalize-login' ); ?><strong>*</strong></label>
                <select class="Form-input Form-input--select Form-select" name="user_gender" id="user_gender" required="required">
                    <option>Escolha</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                </select>
            </p>

            <p class="form-row msg">
                <?php _e( 'Sua senha será gerada automaticamente e enviada para seu endereço de e-mail.', 'personalize-login' ); ?>
            </p>


 
            <input type="submit" name="submit" class="register-button signup-submit" value="<?php _e( 'Registrar', 'personalize-login' ); ?>"/>    
        </form>
    </div>