<?php
namespace config;

class jwt 
{

    public function jwttoken($sic) 
    {   
        $secretkey = 'secret'.$sic;
        $str = "";
        
        //constructing header
        $header = json_encode(array('alg' => "HS256", 'typ' => "JWT"));

        //encrypting header
        $headerstr = base64_encode( $header );

        //constructing payload
        $payload = json_encode(array('sub' => "1234567", 'sic' => $sic));

        //encrypting header
        $payloadstr = base64_encode( $payload );

        //constructing signature
        $signature =  hash_hmac('sha256', $headerstr.$payload, $secretkey);


        return $headerstr.".".$payloadstr.".".$signature;

    }

}
  
?>