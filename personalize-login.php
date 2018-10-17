<?php
/**
 * Plugin Name:       Personalize Login
 * Description:       A plugin that replaces the WordPress login flow with a custom page.
 * Version:           1.0.0
 * Author:            Bruno Kiedis
 * License:           GPL-2.0+
 * Text Domain:       personalize-login
 */
 
class Personalize_Login_Plugin {
 
    /**
     * Initializes the plugin.
     *
     * To keep the initialization fast, only add filter and action
     * hooks in the constructor.
     */
    public function __construct() {
     	
     	add_shortcode( 'custom-login-form', array( $this, 'render_login_form' ) );
     	add_shortcode( 'custom-register-form', array( $this, 'render_register_form' ) );
     	add_shortcode( 'custom-edit-form', array( $this, 'render_edit_form' ) );
		add_shortcode( 'custom-password-lost-form', array( $this, 'render_password_lost_form' ) );
		add_shortcode( 'custom-password-reset-form', array( $this, 'render_password_reset_form' ) );
     	add_shortcode( 'account-info', array( $this, 'render_account_info_form' ) );
     	add_shortcode( 'dashboard', array( $this, 'dashboard_info' ) );

     	add_action( 'login_form_register', array( $this, 'do_register_user' ) );
     	add_action( 'login_form_register', array( $this, 'redirect_to_custom_register' ) );
     	add_action( 'login_form_edit', array( $this, 'do_edit_user' ) );
     	add_action( 'login_form_edit', array( $this, 'redirect_to_custom_edit' ) );
     	add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
		add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
     	add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
     	add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

     	add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
     	add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
     	add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );

    }

    public function dashboard_info( $attributes, $content = null ) {
		// Error messages
		$errors = array();
		if ( isset( $_REQUEST['error'] ) ) {
			$error_codes = explode( ',', $_REQUEST['error'] );
			foreach ( $error_codes as $code ) {
				$errors[] = $this->get_error_message( $code );
			}
		}
		//$attributes['errors'] = $errors;
		// Render the login form using an external template
		return $this->get_template_html( 'dashboard', $attributes );
	}


    /**
	 * A shortcode for rendering the login form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 * @since 1.0
	 */
	public function render_account_info_form( $attributes, $content = null ) {
		// Error messages
		$errors = array();
		if ( isset( $_REQUEST['error'] ) ) {
			$error_codes = explode( ',', $_REQUEST['error'] );
			foreach ( $error_codes as $code ) {
				$errors[] = $this->get_error_message( $code );
			}
		}
		//$attributes['errors'] = $errors;
		// Render the login form using an external template
		return $this->get_template_html( 'account-info-form', $attributes );
	}


	
    /**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {
	    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	        $rp_key = $_REQUEST['rp_key'];
	        $rp_login = $_REQUEST['rp_login'];
	 
	        $user = check_password_reset_key( $rp_key, $rp_login );
	 
	        if ( ! $user || is_wp_error( $user ) ) {
	            if ( $user && $user->get_error_code() === 'expired_key' ) {
	                wp_redirect( home_url( 'member-login?login=expiredkey' ) );
	            } else {
	                wp_redirect( home_url( 'member-login?login=invalidkey' ) );
	            }
	            exit;
	        }
	 
	        if ( isset( $_POST['pass1'] ) ) {
	            if ( $_POST['pass1'] != $_POST['pass2'] ) {
	                // Passwords don't match
	                $redirect_url = home_url( 'member-password-reset' );
	 
	                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
	                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
	                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
	 
	                wp_redirect( $redirect_url );
	                exit;
	            }
	 
	            if ( empty( $_POST['pass1'] ) ) {
	                // Password is empty
	                $redirect_url = home_url( 'member-password-reset' );
	 
	                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
	                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
	                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
	 
	                wp_redirect( $redirect_url );
	                exit;
	            }
	 
	            // Parameter checks OK, reset password
	            reset_password( $user, $_POST['pass1'] );
	            wp_redirect( home_url( 'member-login?password=changed' ) );
	        } else {
	            echo "Invalid request.";
	        }
	 
	        exit;
	    }
	}

    /**
	 * A shortcode for rendering the form used to reset a user's password.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_reset_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	 
	    if ( is_user_logged_in() ) {
	        return __( 'You are already signed in.', 'personalize-login' );
	    } else {
	        if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
	            $attributes['login'] = $_REQUEST['login'];
	            $attributes['key'] = $_REQUEST['key'];
	 
	            // Error messages
	            $errors = array();
	            if ( isset( $_REQUEST['error'] ) ) {
	                $error_codes = explode( ',', $_REQUEST['error'] );
	 
	                foreach ( $error_codes as $code ) {
	                    $errors []= $this->get_error_message( $code );
	                }
	            }
	            $attributes['errors'] = $errors;
	 
	            return $this->get_template_html( 'password_reset_form', $attributes );
	        } else {
	            return __( 'Invalid password reset link.', 'personalize-login' );
	        }
	    }
	}

    /**
	 * Redirects to the custom password reset page, or the login page
	 * if there are errors.
	 */
	public function redirect_to_custom_password_reset() {
	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	        // Verify key / login combo
	        $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
	        if ( ! $user || is_wp_error( $user ) ) {
	            if ( $user && $user->get_error_code() === 'expired_key' ) {
	                wp_redirect( home_url( 'member-login?login=expiredkey' ) );
	            } else {
	                wp_redirect( home_url( 'member-login?login=invalidkey' ) );
	            }
	            exit;
	        }
	 
	        $redirect_url = home_url( 'member-password-reset' );
	        $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
	        $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
	 
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}

    /**
	 * Returns the message body for the password reset mail.
	 * Called through the retrieve_password_message filter.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 *
	 * @return string   The mail message to send.
	 */
	public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	    // Create new message
	    $msg  = __( 'Olá!', 'personalize-login' ) . "\r\n\r\n";
	    $msg .= sprintf( __( 'Você nos pediu para redefinir sua senha para sua conta usando o endereço de e-mail %s.', 'personalize-login' ), $user_login ) . "\r\n\r\n";
	    $msg .= __( "Se isso foi um erro, ou você não pediu uma redefinição de senha, apenas ignore este e-mail e nada acontecerá.", 'personalize-login' ) . "\r\n\r\n";
	    $msg .= __( 'Para redefinir sua senha, visite o seguinte endereço:', 'personalize-login' ) . "\r\n\r\n";
	    $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
	    $msg .= __( 'Obrigado!', 'personalize-login' ) . "\r\n";
	 
	    return $msg;
	}

    /**
	 * Initiates password reset.
	 */
	public function do_password_lost() {
	    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	        $errors = retrieve_password();
	        if ( is_wp_error( $errors ) ) {
	            // Errors found
	            $redirect_url = home_url( 'member-password-lost' );
	            $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
	        } else {
	            // Email sent
	            $redirect_url = home_url( 'member-login' );
	            $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
	        }
	 
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}

    /**
	 * A shortcode for rendering the form used to initiate the password reset.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_lost_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	 	
	 	// Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['errors'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['errors'] );
		 
		    foreach ( $error_codes as $error_code ) {
		        $attributes['errors'] []= $this->get_error_message( $error_code );
		    }

		}
		

	    if ( is_user_logged_in() ) {
	        return __( 'Você já está inscrito.', 'personalize-login' );
	    } else {

	        return $this->get_template_html( 'password_lost_form', $attributes );
	    }
	}

    /**
	 * Redirects the user to the custom "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	*/
	public function redirect_to_custom_lostpassword() {
	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	        if ( is_user_logged_in() ) {
	            $this->redirect_logged_in_user();
	            exit;
	        }
	 
	        wp_redirect( home_url( 'member-password-lost' ) );
	        exit;
	    }
	}


    /**
	 * Handles the registration of a new user.
	 *
	 * Used through the action hook "login_form_register" activated on wp-login.php
	 * when accessed through the registration action.
	 */
	public function do_register_user() {
	    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

	        $redirect_url = home_url( 'member-register' );
	 
	        if ( ! get_option( 'users_can_register' ) ) {
	            // Registration closed, display error
	            $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
	        } else {
	            $email = $_POST['email'];
	            $password = $_POST['password'];
	            $first_name = sanitize_text_field( $_POST['first_name'] );
	            //$last_name = sanitize_text_field( $_POST['last_name'] );
	            $last_name = '';
	            $user_phone = sanitize_text_field( $_POST['user_phone'] );
	            $user_cpf = sanitize_text_field( $_POST['user_cpf'] );
	            $user_gender = sanitize_text_field( $_POST['user_gender'] );
	 
	            $result = $this->register_user( $email, $password, $first_name, $last_name, $user_phone, $user_cpf, $user_gender );
	 
	            if ( is_wp_error( $result ) ) {
	                // Parse errors into a string and append as parameter to redirect
	                $errors = join( ',', $result->get_error_codes() );
	                $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
	            } else {
	                // Success, redirect to login page.
	                $redirect_url = home_url( 'member-login' );
	                $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
	            }
	        }
	 
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}


    /**
	 * Validates and then completes the new user signup process if all went well.
	 *
	 * @param string $email         The new user's email address
	 * @param string $first_name    The new user's first name
	 * @param string $last_name     The new user's last name
	 *
	 * @return int|WP_Error         The id of the user that was created, or error if failed.
	 */
	private function register_user( $email, $password, $first_name, $last_name, $user_phone, $user_cpf, $user_gender ) {
	    $errors = new WP_Error();
	 
	    // Email address is used as both username and email. It is also the only
	    // parameter we need to validate
	    if ( ! is_email( $email ) ) {
	        $errors->add( 'email', $this->get_error_message( 'email' ) );
	        return $errors;
	    }
	 
	    if ( username_exists( $email ) || email_exists( $email ) ) {
	        $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
	        return $errors;
	    }

	    if ( ! $user_phone ) {
	        $errors->add( 'user_phone', $this->get_error_message( 'user_phone' ) );
	        return $errors;
	    }

	    if ( ! $password ) {
	        $errors->add( 'user_pass', $this->get_error_message( 'user_pass' ) );
	        return $errors;
	    }
/*
	    if ( ! $user_gender ) {
	        $errors->add( 'user_gender', $this->get_error_message( 'user_gender' ) );
	        return $errors;
	    }*/
	 
	    // Generate the password so that the subscriber will have to check email...
	    //$password = wp_generate_password( 12, false );
	 
	    $user_data = array(
	        'user_login'    => $email,
	        'user_email'    => $email,
	        'user_pass'     => $password,
	        'first_name'    => $first_name,
	        'last_name'     => $last_name,
	        'nickname'      => $first_name,
	        'user_phone' 	=> $user_phone,
	        'user_cpf' 	    => $user_cpf,
	        'user_gender'  	=> $user_gender,
	    );
	 
	    $user_id = wp_insert_user( $user_data );

	    //salvando campos customizados
	   
	    update_user_meta( $user_id, 'user_phone', sanitize_text_field( $_POST['user_phone'] ) );
	    update_user_meta( $user_id, 'user_cpf', sanitize_text_field( $_POST['user_cpf'] ) );
	    update_user_meta( $user_id, 'user_gender', sanitize_text_field( $_POST['user_gender'] ) );

	    //wp_new_user_notification( $user_id, $password );
	 
	    return $user_id;

	    // Caso o problema não seja resolvido pelo Dev Bruno, Realizar a substituição do "wp_new_user_notification" pelo input de password.
	}



	/*add_action( 'user_register', 'myplugin_user_register' );
    	
    	function myplugin_user_register( $user_id ) {
        if ( ! empty( $_POST['first_name'] ) ) {
            update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
        }
    }
*/
    /**
	 * Redirects the user to the custom registration page instead
	 * of wp-login.php?action=register.
	 */
	public function redirect_to_custom_register() {
	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	        if ( is_user_logged_in() ) {
	            $this->redirect_logged_in_user();
	        } else {
	            wp_redirect( home_url( 'member-register' ) );
	        }
	        exit;
	    }
	}

    /**
	 * A shortcode for rendering the new user registration form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_register_form( $attributes, $content = null ) {
		
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	 	
	 	// Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['register-errors'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['register-errors'] );
		 
		    foreach ( $error_codes as $error_code ) {
		        $attributes['errors'] []= $this->get_error_message( $error_code );
		    }
		}

	    if ( is_user_logged_in() ) {
	        return __( 'Você já está inscrito.', 'personalize-login' );
	    } elseif ( ! get_option( 'users_can_register' ) ) {
	        return __( 'Registrar novos usuários não é permitido no momento.', 'personalize-login' );
	    } else {
	        return $this->get_template_html( 'register_form', $attributes );
	    }
	}


/*public function redirect_to_custom_edit() {
	    
	    if ( 'POST' == $_SERVER['REQUEST_METHOD' && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user'] ) {
	       
	       wp_redirect( home_url( 'member-account' ) );

	        exit;
	    }
	}*/


