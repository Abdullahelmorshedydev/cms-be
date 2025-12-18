@extends('admin.layouts.app')

@section('title', __('custom.words.add_recipient'))

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="mdi mdi-email-plus me-2"></i>
                    {{ __('custom.words.add_new_email_recipient') }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.form-emails.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                {{ __('custom.inputs.name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('custom.inputs.name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('custom.words.recipient_name_hint') }}</small>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                {{ __('custom.inputs.email') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="example@domain.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Form Types -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                {{ __('custom.words.form_types_to_receive') }}
                                <span class="text-danger">*</span>
                            </label>
                            <p class="text-muted small mb-2">{{ __('custom.words.select_form_types_hint') }}</p>

                            <div class="row g-3" id="form-types-container">
                                @foreach($types as $type)
                                    @php
                                        $formTypeEnum = \App\Enums\FormTypeEnum::from($type['value']);
                                        $isChecked = in_array($type['value'], old('form_types', []));
                                    @endphp
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                        <input type="checkbox"
                                               class="btn-check form-type-checkbox"
                                               name="form_types[]"
                                               value="{{ $type['value'] }}"
                                               id="type_{{ $type['value'] }}"
                                               autocomplete="off"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary w-100 text-start d-flex align-items-center justify-content-between p-3 form-type-label"
                                               for="type_{{ $type['value'] }}"
                                               style="height: 100%; min-height: 70px; transition: all 0.3s ease;">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-sm">
                                                        <span class="avatar-initial rounded bg-label-{{ $formTypeEnum->color() }}">
                                                            <i class="{{ $formTypeEnum->icon() }} fs-5"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold small">{{ $type['lang'] }}</div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <i class="mdi mdi-check-circle text-success fs-5 check-icon" style="opacity: 0;"></i>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('form_types')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                {{ __('custom.inputs.is_active') }}
                                <span class="text-danger">*</span>
                            </label>
                            <select name="is_active" class="form-select @error('is_active') is-invalid @enderror" required>
                                @foreach($status as $stat)
                                    <option value="{{ $stat->value }}" {{ old('is_active', 1) == $stat->value ? 'selected' : '' }}>
                                        {{ $stat->value == 1 ? __('custom.enums.active') : __('custom.enums.inactive') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i>
                                {{ __('custom.words.save') }}
                            </button>
                            <a href="{{ route('dashboard.form-emails.index') }}" class="btn btn-label-secondary">
                                <i class="mdi mdi-close me-1"></i>
                                {{ __('custom.words.cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="mdi mdi-lightbulb-on-outline me-2"></i>
                    {{ __('custom.words.tips') }}
                </h6>
                <ul class="mb-0">
                    <li>{{ __('custom.words.email_tip_1') }}</li>
                    <li>{{ __('custom.words.email_tip_2') }}</li>
                    <li>{{ __('custom.words.email_tip_3') }}</li>
                    <li>{{ __('custom.words.email_tip_4') }}</li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .form-type-checkbox:checked + .form-type-label {
            background-color: var(--bs-primary) !important;
            color: white !important;
            border-color: var(--bs-primary) !important;
            box-shadow: 0 0.125rem 0.5rem rgba(var(--bs-primary-rgb), 0.3);
            transform: translateY(-2px);
        }

        .form-type-checkbox:checked + .form-type-label .avatar-initial {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }

        .form-type-checkbox:checked + .form-type-label .check-icon {
            opacity: 1 !important;
        }

        .form-type-label:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .form-type-checkbox:checked + .form-type-label:hover {
            box-shadow: 0 0.125rem 0.5rem rgba(var(--bs-primary-rgb), 0.4);
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkboxes functionality
            const checkboxes = document.querySelectorAll('.form-type-checkbox');

            if (checkboxes.length === 0) return;

            // Find the container for form types
            const container = document.getElementById('form-types-container');
            if (!container) return;

            // Create control buttons container
            const controlsDiv = document.createElement('div');
            controlsDiv.className = 'd-flex gap-2 mb-3 align-items-center';

            // Create "Select All" button
            const selectAllBtn = document.createElement('button');
            selectAllBtn.type = 'button';
            selectAllBtn.className = 'btn btn-sm btn-outline-primary';
            selectAllBtn.innerHTML = '<i class="mdi mdi-checkbox-multiple-marked-outline me-1"></i> {{ __("custom.words.select_all") }}';

            // Create counter badge
            const counterBadge = document.createElement('span');
            counterBadge.className = 'badge bg-label-primary';
            counterBadge.style.fontSize = '0.875rem';

            function updateCounter() {
                const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
                counterBadge.textContent = `${checkedCount} / ${checkboxes.length} {{ __("custom.words.selected") }}`;
            }

            selectAllBtn.onclick = function() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                checkboxes.forEach(cb => {
                    cb.checked = !allChecked;
                    // Trigger change event to update UI
                    cb.dispatchEvent(new Event('change'));
                });
                updateButtonText();
                updateCounter();
            };

            // Function to update button text based on checkbox state
            function updateButtonText() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                selectAllBtn.innerHTML = allChecked ?
                    '<i class="mdi mdi-checkbox-multiple-blank-outline me-1"></i> {{ __("custom.words.deselect_all") }}' :
                    '<i class="mdi mdi-checkbox-multiple-marked-outline me-1"></i> {{ __("custom.words.select_all") }}';
            }

            // Add change listener to checkboxes
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateButtonText();
                    updateCounter();
                });
            });

            // Assemble controls
            controlsDiv.appendChild(selectAllBtn);
            controlsDiv.appendChild(counterBadge);

            // Insert controls before the container
            container.parentElement.insertBefore(controlsDiv, container);

            // Initial updates
            updateButtonText();
            updateCounter();
        });
    </script>
@endsection

