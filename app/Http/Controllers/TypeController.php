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
        $userId = $request->user()->id;
        return (new TypeService())
            ->getType($userId)
            ->getResponse();
    }

    public function store(Request $request)
    {
        $userId = auth()->id();
        return (new TypeService())
            // ->runValidate(['store'])
            ->store($userId, $request->all())
            ->getResponse();
    }

    public function update(Request $request, $typeId)
    {
        return (new TypeService())
            ->update(auth()->id(), $typeId, $request->all())
            ->getResponse();
    }

    public function destroy(Request $request, $typeId)
    {
        $userId = $request->user()->id;
        return (new TypeService())
            ->destroy($userId, $typeId)
            ->getResponse();
    }

    public function show($typeId)
    {
        $userId = auth()->id();
        return (new TypeService())
            ->show($userId, $typeId)
            ->getResponse();
    }

}
