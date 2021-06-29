<?php
    header("Content-Type:application/json");
    require_once 'config.php';

    //Check parameters
    try{
        $parameter_obj = new Parameters();
        list($frequency, $beltSpeed, $containerCapacity, $containerSpeed) = $parameter_obj->getParameters();

        $data = [
            "frequency"=> intval($frequency),
            "beltSpeed"=> intval($beltSpeed),
            "containerCapacity"=> intval($containerCapacity),
            "containerSpeed" => intval($containerSpeed)
        ];

        response(200,SUCCESS_REQUEST,$data);

    }catch(Exception $e){
        response(400, INVALID_REQUEST, null);
    }

    /**
     * response
     * Returns the JSON with the requested data.  
     * @return output JSON
     */
    
    function response($status, $message, $data){
        header("HTTP/1.1 ".$status);
        $result['status']=$status;
        $result['status_message']=$message;
        $result['data']=$data;
        $json_response = json_encode($result);
        echo $json_response;
    }

?>