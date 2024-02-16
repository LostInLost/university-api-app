<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\City;
use App\Models\Students;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index()
    {
        // DB::enableQueryLog();

        $studentByCity = City::select('name')->whereHas('students')->withCount('students as value')->get(['name']);
        $studentByGender = Students::select('gender', DB::raw('COUNT(id) as total'))->groupBy('gender')->get();
        $studentByYear = Students::select(DB::raw('COUNT(id) as total, YEAR(born_date) as born'))
            ->groupBy(DB::raw('YEAR(born_date)'))
            ->orderBy(DB::raw('YEAR(born_date)'))
            ->get();

        $arrayGender = [
            'labels' => [],
            'data' => [],
        ];

        $arrayCity = [
            'labels' => [],
            'data' => [],
        ];

        $studentByYearFinal = [
            'labels' => [],
            'data' => []
        ];

        // return $this->responseError();
        foreach ($studentByGender as $gender) {
            $finalName = $gender['gender'] === 'L' ? 'Male' : 'Female';
            array_push($arrayGender['labels'], $finalName);
            array_push($arrayGender['data'], $gender['total']);
        }

        foreach ($studentByCity as $city) {
            $finalName = $city['name'];
            array_push($arrayCity['labels'], $finalName);
            array_push($arrayCity['data'], $city['value']);
        }

        foreach ($studentByYear as $year) {
            array_push($studentByYearFinal['labels'], $year['born']);
            array_push($studentByYearFinal['data'], $year['total']);
        }

        // dd(DB::getQueryLog());

        return $this->responseSuccess([
            'studentByCity' => $arrayCity,
            'studentByGender' => [
                'labels' => $arrayGender['labels'],
                'data' => $arrayGender['data'],
            ],
            'studentByYear' => $studentByYearFinal,
            // 'tes' => []
        ]);

        // return $this->responseSuccess('Tes API');
    }
}
