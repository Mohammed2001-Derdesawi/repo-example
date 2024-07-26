<?php

namespace App\Http\Services\Api\PointsCalculation;

use App\Http\Requests\Api\PointsCalculation\DifferentialPointsCalculationRequest;
use App\Http\Traits\Responser;
use Carbon\Carbon;
use Exception;

abstract class DifferentialPointsCalculationService
{
    use Responser;

    public function calculate(DifferentialPointsCalculationRequest $request) {
        try {
            $average = $this->calculateAverage($request->average_rate, $request->average);
            $specialization_exam_mark = $request->specialization_exam_mark * 0.4;
            $general_exam_mark = $request->general_exam_mark * 0.2;
            $seniority = $this->calculateSeniority($request->year, $request->month, $request->day,$request->deadlineday,$request->deadlinemonth,$request->deadlineyear);
            $total = $seniority + $general_exam_mark + $specialization_exam_mark + $average;

            $total = number_format($total, 2);
            $seniority = number_format((float)$seniority, 2);
            $average = number_format((float)$average, 2);
            $general_exam_mark = number_format((float)$general_exam_mark, 2);
            return $this->responseSuccess(data: [
                'seniority' => (float)$seniority,
                'average' => (float)$average,
                'specialization_exam_mark' => $specialization_exam_mark,
                'general_exam_mark' => (float)$general_exam_mark,
                'total' => (float)$total,
            ]);
        } catch (Exception $e) {
//            return $e->getMessage();
            return $this->responseFail(message: __('messages.Something went wrong'));
        }
    }

    private function calculateAverage($averageRate, $average) {
        switch ($averageRate) {
            case '5':
                $average = $this->getAverage($average, [4.5, 3.75, 2.75]);
                break;

            case '4':
                $average = $this->getAverage($average, [3.5, 2.75, 2]);
                break;
        }
        $average *= 0.2;
        return $average;
    }

    private function getAverage($average, array $minAverage) {
        switch ($average) {
            case $average >= $minAverage[0]:
                $average = ($average - $minAverage[0]) / 0.5;
                $average = $average * 10 + 90;
                break;

            case $average >= $minAverage[1]:
                $average = ($average - $minAverage[1]) / 0.75;
                $average = $average * 10 + 80;
                break;

            case $average >= $minAverage[2]:
                $average = ($average - $minAverage[2]) / 0.75;
                $average = $average * 10 + 70;
                break;

            default:
                $average += 60;
        }
        return $average;
    }

    protected function calculateSeniority($year,$month,$day,$deadlineDay,$deadLineMonth,$deadLineYear) {
        $endDate = Carbon::createFromDate($deadLineYear, $deadLineMonth, $deadlineDay, 'UTC');
        $years = Carbon::createFromDate($year, $month, $day,'UTC')->diffInYears($endDate);
        if ($years >= 10) {
            $seniority = 10 * 2;
        } else {
            $m = $endDate->month - (int)$month;
            if ($m <= 0) {
                $m = 12 + $m;
            }
            $d = $endDate->day - (int)$day;
            if ($d < 0) {
                --$m;
                $d = 30 + $d;
            }
            $days = ($years * 360) + ($m * 30) + ($d);
            $seniority = (($days) / (10 * 360)) * 100;
            $seniority *= 0.2;
        }
        return $seniority;
    }

}
