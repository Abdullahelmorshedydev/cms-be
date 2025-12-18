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

    /**
     * Provide a fully-hydrated fallback analytics array when analytics fetching fails.
     */
    protected function getDefaultAnalytics(): array
    {
        $zeroStatus = [
            'total' => 0,
            'active' => 0,
            'inactive' => 0,
            'this_month' => 0,
        ];

        return [
            // Core
            'users' => array_merge($zeroStatus, ['admins' => 0]),
            'students' => array_merge($zeroStatus, [
                'with_parents' => 0,
                'without_parents' => 0,
                'male' => 0,
                'female' => 0,
            ]),
            'parents' => array_merge($zeroStatus, [
                'with_children' => 0,
                'without_children' => 0,
                'fathers' => 0,
                'mothers' => 0,
                'guardians' => 0,
            ]),
            'blogs' => array_merge($zeroStatus, [
                'published' => 0,
                'draft' => 0,
            ]),
            'comments' => [
                'total' => 0,
                'active' => 0,
                'pending' => 0,
                'this_month' => 0,
            ],
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
            'roles' => ['total' => 0],

            // E-learning
            'courses' => [
                'total' => 0,
                'published' => 0,
                'featured' => 0,
                'this_month' => 0,
                'by_category' => [],
                'by_level' => [],
            ],
            'enrollments' => [
                'total' => 0,
                'active' => 0,
                'completed' => 0,
                'this_month' => 0,
                'average_progress' => 0,
            ],
            'quizzes' => [
                'total' => 0,
                'published' => 0,
                'this_month' => 0,
                'total_attempts' => 0,
            ],
            'assignments' => [
                'total' => 0,
                'published' => 0,
                'overdue' => 0,
                'this_month' => 0,
                'total_submissions' => 0,
            ],
            'exams' => [
                'total' => 0,
                'published' => 0,
                'final' => 0,
                'this_month' => 0,
                'total_attempts' => 0,
            ],

            // CRM
            'crm_leads' => [
                'total' => 0,
                'converted' => 0,
                'this_month' => 0,
                'by_status' => [],
                'by_source' => [],
            ],
            'crm_contacts' => [
                'total' => 0,
                'this_month' => 0,
                'by_type' => [],
            ],
            'crm_deals' => [
                'total' => 0,
                'won' => 0,
                'lost' => 0,
                'open' => 0,
                'total_value' => 0,
                'won_value' => 0,
                'by_stage' => [],
            ],
            'crm_calls' => [
                'total' => 0,
                'this_month' => 0,
                'today' => 0,
            ],
            'crm_activities' => [
                'total' => 0,
                'overdue' => 0,
                'this_month' => 0,
            ],
            'crm_transactions' => [
                'total' => 0,
                'completed' => 0,
                'pending' => 0,
                'total_amount' => 0,
                'by_type' => [],
            ],

            // HRMS
            'employees' => array_merge($zeroStatus, []),
            'attendance' => [
                'today' => 0,
                'this_month' => 0,
            ],
            'payroll' => [
                'total' => 0,
                'this_month' => 0,
            ],

            // ERP
            'accounts' => [
                'total' => 0,
                'this_month' => 0,
            ],
            'expenses' => [
                'total' => 0,
                'this_month' => 0,
                'total_amount' => 0,
                'this_month_amount' => 0,
            ],
            'revenues' => [
                'total' => 0,
                'this_month' => 0,
                'total_amount' => 0,
                'this_month_amount' => 0,
            ],

            // Recent lists
            'recent_forms' => [],
            'recent_blogs' => [],
            'recent_users' => [],
            'recent_students' => [],
            'recent_parents' => [],
            'recent_courses' => [],
            'recent_enrollments' => [],
            'recent_leads' => [],

            // Charts
            'monthly_stats' => [
                'labels' => [],
                'users' => [],
                'students' => [],
                'parents' => [],
                'blogs' => [],
                'forms' => [],
            ],
        ];
    }
}
