<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Form;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get all analytics data for dashboard
     */
    public function getDashboardAnalytics(): array
    {
        return [
            'forms' => $this->getFormsAnalytics(),
            'pages' => $this->getPagesAnalytics(),
            'recent_forms' => $this->getRecentForms(),
            'monthly_stats' => $this->getMonthlyStats(),
        ];
    }

    /**
     * Get forms analytics
     */
    protected function getFormsAnalytics(): array
    {
        $total = Form::count();
        $read = Form::where('is_read', true)->count();
        $unread = Form::where('is_read', false)->count();
        $thisMonth = Form::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Forms by type
        $byType = Form::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type->value => $item->total];
            })
            ->toArray();

        return [
            'total' => $total,
            'read' => $read,
            'unread' => $unread,
            'this_month' => $thisMonth,
            'by_type' => $byType,
        ];
    }

    /**
     * Get pages analytics
     */
    protected function getPagesAnalytics(): array
    {
        $total = Page::count();
        $active = Page::where('is_active', StatusEnum::ACTIVE)->count();
        $inactive = Page::where('is_active', StatusEnum::INACTIVE)->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
        ];
    }

    /**
     * Get recent forms
     */
    protected function getRecentForms(int $limit = 5)
    {
        return Form::with('emails')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($form) {
                return [
                    'id' => $form->id,
                    'name' => $form->display_name,
                    'email' => $form->email,
                    'type' => $form->type->lang(),
                    'type_value' => $form->type->value,
                    'is_read' => $form->is_read,
                    'created_at' => $form->created_at->diffForHumans(),
                    'created_at_formatted' => $form->created_at->format('Y-m-d H:i'),
                ];
            });
    }

    /**
     * Get monthly statistics for the last 6 months
     */
    protected function getMonthlyStats(): array
    {
        $data = [
            'forms' => [],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            $data['labels'][] = $month;

            $formsCount = Form::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data['forms'][] = $formsCount;
        }

        return $data;
    }
}

