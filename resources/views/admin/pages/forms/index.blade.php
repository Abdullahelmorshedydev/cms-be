@extends('admin.layouts.app')

@php
    $pageTitle = isset($currentType) && $currentType
        ? \App\Enums\FormTypeEnum::from($currentType)->lang()
        : __('custom.words.forms');
@endphp

@section('title', $pageTitle)

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">{{ $pageTitle }}</h4>
                <p class="text-muted mb-0">
                    @if(isset($currentType) && $currentType)
                        {{ __('custom.words.showing_forms_for_type') }}
                    @else
                        {{ __('custom.words.manage_all_form_submissions') }}
                    @endif
                </p>
            </div>
            @if(isset($currentType) && $currentType)
                <a href="{{ route('dashboard.forms.index') }}" class="btn btn-label-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i>
                    {{ __('custom.words.back_to_all_forms') }}
                </a>
            @endif
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">{{ __('custom.words.total') }}</h6>
                                <h3 class="mb-0">{{ $statistics['total'] }}</h3>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="mdi mdi-email-outline mdi-24px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">{{ __('custom.words.unread') }}</h6>
                                <h3 class="mb-0">{{ $statistics['unread'] }}</h3>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="mdi mdi-email-alert-outline mdi-24px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">{{ __('custom.words.read') }}</h6>
                                <h3 class="mb-0">{{ $statistics['read'] }}</h3>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="mdi mdi-email-check-outline mdi-24px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">{{ __('custom.words.today') }}</h6>
                                <h3 class="mb-0">{{ $forms->where('created_at', '>=', now()->startOfDay())->count() }}</h3>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="mdi mdi-calendar-today mdi-24px"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="row align-items-end">
                    <div class="col-md-12 d-flex align-items-end justify-content-between">
                        <h5 class="mb-0">
                            @if(isset($currentType) && $currentType)
                                <i class="{{ \App\Enums\FormTypeEnum::from($currentType)->icon() }} me-2"></i>
                                {{ \App\Enums\FormTypeEnum::from($currentType)->lang() }}
                            @else
                                {{ __('custom.words.forms') }}
                            @endif
                        </h5>
                        <div class="d-flex gap-2">
                            <form action="{{ route('dashboard.forms.export') }}" method="GET">
                                @foreach(request()->except('_token') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-download me-1"></i>
                                    {{ __('custom.words.export') }}
                                </button>
                            </form>
                            <a href="{{ route('dashboard.form-emails.index') }}" class="btn btn-info">
                                <i class="mdi mdi-email-settings me-1"></i>
                                {{ __('custom.words.email_recipients') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card-body">
                <form action="{{ route('dashboard.forms.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">{{ __('custom.words.search') }}</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="{{ __('custom.words.search') }}..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('custom.inputs.type') }}</label>
                            <select name="type" class="form-select">
                                <option value="">{{ __('custom.words.all') }}</option>
                                @foreach($types as $type)
                                    <option value="{{ $type['value'] }}"
                                            {{ request('type') == $type['value'] ? 'selected' : '' }}>
                                        {{ $type['lang'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('custom.words.read_status') }}</label>
                            <select name="is_read" class="form-select">
                                <option value="">{{ __('custom.words.all') }}</option>
                                <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>
                                    {{ __('custom.words.unread') }}
                                </option>
                                <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>
                                    {{ __('custom.words.read') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('custom.words.date_from') }}</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('custom.words.date_to') }}</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Forms Table -->
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="5%">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th width="5%">#</th>
                            <th width="15%">{{ __('custom.inputs.type') }}</th>
                            <th width="15%">{{ __('custom.inputs.name') }}</th>
                            <th width="15%">{{ __('custom.inputs.email') }}</th>
                            <th width="20%">{{ __('custom.inputs.subject') }}</th>
                            <th width="10%">{{ __('custom.words.status') }}</th>
                            <th width="10%">{{ __('custom.words.date') }}</th>
                            <th width="5%">{{ __('custom.words.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($forms as $form)
                            <tr class="{{ $form->is_read ? '' : 'table-active' }}">
                                <td>
                                    <input type="checkbox" class="form-check-input form-check" value="{{ $form->id }}">
                                </td>
                                <td>{{ $form->id }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $form->color }}">
                                        <i class="{{ $form->icon }} me-1"></i>
                                        {{ $form->label }}
                                    </span>
                                </td>
                                <td>{{ $form->name ?? '-' }}</td>
                                <td>{{ $form->email ?? '-' }}</td>
                                <td>{{ Str::limit($form->subject ?? $form->message, 50) }}</td>
                                <td>
                                    @if($form->is_read)
                                        <span class="badge bg-label-success">
                                            <i class="mdi mdi-check me-1"></i>
                                            {{ __('custom.words.read') }}
                                        </span>
                                    @else
                                        <span class="badge bg-label-warning">
                                            <i class="mdi mdi-email-alert me-1"></i>
                                            {{ __('custom.words.unread') }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $form->created_at->diffForHumans() }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('dashboard.forms.show', $form->id) }}">
                                                <i class="mdi mdi-eye-outline me-1"></i>
                                                {{ __('custom.words.view') }}
                                            </a>
                                            @if(!$form->is_read)
                                                <form action="{{ route('dashboard.forms.mark-as-read', $form->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="mdi mdi-check me-1"></i>
                                                        {{ __('custom.words.mark_as_read') }}
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('dashboard.forms.mark-as-unread', $form->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="mdi mdi-email-alert me-1"></i>
                                                        {{ __('custom.words.mark_as_unread') }}
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('dashboard.forms.destroy', $form->id) }}" method="POST"
                                                  onsubmit="return confirm('{{ __('custom.messages.delete_sure') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="mdi mdi-trash-can-outline me-1"></i>
                                                    {{ __('custom.words.delete') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="mdi mdi-email-outline mdi-48px d-block mb-2 text-muted"></i>
                                    <p class="text-muted">{{ __('custom.messages.no_data') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Bulk Actions -->
            <div class="card-body border-top">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('dashboard.forms.bulk-mark-as-read') }}" method="POST" id="bulk-mark-read-form">
                            @csrf
                            <div class="input-group">
                                <button type="submit" class="btn btn-outline-success" disabled id="bulk-mark-read-btn">
                                    <i class="mdi mdi-check me-1"></i>
                                    {{ __('custom.words.mark_selected_as_read') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <form action="{{ route('dashboard.forms.bulk-delete') }}" method="POST" id="bulk-delete-form"
                              onsubmit="return confirm('{{ __('custom.messages.delete_sure') }}');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger" disabled id="bulk-delete-btn">
                                <i class="mdi mdi-trash-can-outline me-1"></i>
                                {{ __('custom.words.delete_selected') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="card-footer">
                {{ $forms->links() }}
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Select all checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.form-check');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkButtons();
        });

        // Individual checkboxes
        document.querySelectorAll('.form-check').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleBulkButtons();
            });
        });

        // Toggle bulk action buttons
        function toggleBulkButtons() {
            const checkedBoxes = document.querySelectorAll('.form-check:checked');
            const bulkMarkReadBtn = document.getElementById('bulk-mark-read-btn');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

            if (checkedBoxes.length > 0) {
                bulkMarkReadBtn.disabled = false;
                bulkDeleteBtn.disabled = false;
            } else {
                bulkMarkReadBtn.disabled = true;
                bulkDeleteBtn.disabled = true;
            }
        }

        // Add checked IDs to forms before submit
        document.getElementById('bulk-mark-read-form').addEventListener('submit', function(e) {
            addCheckedIds(this);
        });

        document.getElementById('bulk-delete-form').addEventListener('submit', function(e) {
            addCheckedIds(this);
        });

        function addCheckedIds(form) {
            const checkedBoxes = document.querySelectorAll('.form-check:checked');
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });
        }
    </script>
@endsection

