<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\TrackableItemService;

class TrackableItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'store', 'update', 'destroy']);
    }

    public function getResponse()
    {
        return response()->json($this->response);
    }

    public function index(Request $request, $typeId)
    {
        return (new TrackableItemService($request))
            ->getTrackableItem($typeId)
            ->getResponse();
    }

    public function store(Request $request, $typeId)
    {
        return (new TrackableItemService($request))
            ->runValidate(['create'])
            ->store($request, $typeId)
            ->getResponse();
    }

    public function update(Request $request, $typeId, $trackable_item_id)
    {
        return (new TrackableItemService($request))
            ->runValidate(['update'])
            ->update($request, $typeId, $trackable_item_id)
            ->getResponse();
    }

    public function destroy(Request $request, $typeId, $trackable_item_id)
    {
        $userId = $request->user()->id;
        return (new TrackableItemService($request))
            ->destroy($userId, $typeId, $trackable_item_id)
            ->getResponse();
    }

    public function show(Request $request, $typeId, $trackable_item_id)
    {
        return (new TrackableItemService($request))
            ->show($typeId, $trackable_item_id)
            ->getResponse();
    }

}
