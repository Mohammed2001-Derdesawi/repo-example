<?php

namespace App\Http\Controllers\Api\PointsCalculation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PointsCalculation\DifferentialPointsCalculationRequest;
use App\Http\Requests\Api\PointsCalculation\MetricalPointsCalculationRequest;
use App\Http\Services\Api\PointsCalculation\DifferentialPointsCalculationService;
use App\Http\Services\Api\PointsCalculation\MetricalPointsCalculationService;

class PointsCalculationController extends Controller
{
    protected DifferentialPointsCalculationService $differential;
    protected MetricalPointsCalculationService $metrical;

    public function __construct(
        DifferentialPointsCalculationService $differentialPointsCalculationService,
        MetricalPointsCalculationService     $metricalPointsCalculationService,
    )
    {
        $this->differential = $differentialPointsCalculationService;
        $this->metrical = $metricalPointsCalculationService;
    }

    public function differential(DifferentialPointsCalculationRequest $request) {
        return $this->differential->calculate($request);
    }

    public function metrical(MetricalPointsCalculationRequest $request) {
        return $this->metrical->calculate($request);
    }
}
