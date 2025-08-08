<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TrackLogService;

class TrackLogsController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth')->only(['store', 'update', 'destroy']);
    }

    public function getResponse(): JsonResponse
    {
        return response()->json($this->response);
    }

    public function index(Request $request, $typeId, $trackable_item_id)
    {
        $userId = $request->user()->id;
        return (new TrackLogService())
            ->getTrackLogs($userId, $typeId, $trackable_item_id)
            ->getResponse();
    }

    public function store(Request $request, $typeId, $trackable_item_id)
    {
        return (new TrackLogService())
            ->store($request, $typeId, $trackable_item_id)
            ->getResponse();
    }

    public function update(Request $request, $typeId, $trackable_item_id, $track_log_id)
    {
        return (new TrackLogService())
            ->update(auth()->id(), $request, $typeId, $trackable_item_id, $track_log_id)
            ->getResponse();
    }

    public function destroy(Request $request, $typeId, $trackable_item_id)
    {
        $userId = $request->user()->id;
        return (new TrackLogService())
            ->destroy($userId, $typeId, $trackable_item_id)
            ->getResponse();
    }

    public function show($trackable_item_id, $typeId, $track_log_id)
    {
        return (new TrackLogService())
            ->show($trackable_item_id, $typeId, $track_log_id)
            ->getResponse();
    }
}
