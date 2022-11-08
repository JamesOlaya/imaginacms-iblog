@extends('layouts.master')

@section('meta')
  @if(isset($category->id))
    @include('iblog::frontend.partials.category.metas')
  @endif
@stop
@section('title')
  {{isset($category->title)? $category->title: trans("iblog::routes.blog.index.index")}}  | @parent
@stop
@section('content')
  <section id="layout2"
           class="  {{isset($category->id) ? 'iblog-index-category iblog-index-category-'.$category->id.' blog-category-'.$category->id : ''}} py-5">
    <div id="content_index_blog"
         class="  {{isset($category->id) ? 'iblog-index-category iblog-index-category-'.$category->id.' blog-category-'.$category->id : ''}} py-5">
      <div class="container">
        <div class="row">
          @include('iblog::frontend.partials.breadcrumb')
        </div>
      </div>
      <div class="container">
        <div class="row">
          {{-- Top Content , Products, Pagination --}}
          <div class="posts col-12 col-md-8">
            <div class="title h4 my-3">
              <h4>
                {{isset($category->title) ? $category->title : ""}}
              </h4>
            </div>
            <livewire:isite::items-list
              moduleName="Iblog"
              itemComponentName="isite::item-list"
              itemComponentNamespace="Modules\Isite\View\Components\ItemList"
              :configLayoutIndex="['default' => 'two',
                                                        'options' => [
                                                            'two'=> [
                                                                'name' => 'two',
                                                                'class' => 'col-12 col-md-6 mt-2',
                                                                'icon' => 'fa fa-square-o',
                                                                'status' => true],
                                                                ]
                                                                ]"
              :itemComponentAttributes="['withViewMoreButton' => false,
                                    'withCategory'=>true,
                                    'withSummary'=>true,
                                    'withCreatedDate'=>true,
                                    'layout'=>'item-list-layout-6',
                                    'imageAspect'=>'3/2',
                                    'imageObject'=>'cover',
                                    'imageBorderRadio'=>'10',
                                    'imageBorderStyle'=>'solid',
                                    'imageBorderWidth'=>'0',
                                    'imageBorderColor'=>'#000000',
                                    'imagePadding'=>'0',
                                    'withTitle'=>true,
                                    'titleAlign'=>'',
                                    'titleTextSize'=>'20',
                                    'titleTextWeight'=>'font-weight-bold',
                                    'titleTextTransform'=>'',
                                    'formatCreatedDate'=>'d \d\e M,Y',
                                    'summaryAlign'=>'text-left',
                                    'summaryTextSize'=>'15',
                                    'summaryTextWeight'=>'font-weight-normal',
                                    'numberCharactersSummary'=>'130',
                                    'categoryAlign'=>'text-left',
                                    'categoryTextSize'=>'14',
                                    'categoryTextWeight'=>'font-weight-normal',
                                    'createdDateAlign'=>'text-left',
                                    'createdDateTextSize'=>'12',
                                    'createdDateTextWeight'=>'font-weight-normal',
                                    'buttonAlign'=>'text-left',
                                    'buttonLayout'=>'',
                                    'buttonIcon'=>'fa fa-angle-right',
                                    'buttonIconLR'=>'left',
                                    'buttonColor'=>'dark',
                                    'viewMoreButtonLabel'=>'iblog::common.layouts.viewMore',
                                    'withImageOpacity'=>'false',
                                    'imageOpacityColor'=>'opacity-dark',
                                    'imageOpacityDirection'=>'opacity-all',
                                    'orderClasses'=>[
                                    'photo'=>'order-0',
                                    'title'=>'order-2',
                                    'date'=>'order-5',
                                    'categoryTitle'=>'order-1',
                                    'summary'=>'order-3',
                                    'viewMoreButton'=>'order-5'
                                    ],
                                    'imagePosition'=>'1',
                            'imagePositionVertical'=>'align-self-center',
                            'contentPositionVertical'=>'align-self-center',
                            'contentPadding'=>'0',
                            'contentBorder'=>'0',
                            'contentBorderColor'=>'#ffffff',
                             'contentBorderRounded'=>'0',
                            'contentMarginInsideX'=>'mx-0',
                            'contentBorderShadows'=>'none',
                            'contentBorderShadowsHover'=>'',
                            'titleColor'=>'text-dark',
                            'summaryColor'=>'text-dark',
                            'categoryColor'=>'text-dark',
                            'createdDateColor'=>'text-dark',
                            'titleMarginT'=>'mt-0 mt-md-1',
                            'titleMarginB'=>'mb-0 mb-md-1',
                            'summaryMarginT'=>'mt-1 mt-md-2 mt-lg-1',
                            'summaryMarginB'=>'mb-1 mb-md-4 mb-lg-2',
                            'categoryMarginT'=>'mt-1 mt-md-3',
                            'categoryMarginB'=>'mb-1 mt-md-2',
                            'categoryOrder'=>'1',
                            'createdDateMarginT'=>'mt-2 mt-md-3',
                            'createdDateMarginB'=>'mb-3',
                            'createdDateOrder'=>'5',
                            'buttonMarginT'=>'mt-md-0 mt-4',
                            'buttonMarginB'=>'mb-md-2 mb-2',
                            'buttonOrder'=>'5',
                            'titleLetterSpacing'=>'0',
                            'summaryLetterSpacing'=>'0',
                            'categoryLetterSpacing'=>'0',
                            'createdDateLetterSpacing'=>'0',
                            'titleVineta'=>'',
                            'titleVinetaColor'=>'text-dark',
                            'buttonSize'=>'button-normal',
                            'buttonTextSize'=>'14',
                            'itemBackgroundColor'=>'#ffffff',
                            'itemBackgroundColorHover'=>'#ffffff',
                            'titleHeight'=>'40',
                            'summaryHeight'=>'80'
                                    ]"
              entityName="Post"
              :showTitle="false"
              :params="['filter' => ['category' => $category->id ?? null],'take' => 8]"
              :responsiveTopContent="['mobile'=>false,'desktop'=>false]"
            />
          </div>
          {{-- Sidebar --}}
          <div class="sidebar col-12 col-md-4 px-5">
            <div class="row">
              <div class="col-12 my-2 pl-lg-5">
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
              <div class="row">
                <div class="col-12 pl-lg-5">
                  <h4 class="mt-1 mb-2 mx-3">{{trans('iblog::common.layouts.titlePostRecent')}}</h4>
                  <livewire:isite::items-list
                    moduleName="Iblog"
                    itemComponentName="isite::item-list"
                    itemComponentNamespace="Modules\Isite\View\Components\ItemList"
                    :configLayoutIndex="['default' => 'one',
                                                            'options' => [
                                                                'one' => [
                                                                    'name' => 'one',
                                                                    'class' => 'col-12 my-3 pl-md-5',
                                                                    'icon' => 'fa fa-align-justify',
                                                                    'status' => true],
                                                        ],
                                                        ]"
                    :itemComponentAttributes="[
                                        'withViewMoreButton'=>false,
                                        'withCategory'=>false,
                                        'withSummary'=>false,
                                        'withCreatedDate'=>true,
                                        'layout'=>'item-list-layout-7',
                                        'imageAspect'=>'4/3',
                                        'imageObject'=>'cover',
                                        'imageBorderRadio'=>'0',
                                        'imageBorderStyle'=>'solid',
                                        'imageBorderWidth'=>'0',
                                        'imageBorderColor'=>'#000000',
                                        'imagePadding'=>'0',
                                        'withTitle'=>true,
                                        'titleAlign'=>'',
                                        'titleTextSize'=>'14',
                                        'titleTextWeight'=>'font-weight-bold',
                                        'titleTextTransform'=>'',
                                        'formatCreatedDate'=>'d \d\e M,Y',
                                        'summaryAlign'=>'text-left',
                                        'summaryTextSize'=>'16',
                                        'summaryTextWeight'=>'font-weight-normal',
                                        'numberCharactersSummary'=>'100',
                                        'categoryAlign'=>'text-left',
                                        'categoryTextSize'=>'18',
                                        'categoryTextWeight'=>'font-weight-normal',
                                        'createdDateAlign'=>'text-left',
                                        'createdDateTextSize'=>'11',
                                        'createdDateTextWeight'=>'font-weight-normal',
                                        'buttonAlign'=>'text-left',
                                        'buttonLayout'=>'rounded',
                                        'buttonIcon'=>'',
                                        'buttonIconLR'=>'left',
                                        'buttonColor'=>'primary',
                                        'viewMoreButtonLabel'=>'iblog::common.layouts.viewMore',
                                        'withImageOpacity'=>false,
                                        'imageOpacityColor'=>'opacity-dark',
                                        'imageOpacityDirection'=>'opacity-all',
                                        'orderClasses'=>[
                                        'photo'=>'order-0',
                                        'title'=>'order-1',
                                        'date'=>'order-4',
                                        'categoryTitle'=>'order-3',
                                        'summary'=>'order-2',
                                        'viewMoreButton'=>'order-5'
                                        ],
                                        'imagePosition'=>'2',
                                        'imagePositionVertical'=>'align-self-star',
                                        'contentPositionVertical'=>'align-self-star',
                                        'contentPadding'=>'0',
                                        'contentBorder'=>'0',
                                        'contentBorderColor'=>'#e3e3e3',
                                        'contentBorderRounded'=>'0',
                                        'contentMarginInsideX'=>'mx-0',
                                        'contentBorderShadows'=>'none',
                                        'contentBorderShadowsHover'=>'',
                                        'titleColor'=>'text-dark',
                                        'summaryColor'=>'text-dark',
                                        'categoryColor'=>'text-primary',
                                        'createdDateColor'=>'text-dark',
                                        'titleMarginT'=>'mt-0',
                                        'titleMarginB'=>'mb-0 mb-md-2',
                                        'summaryMarginT'=>'mt-0',
                                        'summaryMarginB'=>'mb-2',
                                        'categoryMarginT'=>'mt-0',
                                        'categoryMarginB'=>'mb-2',
                                        'categoryOrder'=>'3',
                                        'createdDateMarginT'=>'mt-0 mt-md-3',
                                        'createdDateMarginB'=>'mb-0 mb-md-2',
                                        'createdDateOrder'=>'4',
                                        'buttonMarginT'=>'mt-0',
                                        'buttonMarginB'=>'mb-0',
                                        'buttonOrder'=>'5',
                                        'titleLetterSpacing'=>'0',
                                        'summaryLetterSpacing'=>'0',
                                        'categoryLetterSpacing'=>'0',
                                        'createdDateLetterSpacing'=>'0',
                                        'titleVineta'=>'',
                                        'titleVinetaColor'=>'text-dark',
                                        'buttonSize'=>'button-normal',
                                        'buttonTextSize'=>'16',
                                        'itemBackgroundColor'=>'#ffffff',
                                        'itemBackgroundColorHover'=>'#ffffff',
                                        'titleHeight'=>40,
                                        'summaryHeight'=>100,
                                            ]"
                    entityName="Post"
                    :showTitle="false"
                    :pagination="['show'=>false]"

                    :params="['take'=>3,'filter' => ['category' => $category->id ?? null]]"
                    :responsiveTopContent="['mobile'=>false,'desktop'=>false]"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div>
  </section>
@stop
