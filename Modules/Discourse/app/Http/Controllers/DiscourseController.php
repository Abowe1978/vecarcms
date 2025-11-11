<?php

namespace Modules\Discourse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['message' => 'Discourse module is not implemented yet']);
    }
}
