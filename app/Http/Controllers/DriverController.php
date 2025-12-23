<?php

namespace App\Http\Controllers;

use App\Services\LoadService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function __construct(private LoadService $loadService)
    {
        $this->middleware(['role:driver']);
    }

    public function index(Request $request): View
    {
        $loads = $this->loadService->getDriverLoads($request->user()->id);
        
        return view('driver.index', compact('loads'));
    }
}
