<?php
    class Parameters{

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
         * Get Parameters
         * @return array int[4]
         */

        public function getParameters(){
            try{
                $query = "SELECT frequencyPackages, beltSpeed, containerCapacity, containerSpeed FROM `parameters` WHERE id=1;";
                $result = mysqli_query($this->con_aux, $query);
                $info = mysqli_fetch_assoc($result);
                $count = mysqli_num_rows($result);
                mysqli_close($this->con_aux);

                if( $count == 1){
                    return array($info['frequencyPackages'], $info['beltSpeed'], $info['containerCapacity'], $info['containerSpeed']);
                }else{
                    return array(1,1,5,1); // Return min values (default value)
                }

            }catch(Exception $e){
                return array(1,1,5,1); // Return min values (default value)
            }

        }

        /**
         * Set Parameters
         * @return boolean
         */

        public function setParameters(array $data){
            try{

                // Get Parameters of data
                $parameters_data = array_map('trim', $data);
                $frequency = mysqli_real_escape_string( $this->con_aux,  $parameters_data['customRangeFrequency'] );
                $beltSpeed = mysqli_real_escape_string( $this->con_aux,  $parameters_data['customRangeBeltSpeed'] );
                $containerCapacity = mysqli_real_escape_string( $this->con_aux,  $parameters_data['customRangeContainerCapacity'] );
                $containerSpeed = mysqli_real_escape_string( $this->con_aux,  $parameters_data['customRangeContainerSpeed'] );

                $query = "UPDATE parameters SET frequencyPackages=$frequency, beltSpeed=$beltSpeed, containerCapacity=$containerCapacity, containerSpeed=$containerSpeed WHERE id=1;";
                $result = mysqli_query($this->con_aux, $query);
                if($result){
                    // Success update
                    mysqli_close($this->con_aux);
                    return true;
                }else{
                    mysqli_close($this->con_aux);
                    throw new Exception( FAIL_UPDATE_PARAMETERS );  
                }
            }catch(Exception $e){
                throw new Exception( FAIL_UPDATE_PARAMETERS );
            }
        }
    }
?>