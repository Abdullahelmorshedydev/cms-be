@extends('admin.layouts.app')

@section('title', __('custom.words.email_recipients'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="mdi mdi-email-multiple me-2"></i>
                            {{ __('custom.words.email_recipients') }}
                        </h5>
                        <p class="text-muted small mb-0">{{ __('custom.words.manage_form_email_recipients') }}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('dashboard.forms.index') }}" class="btn btn-label-secondary me-2">
                            <i class="mdi mdi-arrow-left me-1"></i>
                            {{ __('custom.words.back_to_forms') }}
                        </a>
                        <a href="{{ route('dashboard.form-emails.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus me-1"></i>
                            {{ __('custom.words.add_recipient') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">{{ __('custom.inputs.name') }}</th>
                            <th width="20%">{{ __('custom.inputs.email') }}</th>
                            <th width="35%">{{ __('custom.words.receives_form_types') }}</th>
                            <th width="10%">{{ __('custom.inputs.is_active') }}</th>
                            <th width="10%">{{ __('custom.words.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($formEmails as $formEmail)
                            <tr>
                                <td>{{ $formEmail->id }}</td>
                                <td>
                                    <strong>{{ $formEmail->name }}</strong>
                                </td>
                                <td>
                                    <a href="mailto:{{ $formEmail->email }}">{{ $formEmail->email }}</a>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($formEmail->form_types_enums as $type)
                                            <span class="badge bg-label-{{ $type->color() }}">
                                                <i class="{{ $type->icon() }} me-1"></i>
                                                {{ $type->lang() }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    @if($formEmail->is_active->value)
                                        <span class="badge bg-label-success">
                                            <i class="mdi mdi-check me-1"></i>
                                            {{ __('custom.enums.active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-label-danger">
                                            <i class="mdi mdi-close me-1"></i>
                                            {{ __('custom.enums.inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('dashboard.form-emails.edit', $formEmail->id) }}">
                                                <i class="mdi mdi-pencil-outline me-1"></i>
                                                {{ __('custom.words.edit') }}
                                            </a>
                                            <form action="{{ route('dashboard.form-emails.destroy', $formEmail->id) }}"
                                                method="POST"
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
                                <td colspan="6" class="text-center py-4">
                                    <i class="mdi mdi-email-off-outline mdi-48px d-block mb-2 text-muted"></i>
                                    <p class="text-muted">{{ __('custom.messages.no_recipients') }}</p>
                                    <a href="{{ route('dashboard.form-emails.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="mdi mdi-plus me-1"></i>
                                        {{ __('custom.words.add_first_recipient') }}
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $formEmails->links() }}
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="mdi mdi-information-outline me-2"></i>
                    {{ __('custom.words.how_it_works') }}
                </h6>
                <ul class="mb-0">
                    <li>{{ __('custom.words.email_recipient_info_1') }}</li>
                    <li>{{ __('custom.words.email_recipient_info_2') }}</li>
                    <li>{{ __('custom.words.email_recipient_info_3') }}</li>
                    <li>{{ __('custom.words.email_recipient_info_4') }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
