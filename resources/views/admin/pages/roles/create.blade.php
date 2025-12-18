@extends('admin.layouts.app')

@section('title', __('custom.words.create_new') . ' ' . __('custom.role.role'))

@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- roles List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>{{ __('custom.words.create_new') . ' ' . __('custom.role.role') }}</h4>
                <a href="{{ route('dashboard.roles.index') }}" class="btn btn-primary">{{ __('custom.words.back') }}</a>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.roles.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label for="nameInput">{{ __('custom.inputs.name') }}</label>
                                <input id="nameInput" class="form-control" type="text" name="name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="displayNameEn">{{ __('custom.inputs.display_name_en') }}</label>
                                <input id="displayNameEn" class="form-control" type="text" name="display_name[en]"
                                    value="{{ old('display_name.en') }}">
                                @error('display_name.en')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="form-group">
                                <label for="displayNameAr">{{ __('custom.inputs.display_name_ar') }}</label>
                                <input id="displayNameAr" class="form-control" type="text" name="display_name[ar]"
                                    value="{{ old('display_name.ar') }}">
                                @error('display_name.ar')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <label for="nameInput">{{ __('custom.role.permissions') }}</label>

                                <div class="mb-3 mt-3">
                                    <input id="select-all" type="checkbox">
                                    <label for="select-all">{{ __('custom.role.select_all_permissions') }}</label>
                                </div>

                                @foreach ($data['permissions'] as $groupPermissions)
                                    <div class="permissions-section mt-3">
                                        <h5>{{ ucfirst($groupPermissions['label']) }}</h5>

                                        <div class="mb-2">
                                            <input id="select-all-{{ $groupPermissions['value'] }}" type="checkbox"
                                                class="select-all-section">
                                            <label
                                                for="select-all-{{ $groupPermissions['value'] }}">{{ __('custom.role.select_all_in_section') }}</label>
                                        </div>

                                        <div class="row">
                                            @foreach ($groupPermissions['values'] as $permission)
                                                <div class="col-3 mt-2">
                                                    <input id="{{ $permission->id }}" type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        class="permission-checkbox permission-{{ $groupPermissions['value'] }}">
                                                    <label for="{{ $permission->id }}">{{ $permission->display_name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                @error('permissions')
                                    <div class="text-danger"> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('custom.words.create') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('select-all');
            selectAll.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });

                const sectionSelectAlls = document.querySelectorAll('.select-all-section');
                sectionSelectAlls.forEach(sectionSelectAll => {
                    sectionSelectAll.checked = selectAll.checked;
                });
            });

            const sectionSelectAlls = document.querySelectorAll('.select-all-section');
            sectionSelectAlls.forEach(sectionSelectAll => {
                sectionSelectAll.addEventListener('change', function () {
                    const group = this.id.replace('select-all-', '');
                    const groupCheckboxes = document.querySelectorAll('.permission-' + group);
                    groupCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });

                    updateGlobalSelectAll();
                });
            });

            const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
            permissionCheckboxes.forEach(permissionCheckbox => {
                permissionCheckbox.addEventListener('change', function () {
                    updateSectionSelectAll(this);

                    updateGlobalSelectAll();
                });
            });

            function updateSectionSelectAll(checkbox) {
                const group = Array.from(checkbox.classList).find(cls => cls.startsWith('permission-')).replace(
                    'permission-', '');
                const groupCheckboxes = document.querySelectorAll('.permission-' + group);
                const sectionSelectAll = document.getElementById('select-all-' + group);
                sectionSelectAll.checked = Array.from(groupCheckboxes).every(cb => cb.checked);
            }

            function updateGlobalSelectAll() {
                const allCheckboxes = document.querySelectorAll('.permission-checkbox');
                const sectionSelectAlls = document.querySelectorAll('.select-all-section');
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                const allSectionsChecked = Array.from(sectionSelectAlls).every(ssa => ssa.checked);

                selectAll.checked = allChecked && allSectionsChecked;
            }
        });
    </script>
@endsection
