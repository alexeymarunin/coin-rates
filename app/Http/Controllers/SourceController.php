<?php

namespace App\Http\Controllers;

use App\Http\Resources\SourceResource;
use App\Source;
use Illuminate\Http\Request;

/**
 * Класс SourceController
 *
 * @package App\Http\Controllers
 */
class SourceController extends Controller
{
    public function index(Source $source)
    {
        return SourceResource::collection($source->paginate());
    }

    public function view(Source $source)
    {
        return SourceResource::make($source);
    }
}
