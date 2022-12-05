<?php

namespace App\Models;
use App\Models\Zarinpal;
class Payment
{
    public $MerchantID;
    public $Amount;
    public $ZarinGate;
    public $SandBox;
    public $CallbackURL;
    public $Description;

    public function __construct($amount)
    {
        $this->MerchantID 	= "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx";
        $this->Amount 		= $amount;
        $this->CallbackURL="http://localhost:8000/api/administrator/verify";
        $this->ZarinGate 	= false;
        $this->SandBoxDescription 		= false;
        $this->Description="nsdcmndnm";
}

    public function doPayment(){
        $zp = new Zarinpal();
        $result=$zp->verify($this->MerchantID, $this->Amount, $this->SandBox, $this->ZarinGate);
        if (isset($result["Status"]) && $result["Status"] == 100)
        {
            // Success
            echo "تراکنش با موفقیت انجام شد";
            echo "<br />مبلغ : ". $result["Amount"];
            echo "<br />کد پیگیری : ". $result["RefID"];
            echo "<br />Authority : ". $result["Authority"];
        } else {
            // error
            echo "پرداخت ناموفق";
            echo "<br />کد خطا : ". $result["Status"];
            echo "<br />تفسیر و علت خطا : ". $result["Message"];
        }

        return $result;
    }


    public function request()
    {
        $zp 	= new Zarinpal();
        $result = $zp->request($this->MerchantID, $this->Amount, $this->Description,'','',$this->CallbackURL, $this->SandBox,$this->ZarinGate);
        return $result;
        if (isset($result["Status"]) && $result["Status"] == 100)
        {
            // Success and redirect to pay
            $zp->redirect($result["StartPay"]);
        } else {
            // error
            echo "خطا در ایجاد تراکنش";
            echo "<br />کد خطا : ". $result["Status"];
            echo "<br />تفسیر و علت خطا : ". $result["Message"];
        }
    }

    public function rest()
    {
        $data = array("merchant_id" => "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx",
            "amount" => 1000,
            "callback_url" => "http://localhost:8000/api/administrator",
            "description" => "خرید تست",
            "metadata" => [ "email" => "info@email.com","mobile"=>"09121234567"],
        );
        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));
        $result = curl_exec($ch);

        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if (empty($result['errors'])) {
                if ($result['data']['code'] == 100) {
                    header('Location: https://sandbox.zarinpal.com/pg/StartPay/' . $result['data']["authority"]);
                }
            } else {
                echo'Error Code: ' . $result['errors']['code'];
                echo'message: ' .  $result['errors']['message'];

            }
        }
    }
}
