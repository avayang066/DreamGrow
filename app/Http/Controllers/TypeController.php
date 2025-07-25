<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TypeService;

class TypeController extends Controller
{
    public function createTypeForUser(Request $request)
    {
        $userId = auth()->id();
        return (new TypeService())
            ->createTypeForUser($userId,$request->all());
    }

    public function getType(Request $request, $id)
    {
        return (new TypeService())
            ->getType($id);
    }
}
