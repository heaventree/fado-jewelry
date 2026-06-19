<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RoutingController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        // $this->
        // middleware('auth')->
        // except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->hasRole(['super_admin', 'store_admin', 'staff'])) {
            return redirect('/dashboards/index');
        }

        return redirect()->route('shop.home');
    }

    /**
     * Display a view based on first route param
     *
     * @return \Illuminate\Http\Response
     */
    public function root(Request $request, $first)
    {
        if (view()->exists($first)) {
            return view($first);
        }
        abort(404);
    }

    /**
     * second level route
     */
    public function secondLevel(Request $request, $first, $second)
    {
        $viewName = $first . '.' . $second;
        if (view()->exists($viewName)) {
            return view($viewName);
        }
        abort(404);
    }

    /**
     * third level route
     */
    public function thirdLevel(Request $request, $first, $second, $third)
    {
        $viewName = $first . '.' . $second . '.' . $third;
        if (view()->exists($viewName)) {
            return view($viewName);
        }
        abort(404);
    }
}
