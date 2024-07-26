<?php

namespace App\Http\Services\Api\OTO;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Services\Api\OTO\OTOServiceInterface;
use Maree\Oto\Oto;
class OTOService implements OTOServiceInterface {
    protected $refresh_token;
    public function __construct()
    {
        $this->refresh_token=config('oto.refresh_token');
    }

    public function createOrder($paymentItem,$payment){
       try{
        $orderData   = ['orderId' => time().$payment->user_id, "ref1"=> time(),
        "pickupLocationCode"=> "55211",
        "deliveryOptionId"=>time(),
        "serviceType"=> "",
        "storeName"=> "Alrass",
        "payment_method"=> "paid",
        "amount"=>$payment->amount,
        "amount_due"=> 0,
        "shippingAmount"=>0,
        "subtotal"=>$payment->amount,
        "currency"=> "SAR",
        "shippingNotes"=> "",
        "packageSize"=> "small",
        "packageCount"=> 1,
        "packageWeight"=> 1,
        "orderDate"=>date('d/m/Y H:i'),
        "deliverySlotDate"=>date('d/m/Y H:i'),
        "deliverySlotTo"=> "12pm",
        "deliverySlotFrom"=> "2=>30pm",];
        $customeData = ['name' =>$payment->name ,'email' =>$payment->email , 'mobile' =>$payment->phone];
        $addressData = ['address' =>$payment->address,'district' => '' ,'city' =>$payment->city,'country' => 'SA' ,'lat' => '','lng' => ''];
        $items       = [ ["productId" => '1234', "name"=>$paymentItem->name_ar, "price"=>$paymentItem->amount, "rowTotal"  =>$paymentItem->amount, "taxAmount" => '0', "quantity"  =>$paymentItem->quantity, "sku"  =>$paymentItem->name_ar, "image" => '']];
        $response = Oto::createOrder($orderData ,$customeData ,$addressData ,$items);
        $otoId=json_decode($response,true)['otoId'];
        return $otoId;
       }catch(Throwable $e){
        Log::error('Faild to load create OTO Shipment message '.$e->getMessage());
        return null;
       }
    }

    public function cancel($otoId){
        $response = Oto::cancelOrder($otoId);
        return $response;
    }
}
