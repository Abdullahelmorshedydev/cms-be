<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\FormTypeEnum;
use App\Enums\StatusEnum;
use App\Http\Controllers\Dashboard\BaseDashboardController;
use App\Http\Requests\FormEmail\StoreFormEmailRequest;
use App\Http\Requests\FormEmail\UpdateFormEmailRequest;
use App\Services\FormEmailService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FormEmailController extends BaseDashboardController
{
    public function __construct(
        protected FormEmailService $service
    ) {
    }

    /**
     * Display all form email recipients
     */
    public function index()
    {
        try {
            $formEmails = $this->service->getAllPaginated();

            // Handle pagination if it's a paginator
            $formEmailsPaginated = ($formEmails instanceof \Illuminate\Pagination\LengthAwarePaginator)
                ? $formEmails
                : $this->extractPaginatedData(['data' => ['data' => $formEmails ?? []]]);

            return view('admin.pages.form-emails.index', [
                'formEmails' => $formEmailsPaginated,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading form emails', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->handleError($e, 'dashboard.pages.form-emails.index', [
                'formEmails' => $this->getEmptyPaginator(),
            ]);
        }
    }

    /**
     * Show create form
     */
    public function create()
    {
        try {
            return view('admin.pages.form-emails.create', [
                'types' => FormTypeEnum::toArray(),
                'status' => StatusEnum::cases(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading form email create page', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    /**
     * Store new form email recipient
     */
    public function store(StoreFormEmailRequest $request)
    {
        try {
            $response = $this->service->create($request->validated());

            $message = [
                'status' => $response['code'] == Response::HTTP_CREATED,
                'content' => $response['message']
            ];

            return redirect()->route('dashboard.form-emails.index')->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error storing form email', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.created_failed')])->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $response = $this->service->show('id', $id);

            // ModelNotFoundException will be handled by exception handler, so if we get here, record exists
            if (!isset($response['data']['record']) || !$response['data']['record']) {
                abort(404, __('custom.messages.not_found'));
            }

            return view('admin.pages.form-emails.edit', [
                'formEmail' => $response['data']['record'],
                'types' => FormTypeEnum::toArray(),
                'status' => StatusEnum::cases(),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Let ModelNotFoundException bubble up to be handled by exception handler (shows 404 page)
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error loading form email for edit', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => __('custom.messages.retrieved_failed')]);
        }
    }

    /**
     * Update form email recipient
     */
    public function update(UpdateFormEmailRequest $request, $id)
    {
        try {
            $response = $this->service->edit($id, $request->validated());

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error updating form email', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.updated_failed')])->withInput();
        }
    }

    /**
     * Delete form email recipient
     */
    public function destroy($id)
    {
        try {
            $response = $this->service->remove($id);

            $message = [
                'status' => $response['code'] == Response::HTTP_OK,
                'content' => $response['message']
            ];

            return back()->with('message', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting form email', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => __('custom.messages.deleted_failed')]);
        }
    }
}

