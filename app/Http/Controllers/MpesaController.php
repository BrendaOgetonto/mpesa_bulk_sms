<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class MpesaController extends Controller
{
    public function stk_push(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mpesa_number' => 'required',
            // 'amount' => 'required'
        ]);

        if ( $validator->passes() ) {
            //get access token
            $consumer_key="9JYwcWAxWvetqF621moM0G6nujAIGaXp";
            $consumer_secret="B4Hy3gnemAp2Pbny";
            $credentials = base64_encode($consumer_key.":".$consumer_secret);
            $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials));
            curl_setopt($curl, CURLOPT_HEADER,false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $curl_response = curl_exec($curl);
            $access_token=json_decode($curl_response);
            // echo($access_token->access_token);
            echo $access_token->access_token;


            $initiate_url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $access_token = $access_token->access_token;
  
            $BusinessShortCode = '	174379';
            $Timestamp = date('YmdHis');
            $PartyA = $request->input( 'mpesa_number' );
            $CallBackURL = 'http://ec2-18-222-173-59.us-east-2.compute.amazonaws.com/payment/stk_callback_url.php';
            $AccountReference = $request->input( 'mpesa_number' );
            $TransactionDesc = 'Paying bill';
            $Amount = $request->input( 'amount' );
            $Passkey = '	bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
            $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header

            
            $curl_post_data = array(
                'BusinessShortCode' => $BusinessShortCode,
                'Password' => $Password,
                'Timestamp' => $Timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $Amount,
                'PartyA' => $PartyA,
                'PartyB' => $BusinessShortCode,
                'PhoneNumber' => $PartyA,
                'CallBackURL' => $CallBackURL,
                'AccountReference' => $AccountReference,
                'TransactionDesc' => $TransactionDesc
            );
            
            $data_string = json_encode($curl_post_data);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

            $curl_response = curl_exec($curl);
            print_r($curl_response);

            echo $curl_response;
        } 
        else {
            return back()->withErrors( $validator );
        }
    }


    //this section could not work since the confirmationurl and validation url are not on secure websites.
    public function register_url(){
        //get access token
        $consumer_key="9JYwcWAxWvetqF621moM0G6nujAIGaXp";
        $consumer_secret="B4Hy3gnemAp2Pbny";
        $credentials = base64_encode($consumer_key.":".$consumer_secret);
        $url = "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials));
        curl_setopt($curl, CURLOPT_HEADER,false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        $access_token=json_decode($curl_response);
        // echo($access_token->access_token);
        echo $access_token->access_token;


        //register url
        $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';


        $access_token = $access_token->access_token;
        $shortCode = '174379';
        $confirmationUrl = 'http://ec2-18-222-173-59.us-east-2.compute.amazonaws.com/payment/confirmation_url.php';
        $validationUrl = 'http://ec2-18-222-173-59.us-east-2.compute.amazonaws.com/payment/validation.php';



        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header


        $curl_post_data = array(
        //Fill in the request parameters with valid values
        'ShortCode' => $shortCode,
        'ResponseType' => 'Confirmed',
        'ConfirmationURL' => $confirmationUrl,
        'ValidationURL' => $validationUrl
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);
        print_r($curl_response);

        echo $curl_response;

    }
}
