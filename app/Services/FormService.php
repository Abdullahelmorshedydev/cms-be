<?php

namespace App\Services;

use App\Builders\FormBuilder;
use App\Enums\FormTypeEnum;
use App\Enums\StatusEnum;
use App\Repositories\FormRepository;
use App\Jobs\SendFormNotificationJob;
use App\Models\Form;
use App\Models\FormEmail;
use Symfony\Component\HttpFoundation\Response;

class FormService extends BaseService
{
    public function __construct(FormRepository $repository, protected FormBuilder $builder)
    {
        parent::__construct($repository);
    }

    /**
     * Get all forms with pagination and filters
     */
    public function getAllPaginated($data, $with = [], $columns = ['*'], $order = ['created_at' => 'DESC'], $limit = 15)
    {
        // Build filters array
        $filters = [];

        // Type filter
        if (!empty($data['type'])) {
            $filters['type'] = $data['type'];
        }

        // Read status filter
        if (isset($data['is_read'])) {
            $filters['is_read'] = $data['is_read'];
        }

        // Active status filter
        if (isset($data['is_active'])) {
            $filters['is_active'] = $data['is_active'];
        }

        // Date range filters
        if (!empty($data['date_from'])) {
            $filters['created_at'] = [
                'operator' => '>=',
                'value' => $data['date_from']
            ];
        }

        if (!empty($data['date_to'])) {
            $filters['created_at'] = [
                'operator' => '<=',
                'value' => $data['date_to']
            ];
        }

        // Handle search using the advanced search method from BaseRepository
        if (!empty($data['search'])) {
            $searchColumns = ['name', 'first_name', 'last_name', 'email', 'company', 'subject', 'message'];
            return $this->repository->search($data['search'], $searchColumns, $filters, $limit ?? 15);
        }

        // Use repository method directly for pagination
        return $this->repository->findByWith($filters, $columns, $with, $order, $limit ?? 15);
    }

    /**
     * Show single form
     */
    public function show($key, $value, $with = [])
    {
        $form = $this->repository->findOneBy([$key => $value]);

        // Mark as read when viewed
        if (!$form->is_read) {
            $form->markAsRead();
        }

        return returnData(
            [],
            Response::HTTP_OK,
            $this->builder->show($form),
            __('custom.messages.retrieved_success')
        );
    }

    /**
     * Show single form by ID (convenience method)
     */
    public function showById($id)
    {
        return $this->show('id', $id);
    }

    /**
     * Submit a new form from frontend
     */
    public function submit(array $data, callable $callback = null)
    {
        // Prepare form data
        $formData = $this->prepareFormData($data);

        // Create form submission using parent method
        return $this->store($formData, function ($form) use ($callback) {
            // Send email notifications asynchronously
            $this->sendNotifications($form);

            // Execute additional callback from controller if provided
            if ($callback) {
                $callback($form);
            }
        });
    }

    /**
     * Prepare form data before saving
     */
    protected function prepareFormData(array $data): array
    {
        return array_merge($data, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'is_read' => false,
            'is_active' => StatusEnum::ACTIVE->value,
        ]);
    }

    /**
     * Mark form as read
     */
    public function markAsRead($id)
    {
        $form = $this->repository->find($id);
        $form->markAsRead();

        return returnData(
            [],
            Response::HTTP_OK,
            ['form' => $form],
            __('custom.messages.updated_success')
        );
    }

    /**
     * Mark form as unread
     */
    public function markAsUnread($id)
    {
        $form = $this->repository->find($id);
        $form->markAsUnread();

        return returnData(
            [],
            Response::HTTP_OK,
            ['form' => $form],
            __('custom.messages.updated_success')
        );
    }


    /**
     * Bulk mark as read (optimized with single query)
     */
    public function bulkMarkAsRead(array $ids)
    {
        // Use bulk update for better performance
        $this->repository->bulkUpdate(
            ['id' => ['operator' => 'in', 'value' => $ids]],
            [
                'is_read' => true,
                'read_at' => now()
            ]
        );

        return returnData(
            [],
            Response::HTTP_OK,
            [],
            __('custom.messages.updated_success')
        );
    }

    /**
     * Get form statistics (optimized with criteria-based counting)
     * Override parent method to add form-specific statistics
     */
    public function getStatistics(array $criteria = [])
    {
        $total = $this->repository->count($criteria);
        $unreadCriteria = array_merge($criteria, ['is_read' => false]);
        $readCriteria = array_merge($criteria, ['is_read' => true]);

        $unread = $this->repository->count($unreadCriteria);
        $read = $this->repository->count($readCriteria);

        $byType = [];
        foreach (FormTypeEnum::cases() as $type) {
            $typeCriteria = array_merge($criteria, ['type' => $type->value]);
            $byType[$type->slug()] = [
                'label' => $type->lang(),
                'count' => $this->repository->count($typeCriteria),
                'icon' => $type->icon(),
                'color' => $type->color(),
            ];
        }

        return [
            'total' => $total,
            'unread' => $unread,
            'read' => $read,
            'by_type' => $byType,
        ];
    }

    /**
     * Send email notifications to configured recipients
     */
    protected function sendNotifications(Form $form): void
    {
        // Get all active emails that should receive this form type
        $recipients = FormEmail::active()
            ->receivingFormType($form->type)
            ->get();

        // Dispatch email job for each recipient
        foreach ($recipients as $recipient) {
            SendFormNotificationJob::dispatch($form, $recipient);
        }
    }

    /**
     * Export forms to CSV
     * Override parent method for form-specific export
     */
    public function export(array $criteria = [], array $columns = ['*'])
    {
        // Get forms using repository directly (no pagination for export)
        $forms = $this->repository->findByWith($criteria, $columns, [], ['created_at' => 'DESC']);

        $filename = 'forms_export_' . now()->format('Y_m_d_His') . '.csv';
        $filePath = storage_path('app/exports/' . $filename);

        // Create exports directory if it doesn't exist
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $file = fopen($filePath, 'w');

        // Add CSV headers
        fputcsv($file, [
            'ID',
            'Type',
            'Name',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Company',
            'Subject',
            'Message',
            'Read',
            'Date',
        ]);

        // Add form data
        foreach ($forms as $form) {
            fputcsv($file, [
                $form->id,
                $form->type->lang(),
                $form->name,
                $form->first_name,
                $form->last_name,
                $form->email,
                $form->phone,
                $form->company,
                $form->subject,
                $form->message,
                $form->is_read ? 'Yes' : 'No',
                $form->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);

        return $filePath;
    }
}
