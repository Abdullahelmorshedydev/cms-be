<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            return view('admin.index' . [
                'analytics' => $this->getDefaultAnalytics()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading dashboard analytics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('admin.index', [
                'analytics' => $this->getDefaultAnalytics(),
                'error' => __('custom.messages.retrieved_failed'),
            ]);
        }
    }

    protected function getDefaultAnalytics(): array
    {
        return [
            'forms' => [
                'total' => 0,
                'read' => 0,
                'unread' => 0,
                'this_month' => 0,
                'by_type' => [],
            ],
            'pages' => [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
            ],
            'recent_forms' => [],
            'monthly_stats' => [
                'forms' => [],
            ],
        ];
    }
}
