<?php

namespace App\Http\Services\Api\PointsCalculation;

use App\Http\Requests\Api\PointsCalculation\MetricalPointsCalculationRequest;
use App\Http\Traits\Responser;
use Exception;

abstract class MetricalPointsCalculationService
{
    use Responser;

    public function calculate(MetricalPointsCalculationRequest $request) {
        $data = $request->validated();
        try {
            return $this->responseSuccess(data: [
                'high_school' => $data['high_school_percentage'] * $data['high_school_percentage_rate'] / 100,
                'aptitude_exam' => $data['aptitude_exam_mark'] * $data['aptitude_exam_percentage_rate'] / 100,
                'achievement_exam' => $data['achievement_exam_mark'] * $data['achievement_exam_percentage_rate'] / 100,
            ]);
        } catch (Exception $e) {
//            return $e->getMessage();
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

}