/**
 * Alterando os dados no cadastro
 */
/*
public function do_edit_user() {

	if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {

	        $redirect_url = home_url( 'member-edit' );
	 
	        
	            $email = $_POST['email'];
	            $first_name = sanitize_text_field( $_POST['first_name'] );
	            $last_name = sanitize_text_field( $_POST['last_name'] );
	            $user_phone = sanitize_text_field( $_POST['user_phone'] );
	            $user_cpf = sanitize_text_field( $_POST['user_cpf'] );
	            $user_gender = sanitize_text_field( $_POST['user_gender'] );
	 
	            $result = $this->edit_user( $email, $first_name, $last_name, $user_phone, $user_cpf, $user_gender );
	 
	            if ( is_wp_error( $result ) ) {
	                // Parse errors into a string and append as parameter to redirect
	                $errors = join( ',', $result->get_error_codes() );
	                $redirect_url = add_query_arg( 'edit-errors', $errors, $redirect_url );
	            } else {
	                // Success, redirect to login page.
	                $redirect_url = home_url( 'member-edit' );
	            }
	        
	 
	        wp_redirect( $redirect_url );
	        exit;
	    }

}*/



    /**
	 * Validates and then completes the new user signup process if all went well.
	 *
	 * @param string $email         The new user's email address
	 * @param string $first_name    The new user's first name
	 * @param string $last_name     The new user's last name
	 *
	 * @return int|WP_Error         The id of the user that was created, or error if failed.
	 */
	/*private function edit_user( $email, $first_name, $last_name, $user_phone, $user_cpf, $user_gender ) {
	    $errors = new WP_Error();
	 
	    // Email address is used as both username and email. It is also the only
	    // parameter we need to validate
	    if ( ! is_email( $email ) ) {
	        $errors->add( 'email', $this->get_error_message( 'email' ) );
	        return $errors;
	    }
	 
	    if ( username_exists( $email ) || email_exists( $email ) ) {
	        $errors->add( 'email_exists', $this->get_error_message( 'email_exists') );
	        return $errors;
	    }

	    if ( ! $user_phone ) {
	        $errors->add( 'user_phone', $this->get_error_message( 'user_phone' ) );
	        return $errors;
	    }

	    if ( ! $user_cpf ) {
	        $errors->add( 'user_cpf', $this->get_error_message( 'user_cpf' ) );
	        return $errors;
	    }

	    if ( ! $user_gender ) {
	        $errors->add( 'user_gender', $this->get_error_message( 'user_gender' ) );
	        return $errors;
	    }


	    global $current_user;
	    get_currentuserinfo();
	    $user = wp_get_current_user();

	    $user_id = get_current_user_id();
	 
	    //$user_id = wp_insert_user( $user_data );

	    //salvando campos customizados
	   

	    wp_update_user( array( 'ID' => $user_id, 'user_email' => esc_attr( $_POST['user_email'] ) ) );
	    wp_update_user( array( 'ID' => $user_id, 'first_name' => esc_attr( $_POST['first_name'] ) ) );
	    wp_update_user( array( 'ID' => $user_id, 'last_name' => esc_attr( $_POST['last_name'] ) ) );
	    wp_update_user( array( 'ID' => $user_id, 'nickname' => esc_attr( $_POST['nickname'] ) ) );
	    wp_update_user( array( 'ID' => $user_id, 'user_email' => esc_attr( $_POST['user_email'] ) ) );
	    wp_update_user( array( 'ID' => $user_id, 'user_phone' => esc_attr( $_POST['user_phone'] ) ) );
	    wp_update_user( array( 'ID' => $user_id, 'user_cpf' => esc_attr( $_POST['user_cpf'] ) ) );
	    wp_update_user( array( 'ID' => $user_id, 'user_gender' => esc_attr( $_POST['user_gender'] ) ) );

	 
	}*/
