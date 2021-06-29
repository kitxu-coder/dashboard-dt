<?php
	class DBcon
	{
		/**
		 * @var DataBase Connection
		 */
		public $con;
		
		/**
		 * Create the connection to the database 
		 */
		public function __construct()
		{
			$this->con = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
			if( mysqli_connect_error()) echo "MySQL connection failed : " . mysqli_connect_error();
		}
	}
?>