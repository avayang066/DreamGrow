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
        return (new TrackLogService($request))
            ->getTrackLogs( $typeId, $trackable_item_id)
            ->getResponse();
    }

    public function store(Request $request, $typeId, $trackable_item_id)
    {
        return (new TrackLogService($request))
            ->runValidate(['create'])
            ->store($request, $typeId, $trackable_item_id)
            ->getResponse();
    }

    public function update(Request $request, $typeId, $trackable_item_id, $track_log_id)
    {
        return (new TrackLogService($request))
            ->runValidate(methods: ['update'])
            ->update(auth()->id(), $request, $typeId, $trackable_item_id, $track_log_id)
            ->getResponse();
    }

    public function destroy(Request $request, $type_id, $trackable_item_id, $track_log_id)
    {
        return (new TrackLogService($request))
            ->destroy($type_id, $trackable_item_id, $track_log_id)
            ->getResponse();
    }

    public function show(Request $request, $typeId, $trackable_item_id, $track_log_id)
    {
        return (new TrackLogService($request))
            ->show($typeId, $trackable_item_id, $track_log_id)
            ->getLogsByDate($typeId, $trackable_item_id, $track_log_id)
            ->getResponse();
    }

    public function getLogsByDate(Request $request, $typeId, $trackable_item_id)
    {
        return (new TrackLogService($request))
            ->getLogsByDate($request,$typeId, $trackable_item_id)
            ->getResponse();
    }

}