/*
//Redirect
public function redirect_to_custom_edit() {
	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	        if ( is_user_logged_in() ) {
	            $this->redirect_logged_in_user();
	        } else {
	            wp_redirect( home_url( 'member-account' ) );
	        }
	        exit;
	    }
	}
*/

    /**
	 * A shortcode for rendering the new user registration form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	
	public function render_edit_form( $attributes, $content = null ) {
		
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	 	
	 	// Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['edit-errors'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['edit-errors'] );
		 
		    foreach ( $error_codes as $error_code ) {
		        $attributes['errors'] []= $this->get_error_message( $error_code );
		    }
		}

	    if ( is_user_logged_in() ) {
	       
	        return $this->get_template_html( 'edit_form', $attributes );
	    }
	}




    /**
		 * Redirect the user to the custom login page instead of wp-login.php.
		*/
		function redirect_to_custom_login() {
		    if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
		        
		        if( isset($_GET['url']) ){

		        	$redirect_to = $_GET['url'];
		    	
		    	} else {
		        
		        	$redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
				}
		     
		        if ( is_user_logged_in() ) {
		            $this->redirect_logged_in_user( $redirect_to );
		            exit;
		        }
		 
		        // The rest are redirected to the login page
		        $login_url = home_url( 'member-login' );
		        if ( ! empty( $redirect_to ) ) {
		            $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
		        }
		 
		        wp_redirect( $login_url );
		        exit;
		    }
		}
    /**
		 * Returns the URL to which the user should be redirected after the (successful) login.
		 *
		 * @param string           $redirect_to           The redirect destination URL.
		 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
		 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
		 *
		 * @return string Redirect URL
	*/

	public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {

		    if ( isset( $_POST['get_url'] ) ) {

		    	if( !empty( $_POST['get_url'] ) ) {

			    	$redirect_url = $_POST['get_url'];
			    
			    } else {

			    	$redirect_url = home_url() . '/dashboard/';

			    }

		    } else {

		    	$redirect_url = admin_url();
		    
		    }
		 
		 
		    return wp_validate_redirect( $redirect_url, home_url() );
	}

    /**
	 * A shortcode for rendering the login form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	*/
	public function render_login_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	    // Check if the user just registered
		$attributes['registered'] = isset( $_REQUEST['registered'] );
	    $show_title = $attributes['show_title'];
	 	// Check if user just updated password
		$attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';
	    
	    if ( is_user_logged_in() ) {
	        return __( 'Você já está inscrito.', 'personalize-login' );
	    }

	    // Error messages
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['login'] );
		 
		    foreach ( $error_codes as $code ) {
		        $errors []= $this->get_error_message( $code );
		    }
		}
		$attributes['errors'] = $errors;
	     
	    
	    // Pass the redirect parameter to the WordPress login functionality: by default,
	    // don't specify a redirect, but if a valid redirect URL has been passed as
	    // request parameter, use it.
	    $attributes['redirect'] = '';
	    if ( isset( $_REQUEST['redirect_to'] ) ) {
	        $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
	    }
	    
	    // Check if the user just requested a new password 
		$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

	    // Render the login form using an external template
	    return $this->get_template_html( 'login_form', $attributes );
	}

  	
	

	/**
	 * Redirects the user to the correct page depending on whether he / she
	 * is an admin or not.
	 *
	 * @param string $redirect_to   An optional redirect_to URL for admin users
	*/
	private function redirect_logged_in_user( $redirect_to = null ) {
	    $user = wp_get_current_user();
	    if ( user_can( $user, 'manage_options' ) ) {
	        if ( $redirect_to ) {
	            wp_safe_redirect( $redirect_to );
	        } else {
	            wp_redirect( admin_url() );
	        }
	    } else {
	        if( isset($_GET['url']) ){

		        	$redirect_url = $_GET['url'];
		    	
		    	} else {

		    		$redirect_url = home_url();
		    		
		    	}
	    }
	}









    /**
	 * Plugin activation hook.
	 *
	 * Creates all WordPress pages needed by the plugin.
	*/
	public static function plugin_activated() {
	    // Information needed for creating the plugin's pages
	    $page_definitions = array(
	    	 'dashboard' => array(
	            'title' => __( 'Painel', 'personalize-login' ),
	            'content' => '[dashboard]'
	        ),
	        'member-login' => array(
	            'title' => __( 'Login', 'personalize-login' ),
	            'content' => '[custom-login-form]'
	        ),
	        'member-account' => array(
	            'title' => __( 'Sua conta', 'personalize-login' ),
	            'content' => '[account-info]'
	        ),
	        'member-register' => array(
	        	'title' => __( 'Registrar', 'personalize-login' ),
	        	'content' => '[custom-register-form]'
	    	),
	    	'member-edit' => array(
	            'title' => __( 'Alterar meus dados', 'personalize-login' ),
	            'content' => '[custom-edit-form]'
	        ),
	    	'member-password-lost' => array(
		        'title' => __( 'Esqueceu sua senha?', 'personalize-login' ),
		        'content' => '[custom-password-lost-form]'
		    ),
		    'member-password-reset' => array(
		        'title' => __( 'Escolha uma nova senha', 'personalize-login' ),
		        'content' => '[custom-password-reset-form]'
		    )
	    	
	    );
	 
	    foreach ( $page_definitions as $slug => $page ) {
	        // Check that the page doesn't exist already
	        $query = new WP_Query( 'pagename=' . $slug );
	        if ( ! $query->have_posts() ) {
	            // Add the page using the data from the array above
	            wp_insert_post(
	                array(
	                    'post_content'   => $page['content'],
	                    'post_name'      => $slug,
	                    'post_title'     => $page['title'],
	                    'post_status'    => 'publish',
	                    'post_type'      => 'page',
	                    'ping_status'    => 'closed',
	                    'comment_status' => 'closed',
	                )
	            );
	        }
	    }
	}
     
     /**
	 * Renders the contents of the given template to a string and returns it.
	 *
	 * @param string $template_name The name of the template to render (without .php)
	 * @param array  $attributes    The PHP variables for the template
	 *
	 * @return string               The contents of the template.
	*/
	private function get_template_html( $template_name, $attributes = null ) {
	    if ( ! $attributes ) {
	        $attributes = array();
	    }
	 
	    ob_start();
	 
	    do_action( 'personalize_login_before_' . $template_name );
	 
	    require( 'templates/' . $template_name . '.php');
	 
	    do_action( 'personalize_login_after_' . $template_name );
	 
	    $html = ob_get_contents();
	    ob_end_clean();
	 
	    return $html;
	}




	/**
	 * Redirect the user after authentication if there were any errors.
	 *
	 * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
	 * @param string            $username   The user name used to log in.
	 * @param string            $password   The password used to log in.
	 *
	 * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
	*/
	function maybe_redirect_at_authenticate( $user, $username, $password ) {
	    // Check if the earlier authenticate filter (most likely, 
	    // the default WordPress authentication) functions have found errors
	    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	        if ( is_wp_error( $user ) ) {
	            $error_codes = join( ',', $user->get_error_codes() );
	 
	            $login_url = home_url( 'member-login' );
	            $login_url = add_query_arg( 'login', $error_codes, $login_url );
	 
	            wp_redirect( $login_url );
	            exit;
	        }
	    }
	 
	    return $user;
	}






	/**
	 * Finds and returns a matching error message for the given error code.
	 *
	 * @param string $error_code    The error code to look up.
	 *
	 * @return string               An error message.
	*/
	
	private function get_error_message( $error_code ) {
	    switch ( $error_code ) {
	    	// Registration errors
 			// Lost password
 
			case 'empty_username':
    		return __( 'Você precisa digitar seu endereço de e-mail para continuar.', 'personalize-login' );
 
			case 'invalid_email':
			case 'invalidcombo':
    		
    		return __( 'Não há usuários registrados com este endereço de e-mail.', 'personalize-login' );
			
			case 'email':
			    return __( 'O endereço de email que você inseriu não é válido.', 'personalize-login' );
			 
			case 'email_exists':
			    return __( 'Existe uma conta com este endereço de email.', 'personalize-login' );
			 
			case 'closed':
			    return __( 'Registrar novos usuários não é permitido atualmente.', 'personalize-login' );

	        case 'empty_username':
	            return __( 'Você tem um endereço de e-mail, certo?', 'personalize-login' );
	 
	        case 'empty_password':
	            return __( 'Você precisa digitar uma senha para fazer o login.', 'personalize-login' );
	 
	        case 'invalid_username':
	            return __(
	                "Não temos usuários com esse endereço de e-mail. Talvez você tenha usado um diferente ao se inscrever?",
	                'personalize-login'
	            );
	 
	        case 'incorrect_password':
	            $err = __(
	                "A senha que você digitou não estava correta. <a href='%s'>Você esqueceu sua senha</a>?",
	                'personalize-login'
	            );
	        
	        return sprintf( $err, wp_lostpassword_url() );
	 		// Reset password
 
			case 'expiredkey':
			case 'invalidkey':
			    return __( 'The password reset link you used is not valid anymore.', 'personalize-login' );
			 
			case 'password_reset_mismatch':
			    return __( "The two passwords you entered don't match.", 'personalize-login' );
			     
			case 'password_reset_empty':
			    return __( "Sorry, we don't accept empty passwords.", 'personalize-login' );

	        default:
	            break;
	    }
	     
	    return __( 'Ocorreu um erro desconhecido. Por favor, tente novamente mais tarde.
', 'personalize-login' );
	}

}
 
// Initialize the plugin
$personalize_login_pages_plugin = new Personalize_Login_Plugin();

// Create the custom pages at plugin activation
register_activation_hook( __FILE__, array( 'Personalize_Login_Plugin', 'plugin_activated' ) );