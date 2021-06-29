<?php
	class User
	{
		
		/**
		 * @var DataBase Connection
		 */
		protected $con_aux;
		
		/**
		 * Initialize Class DB_con
		 */
		public function __construct()
		{
			$db = new DBcon();
			$this->con_aux = $db->con;
		}
		
		/**
		 * Sign-In with a user and password
		 * @param array $data
		 * @return boolean
		 */

		public function signin( array $data )
		{
			$_SESSION['signin'] = false; // Var Session 
			if( !empty( $data ) ){
				
				// Get User data
				$user_data = array_map('trim', $data);
				$email = mysqli_real_escape_string( $this->con_aux,  $user_data['email'] );
				$password = mysqli_real_escape_string( $this->con_aux,  $user_data['password'] );
					
				if((!$email) || (!$password) ) {
					$_SESSION['message'] = WHITE_USERNAME_PASSWORD;
					$_SESSION['error_message'] = true;
					throw new Exception( WHITE_USERNAME_PASSWORD );
				}
				
				$password = md5( $password ); // MD5 hash function
				
				$query = "SELECT id, name, email, password FROM users where email = '$email' and password = '$password' ";
				$result = mysqli_query($this->con_aux, $query);
				$info = mysqli_fetch_assoc($result);
				$count = mysqli_num_rows($result);
				mysqli_close($this->con_aux);
				
				if( $count == 1){
					$_SESSION['user_data'] = $info;
					$_SESSION['signin'] = true;
					return true;
				}else{
					// Error : Username & Password aren't correct
					$_SESSION['message'] = FAIL_USERNAME_PASSWORD;
					$_SESSION['error_message'] = true;
					throw new Exception( FAIL_USERNAME_PASSWORD );
				}
			} else{
				// Error: Incomplete data
				$_SESSION['message'] = WHITE_USERNAME_PASSWORD;
				$_SESSION['error_message'] = true;
				throw new Exception( WHITE_USERNAME_PASSWORD );
			}
		}

		/**
		 * Register
		 * @param array $data
		 * @return boolean
		 */
		
		public function register($data){
			if( !empty( $data ) ){
				
				// Get User data 
				$user_data = array_map('trim', $data);
				// Validate email format
				if (filter_var( $user_data['email'], FILTER_VALIDATE_EMAIL)) {
					$email = mysqli_real_escape_string( $this->con_aux, $user_data['email']);
				} else {
					$_SESSION['message'] = FAIL_EMAIL;
					$_SESSION['error_message'] = true;
					throw new Exception( FAIL_EMAIL );
				} 


				// Check unique email
				if($this->isRepeatEmail($email)){
					$_SESSION['message'] = MATCHING_EMAIL;
					$_SESSION['error_message'] = true;
					throw new Exception( MATCHING_EMAIL);
				} 

				$username = mysqli_real_escape_string( $this->con_aux, $user_data['username'] );
				$password = mysqli_real_escape_string( $this->con_aux, $user_data['password'] );
				$passwordRepeat = mysqli_real_escape_string( $this->con_aux, $user_data['passwordRepeat'] );
				
				// Check passwords
				if ($password !== $passwordRepeat) {
					$_SESSION['message'] = PASSWORDS_NOT_MATCH;
					$_SESSION['error_message'] = true;
					throw new Exception( PASSWORDS_NOT_MATCH );
				}
				
				// Check empty fields
				if((!$username) || (!$email) || (!$password) || (!$passwordRepeat) ) {
					$_SESSION['message'] = EMPTY_FIELDS;
					$_SESSION['error_message'] = true;
					throw new Exception( EMPTY_FIELDS );
				}

				$password = md5( $password ); // MD5 hash function
				$query = "INSERT INTO users (email, name, password) VALUES ('$email', '$username', '$password')";
				if(mysqli_query($this->con_aux, $query)){
					mysqli_close($this->con_aux);
					$_SESSION['message'] = REGISTER_OK;
					$_SESSION['error_message'] = false;
					return true;
				}else{
					$_SESSION['message'] = REGISTER_FAIL;
					$_SESSION['error_message'] = true;
					throw new Exception (REGISTER_FAIL);
				}
			} else{
				$_SESSION['message'] = REGISTER_FAIL;
				$_SESSION['error_message'] = true;
				throw new Exception( REGISTER_FAIL );
			}
		}

		/**
		 * Update
		 * @param $email, array $data
		 * @return boolean
		 */
		
		public function update($email, $data){
			if( !empty( $data ) ){
				
				// Get User data 
				$user_data = array_map('trim', $data);

				// Validate data
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$email = mysqli_real_escape_string( $this->con_aux, $email);
				} else {
					$_SESSION['message'] = FAIL_EMAIL;
					$_SESSION['error_message'] = true;
					throw new Exception( FAIL_EMAIL );
				}

				$username = mysqli_real_escape_string( $this->con_aux, $user_data['username'] );
				$password = mysqli_real_escape_string( $this->con_aux, $user_data['password'] );
				$passwordRepeat = mysqli_real_escape_string( $this->con_aux, $user_data['passwordRepeat'] );

				//Check passwords
				if ($password !== $passwordRepeat) {
					$_SESSION['message'] = PASSWORDS_NOT_MATCH;
					$_SESSION['error_message'] = true;
					throw new Exception( PASSWORDS_NOT_MATCH );
				}
				
				//Check empty fields
				if((!$username) || (!$password) || (!$passwordRepeat) ) {
					$_SESSION['message'] = EMPTY_FIELDS;
					$_SESSION['error_message'] = true;
					throw new Exception( EMPTY_FIELDS );
				}

				$password = md5( $password ); // MD5 hash function
				$query = "UPDATE users SET name='$username', password='$password' WHERE email='$email';";
				if(mysqli_query($this->con_aux, $query)){
					mysqli_close($this->con_aux);
					$_SESSION['message'] = UPDATE_OK;
					$_SESSION['error_message'] = false;
					return true;
				}else{
					$_SESSION['message'] = UPDATE_FAIL;
					$_SESSION['error_message'] = true;
					throw new Exception (UPDATE_FAIL);
				}
			} else{
				$_SESSION['message'] = UPDATE_FAIL;
				$_SESSION['error_message'] = true;
				throw new Exception( UPDATE_FAIL );
			}	
		
		}
		

		/**
		 * Logout : destroy session
		 */

		public function logout(){
			$_SESSION = array();
			session_unset();
			session_destroy();
			header('Location: ../index.php');
		}

		/**
		 * isRegisteredUser
		 * Check if the session user is a registered user. 
		 * @return boolean
		 */

		public function isRegisteredUser(){
			if(isset($_SESSION['user_data']) && $_SESSION['user_data']){
				try{
					$email = $_SESSION['user_data']['email'];
					$name = $_SESSION['user_data']['name'];
					$password =  $_SESSION['user_data']['password'];
					$query = "SELECT email FROM users where email = '$email' and name='$name' and password = '$password' ";
					$result = mysqli_query($this->con_aux, $query);
					$count = mysqli_num_rows($result);
					
					if( $count == 1){
						return true;
					}else{
						return false;
					}
				}catch(Exception $e){
					return false;
				}

			}else{
				return false;
			}
		}

		/**
		 * isRepeatEmail
		 * Check if the email entered is already registered. 
		 * @return boolean
		 */

		public function isRepeatEmail($email){
			try{
				$query = "SELECT email FROM users where email = '$email' ";
				$result = mysqli_query($this->con_aux, $query);
				$count = mysqli_num_rows($result);

				if( $count == 1){
					return true;
				}else{
					return false;
				}

			}catch(Exception $e){
				return true;
			}
		}

	}
?>