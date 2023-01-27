<?php

namespace Modules\Iblog\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Modules\Core\Traits\NamespacedEntity;
use Modules\Iblog\Presenters\PostPresenter;
use Modules\Media\Entities\File;
use Modules\Media\Support\Traits\MediaRelation;
use Modules\Tag\Contracts\TaggableInterface;
use Modules\Tag\Traits\TaggableTrait;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Modules\Isite\Traits\Typeable;
use Modules\Core\Icrud\Traits\hasEventsWithBindings;
use Modules\Isite\Traits\RevisionableTrait;


class Post extends Model implements TaggableInterface
{
  use Translatable, PresentableTrait, NamespacedEntity,
    TaggableTrait, MediaRelation, BelongsToTenant,
    hasEventsWithBindings, Typeable, RevisionableTrait;

  protected static $entityNamespace = 'asgardcms/post';

  public $transformer = 'Modules\Iblog\Transformers\PostTransformer';
  public $entity = 'Modules\Iblog\Entities\Post';
  public $repository = 'Modules\Iblog\Repositories\PostRepository';

  protected $table = 'iblog__posts';

  protected $fillable = [
    'options',
    'category_id',
    'user_id',
    'featured',
    'sort_order',
    'external_id',
    'created_at',
    'date_available'
  ];
  public $translatedAttributes = [
    'title',
    'description',
    'slug',
    'summary',
    'meta_title',
    'meta_description',
    'meta_keywords',
    'translatable_options',
    'status',
  ];
  protected $presenter = PostPresenter::class;

  protected $dates = [
    'date_available'
  ];

  protected $casts = [
    'options' => 'array'
  ];

  protected $revisionEnabled = true;
  protected $revisionCleanup = true;
  protected $historyLimit = 100;
  protected $revisionCreationsEnabled = true;

  public function categories()
  {
    return $this->belongsToMany(Category::class, 'iblog__post__category');
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function user()
  {
    $driver = config('asgard.user.config.driver');

    return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User");
  }

  public function getOptionsAttribute($value)
  {
    try {
      return json_decode(json_decode($value));
    } catch (\Exception $e) {
      return json_decode($value);
    }
  }

  public function getSecondaryImageAttribute()
  {
    $thumbnail = $this->files()->where('zone', 'secondaryimage')->first();
    if (!$thumbnail) {
      $image = [
        'mimeType' => 'image/jpeg',
        'path' => url('modules/iblog/img/post/default.jpg')
      ];
    } else {
      $image = [
        'mimeType' => $thumbnail->mimetype,
        'path' => $thumbnail->path_string
      ];
    }
    return json_decode(json_encode($image));
  }

  public function getMainImageAttribute()
  {
    $thumbnail = $this->files()->where('zone', 'mainimage')->first();
    if (!$thumbnail) {
      if (isset($this->options->mainimage)) {
        $image = [
          'mimeType' => 'image/jpeg',
          'path' => url($this->options->mainimage)
        ];
      } else {
        $image = [
          'mimeType' => 'image/jpeg',
          'path' => url('modules/iblog/img/post/default.jpg')
        ];
      }
    } else {
      $image = [
        'mimeType' => $thumbnail->mimetype,
        'path' => $thumbnail->path_string
      ];
    }
    return json_decode(json_encode($image));

  }

  public function getGalleryAttribute()
  {

    $images = \Storage::disk('publicmedia')->files('assets/iblog/post/gallery/' . $this->id);
    if (count($images)) {
      $response = array();
      foreach ($images as $image) {
        $response = ["mimetype" => "image/jpeg", "path" => $image];
      }
    } else {
      $gallery = $this->filesByZone('gallery')->get();
      $response = [];
      foreach ($gallery as $img) {
        array_push($response, [
          'mimeType' => $img->mimetype,
          'path' => $img->path_string
        ]);
      }

    }

    return json_decode(json_encode($response));
  }

  /**
   * URL post
   * @return string
   */
  public function getUrlAttribute()
  {


    if (empty($this->slug)) {
      $post = $this->getTranslation(\LaravelLocalization::getDefaultLocale());
      $this->slug = $post->slug ?? "";
    }
    if (empty($this->slug)) return "";

    $currentLocale = locale();
    $tenantDomain = (!empty(config("tenancy.mode")) || !empty($this->organization_id)) ? (
       isset(tenant()->id) ? tenant()->domain : tenancy()->find($this->organization_id)->domain
    ) : parse_url(config("app.url"),PHP_URL_HOST);

    if (isset($this->options->urlCoder) && !empty($this->options->urlCoder)) {
      if ($this->options->urlCoder == "onlyPost") {
          $url = !is_null($tenantDomain) ? "https://".$tenantDomain.'/' . $this->slug : \LaravelLocalization::localizeUrl('/' . $this->slug);
          return $url;
      }
    }

    $url = !is_null($tenantDomain) ? "https://".$tenantDomain.'/' . $this->category->slug . '/' . $this->slug : \LaravelLocalization::localizeUrl('/' . $this->category->slug . '/' . $this->slug);
    
    return $url;
    
  }

  /**
   * Magic Method modification to allow dynamic relations to other entities.
   * @return string
   * @var $destination_path
   * @var $value
   */
  public function __call($method, $parameters)
  {
    #i: Convert array to dot notation
    $config = implode('.', ['asgard.iblog.config.relations.post', $method]);

    #i: Relation method resolver
    if (config()->has($config)) {
      $function = config()->get($config);

      return $function($this);
    }

    #i: No relation found, return the call to parent (Eloquent) to handle it.
    return parent::__call($method, $parameters);
  }

}
