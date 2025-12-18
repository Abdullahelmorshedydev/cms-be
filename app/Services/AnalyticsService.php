<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Enums\EnrollmentStatusEnum;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\CrmActivity;
use App\Models\CrmCall;
use App\Models\CrmContact;
use App\Models\CrmDeal;
use App\Models\CrmLead;
use App\Models\CrmTransaction;
use App\Models\Exam;
use App\Models\Form;
use App\Models\Page;
use App\Models\Quiz;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get all analytics data for dashboard
     */
    public function getDashboardAnalytics(): array
    {
        return [
            // Core Analytics
            'users' => $this->getUsersAnalytics(),
            'students' => $this->getStudentsAnalytics(),
            'parents' => $this->getParentsAnalytics(),
            'blogs' => $this->getBlogsAnalytics(),
            'comments' => $this->getCommentsAnalytics(),
            'forms' => $this->getFormsAnalytics(),
            'pages' => $this->getPagesAnalytics(),
            'roles' => $this->getRolesAnalytics(),

            // E-Learning Analytics
            'courses' => $this->getCoursesAnalytics(),
            'enrollments' => $this->getEnrollmentsAnalytics(),
            'quizzes' => $this->getQuizzesAnalytics(),
            'assignments' => $this->getAssignmentsAnalytics(),
            'exams' => $this->getExamsAnalytics(),

            // CRM Analytics
            'crm_leads' => $this->getCrmLeadsAnalytics(),
            'crm_contacts' => $this->getCrmContactsAnalytics(),
            'crm_deals' => $this->getCrmDealsAnalytics(),
            'crm_calls' => $this->getCrmCallsAnalytics(),
            'crm_activities' => $this->getCrmActivitiesAnalytics(),
            'crm_transactions' => $this->getCrmTransactionsAnalytics(),

            // HRMS Analytics
            'employees' => $this->getEmployeesAnalytics(),
            'attendance' => $this->getAttendanceAnalytics(),
            'payroll' => $this->getPayrollAnalytics(),

            // ERP Analytics
            'accounts' => $this->getAccountsAnalytics(),
            'expenses' => $this->getExpensesAnalytics(),
            'revenues' => $this->getRevenuesAnalytics(),

            // Recent Items
            'recent_forms' => $this->getRecentForms(),
            'recent_blogs' => $this->getRecentBlogs(),
            'recent_users' => $this->getRecentUsers(),
            'recent_students' => $this->getRecentStudents(),
            'recent_parents' => $this->getRecentParents(),
            'recent_courses' => $this->getRecentCourses(),
            'recent_enrollments' => $this->getRecentEnrollments(),
            'recent_leads' => $this->getRecentLeads(),

            // Monthly Statistics
            'monthly_stats' => $this->getMonthlyStats(),
        ];
    }

    /**
     * Get users analytics
     */
    protected function getUsersAnalytics(): array
    {
        $total = User::count();
        $active = User::where('is_active', StatusEnum::ACTIVE)->count();
        $inactive = User::where('is_active', StatusEnum::INACTIVE)->count();
        $admins = User::where('is_admin', true)->count();
        $thisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'admins' => $admins,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get students analytics
     */
    protected function getStudentsAnalytics(): array
    {
        $total = User::where('user_type', UserTypeEnum::STUDENT->value)->count();
        $active = User::where('user_type', UserTypeEnum::STUDENT->value)->where('is_active', StatusEnum::ACTIVE)->count();
        $inactive = User::where('user_type', UserTypeEnum::STUDENT->value)->where('is_active', StatusEnum::INACTIVE)->count();
        $withParents = User::where('user_type', UserTypeEnum::STUDENT->value)->whereNotNull('parent_id')->count();
        $withoutParents = User::where('user_type', UserTypeEnum::STUDENT->value)->whereNull('parent_id')->count();
        $male = User::where('user_type', UserTypeEnum::STUDENT->value)->where('gender', 1)->count();
        $female = User::where('user_type', UserTypeEnum::STUDENT->value)->where('gender', 2)->count();
        $thisMonth = User::where('user_type', UserTypeEnum::STUDENT->value)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'with_parents' => $withParents,
            'without_parents' => $withoutParents,
            'male' => $male,
            'female' => $female,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get parents analytics
     */
    protected function getParentsAnalytics(): array
    {
        $total = User::where('user_type', UserTypeEnum::PARENT->value)->count();
        $active = User::where('user_type', UserTypeEnum::PARENT->value)->where('is_active', StatusEnum::ACTIVE)->count();
        $inactive = User::where('user_type', UserTypeEnum::PARENT->value)->where('is_active', StatusEnum::INACTIVE)->count();
        $withChildren = User::where('user_type', UserTypeEnum::PARENT->value)->has('children')->count();
        $withoutChildren = User::where('user_type', UserTypeEnum::PARENT->value)->doesntHave('children')->count();
        $fathers = User::where('user_type', UserTypeEnum::PARENT->value)->where('relationship_to_student', 'father')->count();
        $mothers = User::where('user_type', UserTypeEnum::PARENT->value)->where('relationship_to_student', 'mother')->count();
        $guardians = User::where('user_type', UserTypeEnum::PARENT->value)->where('relationship_to_student', 'guardian')->count();
        $thisMonth = User::where('user_type', UserTypeEnum::PARENT->value)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'with_children' => $withChildren,
            'without_children' => $withoutChildren,
            'fathers' => $fathers,
            'mothers' => $mothers,
            'guardians' => $guardians,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get blogs analytics
     */
    protected function getBlogsAnalytics(): array
    {
        $total = Blog::count();
        $active = Blog::where('is_active', StatusEnum::ACTIVE)->count();
        $inactive = Blog::where('is_active', StatusEnum::INACTIVE)->count();
        $published = Blog::where('published_at', '<=', now())->count();
        $draft = Blog::where('published_at', '>', now())
            ->orWhereNull('published_at')
            ->count();
        $thisMonth = Blog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'published' => $published,
            'draft' => $draft,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get comments analytics
     */
    protected function getCommentsAnalytics(): array
    {
        $total = BlogComment::count();
        $active = BlogComment::where('is_active', StatusEnum::ACTIVE)->count();
        $pending = BlogComment::where('is_active', StatusEnum::INACTIVE)->count();
        $thisMonth = BlogComment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'this_month' => $thisMonth,
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
     * Get roles analytics
     */
    protected function getRolesAnalytics(): array
    {
        $total = Role::count();

        return [
            'total' => $total,
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
     * Get recent blogs
     */
    protected function getRecentBlogs(int $limit = 5)
    {
        return Blog::with('creator')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->getTranslation('title', app()->getLocale()),
                    'creator' => $blog->creator->name ?? 'N/A',
                    'is_active' => $blog->is_active->value,
                    'published_at' => $blog->published_at ? $blog->published_at->format('Y-m-d') : 'Draft',
                    'created_at' => $blog->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Get recent users
     */
    protected function getRecentUsers(int $limit = 5)
    {
        return User::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active->value,
                    'is_admin' => $user->is_admin,
                    'created_at' => $user->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Get recent students
     */
    protected function getRecentStudents(int $limit = 5)
    {
        return User::where('user_type', UserTypeEnum::STUDENT->value)
            ->with('parent:id,name,email')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'student_id' => $student->student_id,
                    'grade' => $student->grade,
                    'class' => $student->class,
                    'parent_name' => $student->parent ? $student->parent->name : 'N/A',
                    'is_active' => $student->is_active->value,
                    'created_at' => $student->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Get recent parents
     */
    protected function getRecentParents(int $limit = 5)
    {
        return User::where('user_type', UserTypeEnum::PARENT->value)
            ->withCount('children')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($parent) {
                return [
                    'id' => $parent->id,
                    'name' => $parent->name,
                    'email' => $parent->email,
                    'relationship_to_student' => $parent->relationship_to_student ?? 'N/A',
                    'children_count' => $parent->children_count,
                    'is_active' => $parent->is_active->value,
                    'created_at' => $parent->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Get monthly statistics for the last 6 months
     */
    protected function getMonthlyStats(): array
    {
        $months = [];
        $data = [
            'labels' => [],
            'users' => [],
            'students' => [],
            'parents' => [],
            'blogs' => [],
            'forms' => [],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            $data['labels'][] = $month;

            // Users count for this month
            $usersCount = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data['users'][] = $usersCount;

            // Students count for this month
            $studentsCount = User::where('user_type', UserTypeEnum::STUDENT->value)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data['students'][] = $studentsCount;

            // Parents count for this month
            $parentsCount = User::where('user_type', UserTypeEnum::PARENT->value)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data['parents'][] = $parentsCount;

            // Blogs count for this month
            $blogsCount = Blog::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data['blogs'][] = $blogsCount;

            // Forms count for this month
            $formsCount = Form::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $data['forms'][] = $formsCount;
        }

        return $data;
    }

    // ==========================================
    // E-LEARNING ANALYTICS
    // ==========================================

    /**
     * Get courses analytics
     */
    protected function getCoursesAnalytics(): array
    {
        $total = Course::count();
        $published = Course::where('is_published', true)->count();
        $featured = Course::where('is_featured', true)->count();
        $thisMonth = Course::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Courses by category
        $byCategory = Course::select('category_id', DB::raw('count(*) as total'))
            ->with('category:id')
            ->groupBy('category_id')
            ->get()
            ->mapWithKeys(function ($item) {
                $categoryName = $item->category ? $item->category->getTranslation('name', app()->getLocale()) : 'Uncategorized';
                return [$categoryName => $item->total];
            })
            ->toArray();

        // Courses by level
        $byLevel = Course::select('level', DB::raw('count(*) as total'))
            ->groupBy('level')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->level->value => $item->total];
            })
            ->toArray();

        return [
            'total' => $total,
            'published' => $published,
            'featured' => $featured,
            'this_month' => $thisMonth,
            'by_category' => $byCategory,
            'by_level' => $byLevel,
        ];
    }

    /**
     * Get enrollments analytics
     */
    protected function getEnrollmentsAnalytics(): array
    {
        $total = CourseEnrollment::count();
        $active = CourseEnrollment::whereIn('status', [
            EnrollmentStatusEnum::ENROLLED,
            EnrollmentStatusEnum::IN_PROGRESS
        ])->count();
        $completed = CourseEnrollment::where('status', EnrollmentStatusEnum::COMPLETED)->count();
        $thisMonth = CourseEnrollment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Average progress
        $avgProgress = CourseEnrollment::whereNotNull('progress_percentage')
            ->avg('progress_percentage') ?? 0;

        return [
            'total' => $total,
            'active' => $active,
            'completed' => $completed,
            'this_month' => $thisMonth,
            'average_progress' => round($avgProgress, 2),
        ];
    }

    /**
     * Get quizzes analytics
     */
    protected function getQuizzesAnalytics(): array
    {
        $total = Quiz::count();
        $published = Quiz::where('is_published', true)->count();
        $thisMonth = Quiz::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total attempts
        $totalAttempts = DB::table('student_quiz_attempts')->count();

        return [
            'total' => $total,
            'published' => $published,
            'this_month' => $thisMonth,
            'total_attempts' => $totalAttempts,
        ];
    }

    /**
     * Get assignments analytics
     */
    protected function getAssignmentsAnalytics(): array
    {
        $total = Assignment::count();
        $published = Assignment::where('is_published', true)->count();
        $overdue = Assignment::where('due_date', '<', now())->count();
        $thisMonth = Assignment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total submissions
        $totalSubmissions = AssignmentSubmission::count();

        return [
            'total' => $total,
            'published' => $published,
            'overdue' => $overdue,
            'this_month' => $thisMonth,
            'total_submissions' => $totalSubmissions,
        ];
    }

    /**
     * Get exams analytics
     */
    protected function getExamsAnalytics(): array
    {
        $total = Exam::count();
        $published = Exam::where('is_published', true)->count();
        $final = Exam::where('is_final', true)->count();
        $thisMonth = Exam::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total attempts
        $totalAttempts = DB::table('student_exam_attempts')->count();

        return [
            'total' => $total,
            'published' => $published,
            'final' => $final,
            'this_month' => $thisMonth,
            'total_attempts' => $totalAttempts,
        ];
    }

    // ==========================================
    // CRM ANALYTICS
    // ==========================================

    /**
     * Get CRM leads analytics
     */
    protected function getCrmLeadsAnalytics(): array
    {
        $total = CrmLead::count();
        $converted = CrmLead::whereNotNull('converted_at')->count();
        $thisMonth = CrmLead::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Leads by status
        $byStatus = CrmLead::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status->value => $item->total];
            })
            ->toArray();

        // Leads by source
        $bySource = CrmLead::select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->source->value => $item->total];
            })
            ->toArray();

        return [
            'total' => $total,
            'converted' => $converted,
            'this_month' => $thisMonth,
            'by_status' => $byStatus,
            'by_source' => $bySource,
        ];
    }

    /**
     * Get CRM contacts analytics
     */
    protected function getCrmContactsAnalytics(): array
    {
        $total = CrmContact::count();
        $thisMonth = CrmContact::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Contacts by type
        $byType = CrmContact::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type->value => $item->total];
            })
            ->toArray();

        return [
            'total' => $total,
            'this_month' => $thisMonth,
            'by_type' => $byType,
        ];
    }

    /**
     * Get CRM deals analytics
     */
    protected function getCrmDealsAnalytics(): array
    {
        $total = CrmDeal::count();
        $won = CrmDeal::where('stage', \App\Enums\DealStage::CLOSED_WON)->count();
        $lost = CrmDeal::where('stage', \App\Enums\DealStage::CLOSED_LOST)->count();
        $open = CrmDeal::whereNotIn('stage', [
            \App\Enums\DealStage::CLOSED_WON,
            \App\Enums\DealStage::CLOSED_LOST
        ])->count();

        // Total value
        $totalValue = CrmDeal::sum('final_value') ?? 0;
        $wonValue = CrmDeal::where('stage', \App\Enums\DealStage::CLOSED_WON)->sum('final_value') ?? 0;

        // Deals by stage
        $byStage = CrmDeal::select('stage', DB::raw('count(*) as total'))
            ->groupBy('stage')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->stage->value => $item->total];
            })
            ->toArray();

        return [
            'total' => $total,
            'won' => $won,
            'lost' => $lost,
            'open' => $open,
            'total_value' => round($totalValue, 2),
            'won_value' => round($wonValue, 2),
            'by_stage' => $byStage,
        ];
    }

    /**
     * Get CRM calls analytics
     */
    protected function getCrmCallsAnalytics(): array
    {
        $total = CrmCall::count();
        $thisMonth = CrmCall::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Today's calls
        $today = CrmCall::whereDate('created_at', today())->count();

        return [
            'total' => $total,
            'this_month' => $thisMonth,
            'today' => $today,
        ];
    }

    /**
     * Get CRM activities analytics
     */
    protected function getCrmActivitiesAnalytics(): array
    {
        $total = CrmActivity::count();
        $overdue = CrmActivity::where('due_date', '<', now())
            ->where('completed_at', null)
            ->count();
        $thisMonth = CrmActivity::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'overdue' => $overdue,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get CRM transactions analytics
     */
    protected function getCrmTransactionsAnalytics(): array
    {
        $total = CrmTransaction::count();
        $completed = CrmTransaction::where('status', 'completed')->count();
        $pending = CrmTransaction::where('status', 'pending')->count();

        // Total amount
        $totalAmount = CrmTransaction::where('status', 'completed')->sum('amount') ?? 0;

        // Transactions by type
        $byType = CrmTransaction::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type => $item->total];
            })
            ->toArray();

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'total_amount' => round($totalAmount, 2),
            'by_type' => $byType,
        ];
    }

    // ==========================================
    // HRMS ANALYTICS
    // ==========================================

    /**
     * Get employees analytics
     */
    protected function getEmployeesAnalytics(): array
    {
        // Assuming employees are users with a specific type or role
        // Adjust based on your actual implementation
        $total = User::where('user_type', '!=', UserTypeEnum::STUDENT->value)
            ->where('user_type', '!=', UserTypeEnum::PARENT->value)
            ->count();
        $active = User::where('user_type', '!=', UserTypeEnum::STUDENT->value)
            ->where('user_type', '!=', UserTypeEnum::PARENT->value)
            ->where('is_active', StatusEnum::ACTIVE)
            ->count();
        $thisMonth = User::where('user_type', '!=', UserTypeEnum::STUDENT->value)
            ->where('user_type', '!=', UserTypeEnum::PARENT->value)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get attendance analytics
     */
    protected function getAttendanceAnalytics(): array
    {
        // Assuming there's an attendance model/table
        // Adjust based on your actual implementation
        $today = DB::table('session_attendances')
            ->whereDate('created_at', today())
            ->count();

        $thisMonth = DB::table('session_attendances')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'today' => $today,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get payroll analytics
     */
    protected function getPayrollAnalytics(): array
    {
        // Assuming there's a payroll model/table
        // Adjust based on your actual implementation
        $total = DB::table('payrolls')->count();
        $thisMonth = DB::table('payrolls')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'this_month' => $thisMonth,
        ];
    }

    // ==========================================
    // ERP ANALYTICS
    // ==========================================

    /**
     * Get accounts analytics
     */
    protected function getAccountsAnalytics(): array
    {
        // Assuming there's an accounts model/table
        // Adjust based on your actual implementation
        $total = DB::table('accounts')->count();
        $thisMonth = DB::table('accounts')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            'total' => $total,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Get expenses analytics
     */
    protected function getExpensesAnalytics(): array
    {
        // Assuming there's an expenses model/table
        // Adjust based on your actual implementation
        $total = DB::table('expenses')->count();
        $thisMonth = DB::table('expenses')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total amount
        $totalAmount = DB::table('expenses')->sum('amount') ?? 0;
        $thisMonthAmount = DB::table('expenses')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount') ?? 0;

        return [
            'total' => $total,
            'this_month' => $thisMonth,
            'total_amount' => round($totalAmount, 2),
            'this_month_amount' => round($thisMonthAmount, 2),
        ];
    }

    /**
     * Get revenues analytics
     */
    protected function getRevenuesAnalytics(): array
    {
        // Assuming there's a revenues model/table
        // Adjust based on your actual implementation
        $total = DB::table('revenues')->count();
        $thisMonth = DB::table('revenues')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Total amount
        $totalAmount = DB::table('revenues')->sum('amount') ?? 0;
        $thisMonthAmount = DB::table('revenues')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount') ?? 0;

        return [
            'total' => $total,
            'this_month' => $thisMonth,
            'total_amount' => round($totalAmount, 2),
            'this_month_amount' => round($thisMonthAmount, 2),
        ];
    }

    // ==========================================
    // RECENT ITEMS
    // ==========================================

    /**
     * Get recent courses
     */
    protected function getRecentCourses(int $limit = 5)
    {
        return Course::with('category', 'instructor')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->getTranslation('title', app()->getLocale()),
                    'category' => $course->category ? $course->category->getTranslation('name', app()->getLocale()) : 'N/A',
                    'instructor' => $course->instructor ? $course->instructor->name : 'N/A',
                    'is_published' => $course->is_published,
                    'created_at' => $course->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Get recent enrollments
     */
    protected function getRecentEnrollments(int $limit = 5)
    {
        return CourseEnrollment::with('student', 'course')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($enrollment) {
                return [
                    'id' => $enrollment->id,
                    'student_name' => $enrollment->student ? $enrollment->student->name : 'N/A',
                    'course_title' => $enrollment->course ? $enrollment->course->getTranslation('title', app()->getLocale()) : 'N/A',
                    'status' => $enrollment->status->value,
                    'progress' => $enrollment->progress_percentage ?? 0,
                    'created_at' => $enrollment->created_at->diffForHumans(),
                ];
            });
    }

    /**
     * Get recent leads
     */
    protected function getRecentLeads(int $limit = 5)
    {
        return CrmLead::with('assignedTo', 'courseInterest')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($lead) {
                return [
                    'id' => $lead->id,
                    'lead_number' => $lead->lead_number,
                    'name' => $lead->full_name,
                    'email' => $lead->email,
                    'status' => $lead->status->value,
                    'assigned_to' => $lead->assignedTo ? $lead->assignedTo->name : 'Unassigned',
                    'created_at' => $lead->created_at->diffForHumans(),
                ];
            });
    }
}

