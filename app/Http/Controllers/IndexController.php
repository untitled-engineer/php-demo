<?php

namespace App\Http\Controllers;

use App\Repositories\PlacesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Lumen\Application;

class IndexController extends Controller
{

    /** @var PlacesRepository  */
    protected PlacesRepository $placesRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PlacesRepository $placesRepository)
    {
        $this->placesRepository = $placesRepository;
    }

    /**
     * @return View|Application
     */
    public function index(){

        return view('greeting', [
            'name' => 'World!',
            'places' => $this->placesRepository->getAllPlaces(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function placeInfo(Request $request): JsonResponse
    {
        return response()->json(
            $this->placesRepository->getNearestPlaces(
                $request->input('place'),
                $request->input('range'),
            )
        );
    }
}
