<div class="ibox">
    <div class="ibox-title ibox-title-slide">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <h5>{{ __('form.list_slide') }}</h5>
            <button type="button" class="addSlide btn btn-success">{{ __('form.add_slide') }}</button>
        </div>
    </div>
    @php
        $slides = old('slide', $slideItem ?? []);
        $i = 1;
    @endphp
    <div class="ibox-content">
        <div id="sortable" class="row slide-list sortui ui-sortable">
            <div class="text-danger slide-notification">
                {{ __('form.no_image_selected') }}</div>
            @if (isset($slides) && is_array($slides) && count($slides) > 0)
                @foreach ($slides['image'] as $key => $val)
                    @php
                        $image = $val;
                        $description = $slides['description'][$key];
                        $canonical = $slides['canonical'][$key];
                        $window = $slides['window'][$key];
                        $name = $slides['name'][$key];
                        $alt = $slides['alt'][$key];

                    @endphp
                    <div class="col-lg-12 ui-state-default">
                        <div class="slide-item mb20">
                            <div class="row custom-row">
                                <div class="col-lg-3 mb10">
                                    <span class="slide-image img-cover">
                                        <img src="{{ $image }}" alt="">
                                        <div class="change-img text-center">
                                            {{ __('form.change_image') }}
                                        </div>
                                        <input type="hidden" name="slide[image][]" value="{{ $image }}">
                                        <button class="deleteSlide"><i class="fa fa-trash"></i></button>
                                    </span>
                                </div>
                                <div class="col-lg-9">
                                    <div class="tabs-container">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#tab_{{ $i }}"
                                                    aria-expanded="true">
                                                    {{ __('form.general_info') }}</a>
                                            </li>
                                            <li class=""><a data-toggle="tab" href="#tab_{{ $i + 1 }}"
                                                    aria-expanded="false">{{ __('form.seo') }}</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="tab_{{ $i }}" class="tab-pane active">
                                                <div class="panel-body">
                                                    <div class="label-text mb5"> {{ __('form.description') }}
                                                    </div>
                                                    <div class="form-row mb10">
                                                        <textarea name="slide[description][]" class="form-control">{{ $description }}</textarea>
                                                    </div>
                                                    <div class="form-row form-row-url">
                                                        <input type="text" name="slide[canonical][]"
                                                            class="form-control" placeholder="{{ __('form.url') }}"
                                                            value="{{ $canonical }}">
                                                        <div class="overlay">
                                                            <div class="uk-flex uk-flex-middle">
                                                                <label
                                                                    for="input_tab_{{ $i }}">{{ __('form.open_in_new_tab') }}</label>
                                                                <input type="hidden" name="slide[window][]"
                                                                    value="{{ $window }}">
                                                                <input type="checkbox" class="slide-window"
                                                                    id="input_tab_{{ $i }}"
                                                                    {{ $window == '_blank' ? 'checked' : '' }}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="tab_{{ $i + 1 }}" class="tab-pane">
                                                <div class="panel-body">
                                                    <div class="label-text mb5">{{ __('form.image_title') }}
                                                    </div>
                                                    <div class="form-row form-row-url slide-seo-tab">
                                                        <input type="text" name="slide[name][]" class="form-control"
                                                            placeholder="{{ __('form.image_title') }}"
                                                            value="{{ $name }}">
                                                    </div>
                                                    <div class="label-text mb5 mt12">
                                                        {{ __('form.image_description') }}
                                                    </div>
                                                    <div class="form-row form-row-url slide-seo-tab">
                                                        <input type="text" name="slide[alt][]" class="form-control"
                                                            placeholder="{{ __('form.image_description') }}"
                                                            value="{{ $alt }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                    @php
                        $i += 2;
                    @endphp
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
    var generalInfo = "{{ __('form.general_info') }}";
    var seo = "{{ __('form.seo') }}";
    var description = "{{ __('form.description') }}";
    var url = "{{ __('form.url') }}";
    var openInNewTab = "{{ __('form.open_in_new_tab') }}";
    var imageTitle = "{{ __('form.image_title') }}";
    var imageDescription = "{{ __('form.image_description') }}";
    var changeImage = "{{ __('form.change_image') }}"
</script>
