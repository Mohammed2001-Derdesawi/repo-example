<?php

namespace App\Http\Services\Api\WhatsApp;

use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WhatsAppService implements WhatsAppServiceInterface {
    protected $baseUrl;
    protected $token;
    protected $to;
    protected $image;
    protected $message;

    public function __construct()
    {
        $this->baseUrl='https://go-wloop.net/api/v1';
        $this->token=config('services.whatsapp_loop.token');
    }
    private function getMediaUrl(){
        return '/send/'.$this->mediaType();
    }

    private function mediaType(){
        $extension=pathinfo($this->image, PATHINFO_EXTENSION);
        switch($extension){
            case in_array($extension,config('filesystems.document_extensions')):
            return 'file';
            break;
            default:return 'image'; break;
        }
    }
    public function sendMedia(){
            $data=[
                'url'=>$this->image,
                'phone'=>$this->to,
                'caption'=>$this->message,
            ];
            $client=$this->getClient();
            try{
                $client->post($this->getMediaUrl(),$data)->json();
                return true;
            }catch(Throwable $e){
                Log::info('Faild to send WhatsApp Mesasege ' . $e->getMessage());
            }

        return false;
    }

    public function sendSimpleMessage(){
                $data=[
                    'phone'=>$this->to,
                    'body'=>$this->message,
                ];
                $client=$this->getClient();
                try{
                    $client->post('/message/send',$data)->json();
                    return true;
                }catch(Throwable $e){
                    Log::info('Faild to send WhatsApp Mesasege ' . $e->getMessage());
                }

            return false;

    }

    public function getClient(){
        return Http::withToken($this->token)->baseUrl($this->baseUrl);
    }

    private function setOptions($options)
    {
        if(isset($options['to'])){
            $this->to='966'.($options['to'][0]=='0'? substr( $options['to'],1):$options['to']);
        }
        if(isset($options['message'])){
            $this->message=$options['message'];
        }
        if(isset($options['image'])){
            $this->image=$options['image'];
        }
    }

    public function sendMessage($options)
    {
        $this->setOptions($options);
        if(!empty($this->to) && !empty($this->image)  && !empty($this->message)){
            return $this->sendMedia();
        }
        elseif(!empty($this->to)   && !empty($this->message)){
           return  $this->sendSimpleMessage();
        }

    }
}
