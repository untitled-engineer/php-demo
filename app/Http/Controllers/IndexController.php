<?php

namespace App\Http\Controllers;

use App\Repositories\PlacesRepository;

class IndexController
    extends Controller
{

    protected $placesRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PlacesRepository $placesRepository)
    {
        $this->placesRepository = $placesRepository;
    }

    //
    public function index(){

        return view('greeting', [
            'name' => 'World!',
            'places' => $this->placesRepository->getAllPlaces(),
        ]);
    }
}
