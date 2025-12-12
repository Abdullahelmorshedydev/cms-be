<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="mdi mdi-filter me-2"></i>
                    {{ __('custom.words.filter') }} {{ __('custom.blog.blogs') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.blogs.index') }}" method="GET">
                <div class="modal-body">
                    <div class="row">
                        <!-- Title Search -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('custom.inputs.title') }}</label>
                            <input type="text"
                                class="form-control"
                                name="filters[title->{{ app()->getLocale() }}][value]"
                                value="{{ request('filters.title->' . app()->getLocale() . '.value') }}"
                                placeholder="{{ __('custom.words.search') }} {{ __('custom.inputs.title') }}">
                            <input type="hidden" name="filters[title->{{ app()->getLocale() }}][operator]" value="like">
                        </div>

                        <!-- Creator Filter -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('custom.inputs.creator') }}</label>
                            <select class="form-select" name="filters[created_by][value]">
                                <option value="">{{ __('custom.words.all') }}</option>
                                @if(isset($data['users']))
                                    @foreach($data['users'] as $user)
                                        <option value="{{ $user->id }}"
                                            {{ request('filters.created_by.value') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <input type="hidden" name="filters[created_by][operator]" value="=">
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('custom.inputs.status') }}</label>
                            <select class="form-select" name="filters[is_active][value]">
                                <option value="">{{ __('custom.words.all') }}</option>
                                <option value="1" {{ request('filters.is_active.value') == '1' ? 'selected' : '' }}>
                                    {{ __('custom.enums.active') }}
                                </option>
                                <option value="2" {{ request('filters.is_active.value') == '2' ? 'selected' : '' }}>
                                    {{ __('custom.enums.inactive') }}
                                </option>
                            </select>
                            <input type="hidden" name="filters[is_active][operator]" value="=">
                        </div>

                        <!-- Published Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('custom.blog.published_status') }}</label>
                            <select class="form-select" name="published_filter">
                                <option value="">{{ __('custom.words.all') }}</option>
                                <option value="published" {{ request('published_filter') == 'published' ? 'selected' : '' }}>
                                    {{ __('custom.blog.published') }}
                                </option>
                                <option value="draft" {{ request('published_filter') == 'draft' ? 'selected' : '' }}>
                                    {{ __('custom.words.draft') }}
                                </option>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('custom.words.date_from') }}</label>
                            <input type="date"
                                class="form-control"
                                name="filters[created_at][value][0]"
                                value="{{ request('filters.created_at.value.0') }}">
                            <input type="hidden" name="filters[created_at][operator]" value=">=">
                        </div>

                        <!-- Date To -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('custom.words.date_to') }}</label>
                            <input type="date"
                                class="form-control"
                                name="filters[created_at][value][1]"
                                value="{{ request('filters.created_at.value.1') }}">
                        </div>

                        <!-- Sort By -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('custom.words.sort_by') }}</label>
                            <select class="form-select" name="sort_by">
                                <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>
                                    {{ __('custom.words.id') }}
                                </option>
                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>
                                    {{ __('custom.inputs.title') }}
                                </option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>
                                    {{ __('custom.words.created_at') }}
                                </option>
                                <option value="published_at" {{ request('sort_by') == 'published_at' ? 'selected' : '' }}>
                                    {{ __('custom.inputs.published_at') }}
                                </option>
                            </select>
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('custom.words.sort_order') }}</label>
                            <select class="form-select" name="sort_order">
                                <option value="DESC" {{ request('sort_order', 'DESC') == 'DESC' ? 'selected' : '' }}>
                                    {{ __('custom.words.descending') }}
                                </option>
                                <option value="ASC" {{ request('sort_order') == 'ASC' ? 'selected' : '' }}>
                                    {{ __('custom.words.ascending') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        {{ __('custom.words.close') }}
                    </button>
                    <a href="{{ route('dashboard.blogs.index') }}" class="btn btn-label-warning">
                        <i class="mdi mdi-refresh me-1"></i>
                        {{ __('custom.words.reset') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-filter-check me-1"></i>
                        {{ __('custom.words.apply_filter') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
