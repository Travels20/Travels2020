<div class="panel">
    <div class="panel-title"><strong>{{__("Tour Content")}}</strong></div>
    <div class="panel-body">
        <div class="form-group magic-field" data-id="title" data-type="title">
            <label class="control-label">{{__("Title")}}</label>
            <input type="text" value="{{old('title',$translation->title)}}" placeholder="{{__("Title")}}" name="title" class="form-control" maxlength="30">
            <small class="form-text text-muted">
                 {{ __("Maximum 30 characters only.") }}
             </small>
        </div>
        <div class="form-group magic-field" data-id="content" data-type="content">
            <label class="control-label">{{__("Content")}}</label>
            <div class="">
                <textarea name="content" class="d-none has-ckeditor" id="content" cols="30" rows="10">{{old('content',$translation->content)}}</textarea>
            </div>
        </div>
        <div class="form-group d-none">
            <label class="control-label">{{__("Description")}}</label>
            <div class="">
                <textarea name="short_desc" class="form-control" cols="30" rows="4">{{old('short_desc',$translation->short_desc)}}</textarea>
            </div>
        </div>
        @if(is_default_lang())
            <div class="form-group">
                <label class="control-label">{{__("Category")}}</label>
                <div class="">
                    <select name="category_id" class="form-control">
                        <option value="">{{__("-- Please Select --")}}</option>
                        <?php
                        $traverse = function ($categories, $prefix = '') use (&$traverse, $row) {
                            foreach ($categories as $category) {
                                $selected = '';
                                if ($row->category_id == $category->id)
                                    $selected = 'selected';
                                printf("<option value='%s' %s>%s</option>", $category->id, $selected, $prefix . ' ' . $category->name);
                                $traverse($category->children, $prefix . '-');
                            }
                        };
                        $traverse($tour_category);
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{{__("Youtube Video")}}</label>
                <input type="text" name="video" class="form-control" value="{{old('video',$row->video)}}" placeholder="{{__("Youtube link video")}}">
            </div>

             <!-- <div class="form-group magic-field" data-id="stay" data-type="stay">
                 <label class="control-label">{{__("stay")}}</label>
                 <input type="text" value="{{old('stay',$translation->stay)}}" placeholder="{{__("stay")}}" name="stay" class="form-control">
            </div> -->


            @if(is_default_lang())
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label">{{__("Minimum advance reservations")}}</label>
                            <input type="number" name="min_day_before_booking" class="form-control" value="{{ old('min_day_before_booking', $row->min_day_before_booking) }}" placeholder="{{__("Ex: 3")}}">
                            <i>{{ __("Leave blank if you dont need to use the min day option") }}</i>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label">{{__("Duration")}}</label>
                            <div class="input-group mb-3">
                                <input type="text" name="duration" class="form-control" value="{{old('duration',$row->duration)}}" placeholder="{{__("Duration")}}"  aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">{{__('hours')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label">{{__("Tour Min People")}}</label>
                        <input type="text" name="min_people" class="form-control" value="{{old('min_people',$row->min_people)}}" placeholder="{{__("Tour Min People")}}">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label">{{__("Tour Max People")}}</label>
                        <input type="text" name="max_people" class="form-control" value="{{old('max_people',$row->max_people)}}" placeholder="{{__("Tour Max People")}}">
                    </div>
                </div>
            </div>

        @endif
        <?php do_action(\Modules\Tour\Hook::FORM_AFTER_MAX_PEOPLE,$row) ?>
        <div class="form-group-item">
            <label class="control-label">{{__('FAQs')}}</label>
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-5">{{__("Title")}}</div>
                    <div class="col-md-5">{{__('Content')}}</div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="g-items">
                @if(!empty($translation->faqs))
                    @php if(!is_array($translation->faqs)) $translation->faqs = json_decode(old('faqs',$translation->faqs)); @endphp
                    @foreach($translation->faqs as $key=>$faq)
                        <div class="item" data-number="{{$key}}">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="faqs[{{$key}}][title]" class="form-control" value="{{$faq['title']}}" placeholder="{{__('Eg: When and where does the tour end?')}}">
                                </div>
                                <div class="col-md-6">
                                    <textarea name="faqs[{{$key}}][content]" class="form-control full-h" placeholder="...">{{$faq['content']}}</textarea>
                                </div>
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="text-right">
                <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
            </div>
            <div class="g-more hide">
                <div class="item" data-number="__number__">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" __name__="faqs[__number__][title]" class="form-control" placeholder="{{__('Eg: When and where does the tour end?')}}">
                        </div>
                        <div class="col-md-6">
                            <textarea __name__="faqs[__number__][content]" class="form-control full-h" placeholder="..."></textarea>
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group-item">
             <label class="control-label">{{__('Activities')}}</label>
                <div class="g-items-header">
                    <div class="row">
                        <div class="col-md-5">{{__("Content")}}</div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                <div class="g-items">
                    @if(!empty($translation->activities))
                        @php 
                            if(!is_array($translation->activities)) {
                                $translation->activities = json_decode($translation->activities, true); // Ensure it is an array
                            }
                        @endphp
                        @foreach($translation->activities as $key => $activity)
                            <div class="item" data-number="{{$key}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <input type="text" name="activities[{{$key}}][title]" class="form-control" value="{{ $activity['title'] ?? '' }}" placeholder="{{__('Eg: Speedboat Transfer')}}">
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="text-right">
                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                </div>
                <div class="g-more hide">
                    <div class="item" data-number="__number__">
                        <div class="row">
                            <div class="col-md-11">
                                <input type="text" __name__="activities[__number__][title]" class="form-control" placeholder="{{__('Eg: Activities & Transport')}}">
                            </div>
                            <div class="col-md-1">
                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
         </div>

        <div class="form-group magic-field" data-id="stay" data-type="stay">
            <label class="control-label">{{__("Stay ")}}</label>
            <input type="text" value="{{$translation->stay}}" placeholder="{{__("stay")}}" name="stay" class="form-control" maxlength="80">
            <small class="form-text text-muted">
                 {{ __("Maximum 80 characters only.") }}
             </small>
        </div>

        <!-- <div class="form-group magic-field" data-id="hotel" data-type="hotel">
            <label class="control-label">{{__("Hotel Ratting")}}</label>
            <input type="number" value="{{$translation->hotel}}" placeholder="{{__("hotel")}}" name="hotel" class="form-control" >
           
        </div> -->
    

        <div class="form-group magic-field" data-id="hotel" data-type="hotel">
            <label class="control-label">{{ __("Hotel Rating") }}</label>
            <select name="hotel" class="form-control">
                <option value="2" {{ $translation->hotel == '2' ? 'selected' : '' }}>2 ★ Star</option>
                <option value="3" {{ $translation->hotel == '3' ? 'selected' : '' }}>3 ★ Star</option>
                <option value="4" {{ $translation->hotel == '4' ? 'selected' : '' }}>4 ★ Star</option>
                <option value="5" {{ $translation->hotel == '5' ? 'selected' : '' }}>5 ★ Star</option>
            </select>
        </div>

        <div class="form-group magic-field" data-id="meal_plan" data-type="meal_plan">
            <label class="control-label">{{ __("Meal Plan") }}</label>
            <select name="meal_plan" class="form-control">
                <option value="Breakfast" {{ $translation->meal_plan == 'Breakfast' ? 'selected' : '' }}>
                    {{ __("Breakfast") }}
                </option>
                <option value="Full Board" {{ $translation->meal_plan == 'Full Board' ? 'selected' : '' }}>
                    {{ __("Full Board") }}
                </option>
                <option value="Breakfast + Dinner" {{ $translation->meal_plan == 'Breakfast + Dinner' ? 'selected' : '' }}>
                    {{ __("Breakfast + Dinner") }}
                </option>
                <option value="All Included" {{ $translation->meal_plan == 'All Included' ? 'selected' : '' }}>
                    {{ __("All Included") }}
                </option>
            </select>
        </div>

        <div class="form-group magic-field" data-id="transfers" data-type="transfers">
            <label class="control-label">{{ __("Transfer Type") }}</label>
            <select name="transfers" class="form-control">
                <option value="PVT" {{ $translation->transfers == 'PVT' ? 'selected' : '' }}>
                    {{ __("PVT - Private Transfer") }}
                </option>
                <option value="SIC" {{ $translation->transfers == 'SIC' ? 'selected' : '' }}>
                    {{ __("SIC - Seat In Coach") }}
                </option>
                <option value="Speedboat" {{ $translation->transfers == 'Speedboat' ? 'selected' : '' }}>
                    {{ __("Speedboat") }}
                </option>
                <option value="Seaplane" {{ $translation->transfers == 'Seaplane' ? 'selected' : '' }}>
                    {{ __("Seaplane") }}
                </option>
                <option value="Domestic Flight" {{ $translation->transfers == 'Domestic Flight' ? 'selected' : '' }}>
                    {{ __("Domestic Flight") }}
                </option>
            </select>
        </div>


        <!-- <div class="form-group magic-field" data-id="shortdesc" data-type="shortdesc">
            <label class="control-label">{{__(" Short Inclusion")}}</label>
            <input type="text" value="{{$translation->shortdesc}}" placeholder="{{__("Short Inclusion")}}" name="inclusion" class="form-control">
        </div> -->




        @include('Tour::admin/tour/include-exclude')
        @include('Tour::admin/tour/itinerary')
        @if(is_default_lang())
            <div class="form-group">
                <label class="control-label">{{__("Banner Image")}}</label>
                <div class="form-group-image">
                    {!! \Modules\Media\Helpers\FileHelper::fieldUpload('banner_image_id',old('banner_image_id',$row->banner_image_id)) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label">{{__("Gallery")}}</label>
                {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery',old('gallery',$row->gallery)) !!}
            </div>
        @endif
    </div>
</div>
