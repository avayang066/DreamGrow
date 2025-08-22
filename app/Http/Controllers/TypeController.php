<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TypeService;

class TypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    public function getResponse()
    {
        return response()->json($this->response);
    }

    public function index(Request $request)
    {
        return (new TypeService($request))
            ->getType()
            ->getResponse();
    }

    public function store(Request $request)
    {
        return (new TypeService($request))
            ->runValidate(['name'])
            ->store()
            ->getResponse();
    }

    public function update(Request $request, $typeId)
    {
        return (new TypeService($request))
            ->runValidate(['name'])
            ->update($typeId)
            ->getResponse();
    }

    public function destroy(Request $request, $typeId)
    {
        return (new TypeService($request))
            ->destroy($typeId)
            ->getResponse();
    }

    public function show(Request $request, $typeId)
    {
        return (new TypeService($request))
            ->show($typeId)
            ->getResponse();
    }

}
