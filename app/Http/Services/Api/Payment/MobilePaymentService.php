<?php

namespace App\Http\Services\Api\Payment;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Api\Payment\PaymentRequest;

class MobilePaymentService extends PaymentService
{
    public function initiate(PaymentRequest $request) {
        $data = $request->validated();
        DB::beginTransaction();
        try {
            if ($data['type'] == 'CASH') {
                $data['transfer_image'] = $this->fileManager->handle('transfer_image', 'payments/transfers');
            }
            $amount = $this->{$this->payable[$data['pay']]['repository']}->getById($data[$data['pay'].'_id'], $this->payable[$data['pay']]['columns'])->{$this->payable[$data['pay']]['amount']};
            $printRequestData = $request->only(['name', 'email', 'phone', 'qualification', 'address', 'nationality', 'national_id']);
            $payment = $this->paymentRepository->create([
                'payment_type' => $data['type'],
                'payable_type' => $this->payable[$data['pay']]['type'],
                'payable_id' => $data[$data['pay'].'_id'],
                'user_id' => auth('api')->id(),
                'amount' => $amount,
                'transfer_image' => $data['transfer_image'] ?? null,
                'bank_account_name' => $data['bank_account_name'] ?? null,
                'bank_account_number' => $data['bank_account_number'] ?? null,
                'from_bank' => $data['from_bank'] ?? null,
                'to_bank' => $data['to_bank'] ?? null,
                'transfer_amount' => $data['transfer_amount'] ?? null,
                'transfer_date' => $data['transfer_date'] ?? null,
                'transfer_time' => $data['transfer_time'] ?? null,
                'is_confirmed' => false,
                'city' => $data['city']??null,
                ...$printRequestData
            ]);


            if ($data['type'] == 'EPAYMENT') {
                if($payment->amount == 0){
                    $this->assign->handle($payment, $request->certificate_user_id, true);
                    $this->paymentRepository->update($payment->id, ['is_confirmed' => true, 'is_declined' => false]);
                }

                DB::commit();

                return $this->responseSuccess(data: [
                    'amount' => $payment->amount,
                    'metadata' => [
                        'udf1' => auth('api')?->id(),
                        'udf2' => Carbon::now()->toDateTimeString(),
                        'udf3' => $payment->id,
                        'udf4' => $request->certificate_user_id ?? '',
                    ],
                    'customer' => [
                        'first_name' => auth('api')->user()->name,
                        'last_name' => '',
                        'email' => auth('api')->user()->email,
                    ],
                ]);
            } elseif ($data['type'] == 'TAMARA') {
                return $this->tamaraPayment($payment, $request->certificate_user_id, $request->instalments);
            } else {
                $this->assign->handle($payment, $request->certificate_user_id);
                DB::commit();

                return $this->responseSuccess(message: __('messages.Payment will be reviewed'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Payment Error " .$e->getMessage());
//            return $e->getMessage();
            return $this->responseFail(message: __('messages.An error occurred while processing the payment'));
        }
    }
}
