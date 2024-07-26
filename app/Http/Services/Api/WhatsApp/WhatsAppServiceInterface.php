<?php

namespace App\Http\Services\Api\WhatsApp;

interface WhatsAppServiceInterface {
    public function sendMedia();
    public function sendSimpleMessage();
    public function sendMessage($options);

}
