@php
    $translation = $row->translate();
@endphp

<div class="item-tour item-loop-wrap {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image">
        @if($row->discount_percent)
            <div class="sale_info">{{$row->discount_percent}}</div>
        @endif
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            @if($row->image_url)
                @if(!empty($disable_lazyload))
                    <img src="{{$row->image_url}}" class="img-responsive" alt="{{$location->name ?? ''}}">
                @else
                    {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive','alt'=>$row->title]) !!}
                @endif
            @endif
        </a>
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    <div class="location">
        @if(!empty($row->location->name))
            @php $location =  $row->location->translate() @endphp
            <i class="icofont-paper-plane"></i>
            {{$location->name ?? ''}}
        @endif
    </div>
    <div class="item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
            {{$translation->title}}
        </a>
    </div>
    @if(setting_item('tour_enable_review'))
    <!-- <?php
    $reviewData = $row->getScoreReview();
    $score_total = $reviewData['score_total'];
    ?>
    <div class="service-review tour-review-{{$score_total}}">
     
        <div class="list-star">
            <ul class="booking-item-rating-stars">
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
                <li><i class="fa fa-star-o"></i></li>
            </ul>
            <div class="booking-item-rating-stars-active" style="width: {{  $score_total * 2 * 10 ?? 0  }}%">
                <ul class="booking-item-rating-stars">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                </ul>
            </div>
        </div>
        <span class="review">
            @if($reviewData['total_review'] > 1)
                {{ __(":number Reviews",["number"=>$reviewData['total_review'] ]) }}
            @else
                {{ __(":number Review",["number"=>$reviewData['total_review'] ]) }}
            @endif
        </span>
    </div>
    @endif -->
    <div class="d-flex flex-wrap align-items-center px-1 item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl($include_param ?? true)}}">
        
            <!-- <div class="d-flex align-items-center flex-wrap">

                @if(!empty($row->hotel))
                    <div class="d-flex align-items-center flex-wrap mr-3">
                        <span class="mr-1">{{ $row->hotel }}</span>
                        <i class="fa fa-star text-warning mr-1"></i>
                        <span>Hotel</span>
                    </div>
                @endif
               
                @if(!empty($row->meal_plan))
                    <div class="d-flex align-items-center flex-wrap mr-3">
                        <i class="fa fa-cutlery mr-2 text-success"></i> {{ $row->meal_plan }}
                    </div>
                @endif

                <hr>
               
                @if(!empty($row->activities))
                    @php
                        $activities = is_string($row->activities) ? json_decode($row->activities, true) : $row->activities;
                    @endphp
                        @foreach($activities as $activity)
                            <div class="p-2 mb-2 bg-light text-dark rounded-pill ">
                                <i class="fa fa-check text-success"></i> {{ $activity['title'] }}
                            </div>
                        @endforeach
                @endif
                @if(!empty($row->transfers))
                    <div class="d-flex align-items-center flex-wrap mr-3">
                        <i class="fa fa-car mr-2 text-info"></i> {{ $row->transfers }}
                    </div>
                @endif
            </div> -->
            <div class="d-flex flex-column flex-wrap">

                {{-- First Row: Hotel (left) + Meal Plan (right) --}}
                <div class="d-flex justify-content-between flex-wrap mb-2">
                    @if(!empty($row->hotel))
                        <div class="d-flex align-items-center">
                            <span class="mr-1">{{ $row->hotel }}</span>
                            <i class="fa fa-star text-warning mr-1"></i>
                            <span>Hotel</span>
                        </div>
                    @endif

                    @if(!empty($row->meal_plan))
                        <div class="d-flex align-items-center">
                            <i class="fa fa-cutlery mr-2 text-success"></i> {{ $row->meal_plan }}
                        </div>
                    @endif
                </div>
                <div class="vr"></div>
                {{-- Second Row: Activities (left) + Transfers (right) --}}
                <div class="d-flex justify-content-between flex-wrap">
                    <div class="d-flex flex-column">
                        @if(!empty($row->activities))
                            @php
                                $activities = is_string($row->activities) ? json_decode($row->activities, true) : $row->activities;
                            @endphp
                            @foreach($activities as $activity)
                                <div class="bg-light text-dark rounded-pill">
                                    <i class="fa fa-check text-success"></i> {{ $activity['title'] }}
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if(!empty($row->transfers))
                        <div class="d-flex align-items-start">
                            <i class="fa fa-car mr-2 text-info"></i> {{ $row->transfers }}
                        </div>
                    @endif
                </div>
            </div>

                <hr>
            <!-- Display Stay -->
            @if(!empty($row->stay))
                <div class="d-flex align-items-center">
                    <i class="icofont-beach p-1"></i> {{ $row->stay }}
                </div>
            @endif

             <!-- <div class="d-flex align-items-center mr-3">
                    <i class="fa fa-google mr-2 text-black"></i> 4.5
                </div> -->
                
            <!-- <div class="d-flex justify-content-start ">
              
                @if(!empty($row->activities))
                    @php
                        $activities = is_string($row->activities) ? json_decode($row->activities, true) : $row->activities;
                    @endphp
                        @foreach($activities as $activity)
                            <div class="p-2 mb-2 bg-light text-dark rounded-pill ">
                                <i class="fa fa-check text-success"></i> {{ $activity['title'] }}
                            </div>
                        @endforeach
                @endif
                
            </div> -->
           
        </a>
    </div>

    
    <!-- </div> -->
    <!-- <div class="item-title"> -->
        @if(!empty($row->shortdesc))
            <div class="item-title">{{$row->shortdesc}}</div>
        @endif
    <!-- </div> -->
 
    <hr>
    <div class="d-flex justify-content-between align-items-center flex-wrap px-3">
        <!-- Price Section (Left) -->
        <div class="text-start mb-2">
            <span class="text-danger d-block" style="text-decoration: line-through;">
                {{ $row->display_sale_price }}
            </span>
            <span class="text-price">
                {{ $row->display_price }} {{ __("/per person") }}
            </span>
        </div>

        <!-- Button Section (Right) -->
        <div class="d-flex align-items-center">
            <a @if(!empty($blank)) target="_blank" @endif href="{{ $row->getDetailUrl($include_param ?? true) }}">
                <button type="button" class="btn btn-success btn-sm">Book now</button>
            </a>
        </div>
    </div>



</div>
