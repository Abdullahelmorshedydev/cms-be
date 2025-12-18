@extends('admin.layouts.app')

@section('title', __('custom.words.form_details'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="{{ $form->icon }} me-2"></i>
                            {{ $form->label }}
                        </h5>
                        <span class="badge bg-label-{{ $form->color }}">
                            {{ $form->label }}
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Form Details -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted">{{ __('custom.words.id') }}</label>
                                <p class="form-control-static">#{{ $form->id }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">{{ __('custom.words.status') }}</label>
                                <p class="form-control-static">
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
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted">{{ __('custom.words.submitted_at') }}</label>
                                <p class="form-control-static">{{ $form->created_at->format('Y-m-d H:i A') }}</p>
                                <small class="text-muted">{{ $form->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Sender Information -->
                        @if($form->name || $form->email || $form->phone)
                            <h6 class="mb-3">{{ __('custom.words.sender_information') }}</h6>
                            <div class="row mb-3">
                                @if($form->name)
                                    <div class="col-md-4">
                                        <label class="form-label text-muted">
                                            <i class="mdi mdi-account-outline me-1"></i>
                                            {{ __('custom.inputs.name') }}
                                        </label>
                                        <p class="form-control-static">{{ $form->name }}</p>
                                    </div>
                                @endif

                                @if($form->email)
                                    <div class="col-md-4">
                                        <label class="form-label text-muted">
                                            <i class="mdi mdi-email-outline me-1"></i>
                                            {{ __('custom.inputs.email') }}
                                        </label>
                                        <p class="form-control-static">
                                            <a href="mailto:{{ $form->email }}">{{ $form->email }}</a>
                                        </p>
                                    </div>
                                @endif

                                @if($form->phone)
                                    <div class="col-md-4">
                                        <label class="form-label text-muted">
                                            <i class="mdi mdi-phone-outline me-1"></i>
                                            {{ __('custom.inputs.phone') }}
                                        </label>
                                        <p class="form-control-static">
                                            <a href="tel:{{ $form->phone }}">{{ $form->phone }}</a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <hr>
                        @endif

                        <!-- Subject -->
                        @if($form->subject)
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="mdi mdi-text-subject me-1"></i>
                                    {{ __('custom.inputs.subject') }}
                                </label>
                                <p class="form-control-static fw-bold">{{ $form->subject }}</p>
                            </div>
                            <hr>
                        @endif

                        <!-- Message -->
                        @if($form->message)
                            <div class="mb-3">
                                <label class="form-label text-muted">
                                    <i class="mdi mdi-message-text-outline me-1"></i>
                                    {{ __('custom.inputs.message') }}
                                </label>
                                <div class="p-3 border rounded bg-light">
                                    <p class="mb-0" style="white-space: pre-wrap;">{{ $form->message }}</p>
                                </div>
                            </div>
                            <hr>
                        @endif

                        <!-- Additional Data -->
                        @if($form->data && count($form->data) > 0)
                            <h6 class="mb-3">{{ __('custom.words.additional_data') }}</h6>
                            <div class="row">
                                @foreach($form->data as $key => $value)
                                    @if(!in_array($key, ['name', 'email', 'phone', 'subject', 'message']))
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted">
                                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                                            </label>
                                            <p class="form-control-static">
                                                @if(is_array($value))
                                                    {{ implode(', ', $value) }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Actions Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('custom.words.actions') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('dashboard.forms.index') }}" class="btn btn-label-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i>
                                {{ __('custom.words.back_to_list') }}
                            </a>

                            @if(!$form->is_read)
                                <form action="{{ route('dashboard.forms.mark-as-read', $form->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="mdi mdi-check me-1"></i>
                                        {{ __('custom.words.mark_as_read') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('dashboard.forms.mark-as-unread', $form->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="mdi mdi-email-alert me-1"></i>
                                        {{ __('custom.words.mark_as_unread') }}
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('dashboard.forms.destroy', $form->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('custom.messages.delete_sure') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="mdi mdi-trash-can-outline me-1"></i>
                                    {{ __('custom.words.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Meta Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('custom.words.meta_information') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">
                                <i class="mdi mdi-ip me-1"></i>
                                {{ __('custom.words.ip_address') }}
                            </label>
                            <p class="form-control-static small">{{ $form->ip_address ?? 'N/A' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small">
                                <i class="mdi mdi-devices me-1"></i>
                                {{ __('custom.words.device_info') }}
                            </label>
                            <p class="form-control-static small" style="word-break: break-word;">
                                {{ $form->user_agent ?? 'N/A' }}
                            </p>
                        </div>

                        @if($form->read_at)
                            <div class="mb-3">
                                <label class="form-label text-muted small">
                                    <i class="mdi mdi-clock-outline me-1"></i>
                                    {{ __('custom.words.read_at') }}
                                </label>
                                <p class="form-control-static small">
                                    {{ $form->read_at->format('Y-m-d H:i A') }}<br>
                                    <span class="text-muted">{{ $form->read_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        @endif

                        <div>
                            <label class="form-label text-muted small">
                                <i class="mdi mdi-calendar me-1"></i>
                                {{ __('custom.words.submitted_at') }}
                            </label>
                            <p class="form-control-static small">
                                {{ $form->created_at->format('Y-m-d H:i A') }}<br>
                                <span class="text-muted">{{ $form->created_at->diffForHumans() }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
