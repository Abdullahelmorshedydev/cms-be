<div class="container">
    <!-- Tab Navigation -->
    <ul class="nav nav-tabs" id="settingTabs" role="tablist">
        @foreach ($settingGroups as $group)
            <li class="nav-item" role="presentation">
                <a class="nav-link @if ($loop->first) active @endif" id="tab-{{ $group->value }}"
                    data-bs-toggle="tab" href="#tab-panel-{{ $group->value }}" role="tab"
                    aria-controls="tab-panel-{{ $group->value }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    {{ $group->lang() }}
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="settingTabsContent">
        @foreach ($settingGroups as $group)
            <div class="tab-pane fade @if ($loop->first) show active @endif"
                id="tab-panel-{{ $group->value }}" role="tabpanel" aria-labelledby="tab-{{ $group->value }}">

                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('custom.columns.label') }}</th>
                            <th>{{ __('custom.columns.value') }}</th>
                            @can('settings.edit')
                                <th>{{ __('custom.words.actions') }}</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($settings[$group->value] ?? []) as $setting)
                            <tr>
                                <td>{{ is_array($setting?->label) ? ($setting->label[app()->getLocale()] ?? $setting->label['en'] ?? '') : ($setting?->label ?? '') }}</td>
                                <td>
                                    @if ($setting?->type?->inputType() == 'file')
                                        <a href="{{ $setting?->image_path }}" target="_blank">
                                            <img src="{{ $setting?->image_path }}"
                                                alt="{{ $setting?->image?->alt_text }}" width="50" height="50">
                                        </a>
                                    @else
                                        {{ is_array($setting?->value) ? ($setting->value[app()->getLocale()] ?? $setting->value['en'] ?? '') : ($setting?->value ?? '') }}
                                    @endif
                                </td>
                                @can('settings.edit')
                                    <td>
                                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-url="{{ route('dashboard.settings.update', $setting->key) }}"
                                            data-key-en="value[en]"
                                            data-label-en="{{ is_array($setting?->label) ? ($setting->label['en'] ?? '') : ($setting?->label ?? '') }}"
                                            data-value-en="{{ is_array($setting?->value) ? ($setting->value['en'] ?? '') : ($setting?->value ?? '') }}"
                                            data-key-ar="value[ar]"
                                            data-label-ar="{{ is_array($setting?->label) ? ($setting->label['ar'] ?? '') : ($setting?->label ?? '') }}"
                                            data-value-ar="{{ is_array($setting?->value) ? ($setting->value['ar'] ?? '') : ($setting?->value ?? '') }}"
                                            data-type="{{ $setting?->type?->inputType() }}">
                                            <i class="mdi mdi-pen me-1"></i>
                                            {{ __('custom.words.edit') }}
                                        </a>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        @endforeach
    </div>
</div>

<!-- Modal and JavaScript Section -->
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');

            editModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                const modalAction = editModal.querySelector('#editForm');
                modalAction.setAttribute('action', button.getAttribute('data-url'));

                const modalInputEn = editModal.querySelector('#inputEn');
                modalInputEn.setAttribute('type', button.getAttribute('data-type'));
                modalInputEn.setAttribute('name', button.getAttribute('data-key-en'));

                const modalInputAr = editModal.querySelector('#inputAr');
                modalInputAr.setAttribute('type', button.getAttribute('data-type'));
                modalInputAr.setAttribute('name', button.getAttribute('data-key-ar'));

                const modalLabelEn = editModal.querySelector('#labelEn');
                modalLabelEn.innerHTML = button.getAttribute('data-label-en');

                const modalLabelAr = editModal.querySelector('#labelAr');
                modalLabelAr.innerHTML = button.getAttribute('data-label-ar');

                // Check if the input is a file
                if (button.getAttribute('data-type') == 'file') {
                    modalInputEn.removeAttribute('value');
                    modalInputAr.removeAttribute('value');

                    const parentDivAr = modalInputAr.parentElement;
                    if (!parentDivAr.classList.contains('d-none')) {
                        parentDivAr.classList.add('d-none');
                    }

                    const grandParentDivEn = modalInputEn.closest('div').parentElement;
                    if (grandParentDivEn.classList.contains('col-sm-6')) {
                        grandParentDivEn.classList.remove('col-sm-6');
                        grandParentDivEn.classList.add('col-sm-12');
                    }

                    const previewContainer = editModal.querySelector('#imagePreviewContainer');
                    previewContainer.innerHTML = '';

                    modalInputEn.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                const container = document.createElement('div');
                                container.className =
                                    'd-flex align-items-center border p-2 rounded position-relative h-100';

                                container.innerHTML = `
                                    <img src="${event.target.result}" class="img-thumbnail" width="100">
                                    <div class="ms-3 flex-grow-1">
                                        <p class="mb-0 text-truncate">${file.name}</p>
                                    </div>
                                    <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center"
                                            style="background-color: transparent; border: 1px solid #464963; color: inherit; width: fit-content; height: 35px; padding: 0 20px; margin-inline-end: 10px;"
                                            title="Remove Preview">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                `;

                                container.querySelector('button').addEventListener('click',
                                    () => {
                                        modalInputEn.value = '';
                                        container.remove();
                                    });

                                previewContainer.appendChild(container);
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                } else {
                    modalInputEn.value = button.getAttribute('data-value-en');
                    modalInputAr.value = button.getAttribute('data-value-ar');

                    const parentDivAr = modalInputAr.parentElement;
                    if (parentDivAr.classList.contains('d-none')) {
                        parentDivAr.classList.remove('d-none');
                    }

                    const grandParentDivEn = modalInputEn.closest('div').parentElement;
                    if (grandParentDivEn.classList.contains('col-sm-12')) {
                        grandParentDivEn.classList.remove('col-sm-12');
                        grandParentDivEn.classList.add('col-sm-6');
                    }
                }
            });
        });
    </script>
@endsection
