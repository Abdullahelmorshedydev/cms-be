@props(['index', 'section' => null])

<div class="accordion-item" data-id="{{ isset($section) ? $section->id : null }}">
    <h2 class="accordion-header position-relative d-flex align-items-center justify-content-between pe-4"
        id="section-header-{{ $index }}">
        <button class="accordion-button collapsed flex-grow-1 text-start" type="button" data-bs-toggle="collapse"
            data-bs-target="#section-collapse-{{ $index }}" aria-expanded="false"
            aria-controls="section-collapse-{{ $index }}">
            {{ __('custom.page.section_number', ['number' => $index + 1]) }}
            @if (isset($section) && $section)
                @php
                    $sectionName = '';
                    if (method_exists($section, 'getTranslation')) {
                        $sectionName = $section->getTranslation('name', app()->getLocale()) ?? '';
                    } elseif (is_object($section) && isset($section->name)) {
                        if (is_array($section->name)) {
                            $sectionName =
                                $section->name[app()->getLocale()] ?? (array_values($section->name)[0] ?? '');
                        } else {
                            $sectionName = $section->name;
                        }
                    }
                @endphp
                @if ($sectionName)
                    - {{ $sectionName }}
                @endif
            @endif
        </button>

        {{-- Delete button --}}
        <button type="button" class="btn btn-sm btn-outline-danger delete-section ms-2" title="{{ __('custom.page.delete_section') }}">
            <i class="fa fa-trash"></i>
        </button>
    </h2>
    <div id="section-collapse-{{ $index }}" class="accordion-collapse collapse"
        aria-labelledby="section-header-{{ $index }}" data-bs-parent="#dynamicAccordion">
        <div class="accordion-body">
            @include('dashboard.pages.pages.partials.__accordion_content', [
                'index' => $index,
                'section' => $section ?? null,
            ])
            <input type="hidden" name="sections[{{ $index }}][order]" value="{{ $index }}">

            <div class="mt-3">
                <h6>{{ __('custom.page.subsections') }}</h6>
                <button type="button" class="btn btn-sm btn-outline-primary add-subsection-btn"
                    data-parent-index="{{ $index }}">
                    {{ __('custom.page.add_subsection') }}
                </button>
                <div class="accordion mt-2 subsection-container" id="subsection-container-{{ $index }}">
                    @if (isset($section) && !empty($section->subsections))
                        @foreach ($section->subsections as $subIndex => $subSection)
                            @include('dashboard.pages.pages.partials.__sub_accordion_item', [
                                'parentIndex' => $index,
                                'subIndex' => $subIndex,
                                'section' => $subSection,
                            ])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
