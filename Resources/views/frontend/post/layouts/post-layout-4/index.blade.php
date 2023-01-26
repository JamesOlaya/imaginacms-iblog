@extends('layouts.master')

@section('meta')
  @include('iblog::frontend.partials.post.metas')
@stop

@section('title')
  {{ $post->title }} | @parent
@stop
@section('content')
  <div id="postLayout4"
       class="  {{isset($category->id) ? 'iblog-index-category iblog-index-category-'.$category->id.' blog-category-'.$category->id : ''}} py-2">
    <div class="container">
      <div class="row">
        @include('iblog::frontend.partials.breadcrumb')
      </div>
    </div>
    <div class="page blog single single-{{$category->slug}} single-{{$category->id}}">

      <div class="container">
        <div class="row">
          {{--sidebar--}}
          <div class="col-12 col-md-3">
            <livewire:isite::filters :filters="['categories' => [
                                                                'title' => 'iblog::category.plural',
                                                                'name' => 'categories',
                                                                'typeTitle' => 'titleOfTheConfig',
                                                                'status' => true,
                                                                'isExpanded' => true,
                                                                'type' => 'tree',
                                                                'repository' => 'Modules\Iblog\Repositories\CategoryRepository',
                                                                'entityClass' => 'Modules\Iblog\Entities\Category',
                                                                'params' => ['filter' => ['internal' => false]],
                                                                'emitTo' => 'itemsListGetData',
                                                                'repoAction' => null,
                                                                'repoAttribute' => null,
                                                                'listener' => null,
                                                                'layout' => 'default',
                                                                'classes' => 'col-12'
                                                            ]]"/>
          </div>
          {{--article--}}
          <div class="col-12 col-md-9 p-md-4">
            <h3 class="title">{{ $post->title }}</h3>
            <div class="my-1">
              <x-media::single-image imgClasses=""
                                     :mediaFiles="$post->mediaFiles()"
                                     :isMedia="true" :alt="$post->title"/>
            </div>
            <div class="create-date my-4">
              {{ $post->created_at->format('d \d\e M,Y')}}
            </div>
            <div class="page-body description my-4 text-justify">
              {!! $post->description !!}
            </div>
            <div class="social-share d-flex justify-content-start align-items-center my-5">
              <div class="mr-2">{{trans('iblog::common.social.share')}}:</div>
              <div class="sharethis-inline-share-buttons"></div>
            </div>
          </div>
        </div>
        <div class="mb-5">
          <h5>{{trans('iblog::common.layouts.titleRelatedPosts')}}</h5>
          <x-isite::carousel.owl-carousel
            id="Articles"
            repository="Modules\Iblog\Repositories\PostRepository"
            :params="['take' => 20,'filter' => ['category' => $category->id]]"
            :margin="30"
            :loops="false"
            :dots="false"
            :nav="false"
            mediaImage="mainimage"
            :autoplay="false"
            :responsive="[300 => ['items' =>  1],700 => ['items' =>  2], 1024 => ['items' => 3]]"
            :itemComponentAttributes="config('asgard.iblog.config.itemComponentAttributesBlog')"
          />
        </div>
      </div>
    </div>
  </div>
@stop