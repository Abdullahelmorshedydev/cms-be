@php
    $faqs = $sectionContent['faqs'] ?? [
        ['question' => ['en' => '', 'ar' => ''], 'answer' => ['en' => '', 'ar' => '']],
        ['question' => ['en' => '', 'ar' => ''], 'answer' => ['en' => '', 'ar' => '']],
        ['question' => ['en' => '', 'ar' => ''], 'answer' => ['en' => '', 'ar' => '']],
        ['question' => ['en' => '', 'ar' => ''], 'answer' => ['en' => '', 'ar' => '']],
    ];
@endphp

<div class="row">
    {{-- Headline --}}
    @foreach($locales as $locale)
        <div class="col-md-6 mb-3">
            <div class="form-floating form-floating-outline">
                <input type="text" 
                    class="form-control"
                    name="{{ $namePrefix }}[title][{{ $locale }}]"
                    value="{{ $sectionContent['title'][$locale] ?? '' }}"
                    placeholder="{{ __('custom.inputs.headline_' . $locale) }}">
                <label>{{ __('custom.inputs.headline_' . $locale) }}</label>
            </div>
        </div>
    @endforeach

    {{-- FAQs (4 fixed FAQs) --}}
    @for($faqIndex = 0; $faqIndex < 4; $faqIndex++)
        <div class="col-12 mb-4">
            <div class="card border">
                <div class="card-header bg-light">
                    <strong>FAQ {{ $faqIndex + 1 }}</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- FAQ Question --}}
                        @foreach($locales as $locale)
                            <div class="col-md-6 mb-3">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" 
                                        class="form-control"
                                        name="{{ $namePrefix }}[faqs][{{ $faqIndex }}][question][{{ $locale }}]"
                                        value="{{ $faqs[$faqIndex]['question'][$locale] ?? '' }}"
                                        placeholder="Question {{ strtoupper($locale) }}">
                                    <label>Question {{ strtoupper($locale) }}</label>
                                </div>
                            </div>
                        @endforeach

                        {{-- FAQ Answer --}}
                        @foreach($locales as $locale)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Answer {{ strtoupper($locale) }}</label>
                                <textarea 
                                    class="form-control section-description-editor"
                                    id="faq-answer-{{ $sectionIndex }}-{{ $faqIndex }}-{{ $locale }}{{ isset($isSubsection) && $isSubsection ? '-sub-' . $subIndex : '' }}"
                                    name="{{ $namePrefix }}[faqs][{{ $faqIndex }}][answer][{{ $locale }}]"
                                    rows="4"
                                    placeholder="Answer {{ strtoupper($locale) }}">{{ $faqs[$faqIndex]['answer'][$locale] ?? '' }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>
