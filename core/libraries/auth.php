<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {

        var $CI = NULL;

        function __construct(){

            $this->CI =& get_instance();
            $this->CI->load->library('session');

            $controller = $this->CI->uri->segment(1, $this->CI->input->get('c') );
            $method 	= $this->CI->uri->segment(2, $this->CI->input->get('m') );

            if(isset($_GET['digest']) && $_GET['digest']==1){

				/* Compruebo credenciales por HTTP AUTH */
				if (!isset($_SERVER['PHP_AUTH_USER'])) {
					header('WWW-Authenticate: Basic realm="My Realm"');
					header('HTTP/1.0 401 Unauthorized');
					$jsonAuthError = array('result'=>array('status'=>'auth_error', 'msg'=>'Error de autentificacion'));
					die(json_encode($jsonAuthError));
				} else {

					$user_request = $this->get_user($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);

					if($user_request['result']!='success'){
						$jsonAuthError = array('result'=>array('status'=>'auth_error', 'msg'=>'Error de autentificacion'));
						die(json_encode($jsonAuthError));
					}

				}

				// If I reach here the login was successful and the script goes on

			}elseif($this->CI->input->is_cli_request()){
				// No restrinjo acceso cuando se accede por consola
				// Simulamos usuario superadmin
			    $this->CI->session->set_userdata('nombre', 'Admin web');
			    $this->CI->session->set_userdata('perfil', 'admin');
			}else{

				/* Compruebo credenciales por SESSION */

				if($this->CI->session->userdata('islogged')==FALSE && $controller != "login"){
					$this->restrict();
				}

				if($this->CI->session->userdata('islogged')==TRUE && $controller=="login" && $method!="logout"){
					redirect(base_url().$this->CI->config->item('controller_inicio'),'location');
				}


			}

        }

        function get_user($user,$password=null,$encrypt=true){

			if($password != null){
				$password = ($encrypt) ? md5($password) : $password;
			}

			$user = $this->CI->admins_model->get_user($user,$password);

			if(isset($user['id']) && $user['id']>0){

				//$user = $user[0];
				$user['result'] = 'success';

			}else{
				$user['result'] = 'failed';
			}

			return $user;

		}

        function restrict()
        {
        	$this->CI->session->set_flashdata(
        		'referrer',
        		current_url().(
        			(isset($_SERVER['QUERY_STRING']) AND $_SERVER['QUERY_STRING']!='')
        			? '?'.$_SERVER['QUERY_STRING']
        			: ''
        		)
        	);
            redirect(base_url().'login','refresh');

        }

}

?>
