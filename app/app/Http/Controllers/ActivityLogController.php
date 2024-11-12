<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class ActivityLogController extends Controller
{
    public $activityLogServices;

    public function __construct()
    {
        $this->activityLogServices = new ActivityLogService();
    }
    public function index() {

    }

    public function show($id) {
        return response()->json($this->activityLogServices->getActivityLogs($id));
    }
}
