<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Content Model (Article, Banner, Page).
 */
class Content extends Model
{
    /**
     * Fillable attribute.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'slug',
        'title',
        'is_free',
        'content_text',
        'content_image',
        'content_url',
        'visitor',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_free' => 'boolean',
    ];

    /**
     * Get formatted ID.
     *
     * @return string
     */
    public function getFormattedIdAttribute()
    {
        return '#' . $this->id;
    }

    /**
     * Generate slug for this content.
     *
     * @return void
     */
    public function generateSlug()
    {
        $number = 0;

        do {
            $text = $number;
            if ($text == 0) {
                $text = '';
            }

            $slug = \Str::slug($this->title, '-') . $text;
            $count = Content::whereSlug($slug)->count();

            $number++;
        } while ($count > 0);

        $this->attributes['slug'] = $slug;
        self::save();
    }

    /**
     * Get content image url attribute.
     *
     * @return string
     */
    public function getContentImageUrlAttribute()
    {
        if (!is_null($this->getAttribute('content_image'))) {
            return asset(\Storage::url($this->getAttribute('content_image')));
        }
    }

    /**
     * Scope a query to only include page.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePage($query)
    {
        return $query->where('type', 'page');
    }

    /**
     * Mapping field form.
     *
     * @return array
     */
    public static function mappingFieldFormPage()
    {
        return [
            'title' =>[
                'label' => 'Title',
                'type' => 'text',
                'class' => 'col-12',
            ],
            'content_text' =>[
                'label' => 'Content',
                'type' => 'wysiwyg',
                'class' => 'col-12'
            ],
        ];
    }

    /**
     * Get response for page object.
     *
     * @return array
     */
    public function getResponsePageAttribute()
    {
        return [
            'title' => $this->title,
            'content_text' => $this->content_text
        ];
    }

    /**
     * Scope a query to only include page.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeArticle($query)
    {
        return $query->where('type', 'article');
    }

    /**
     * Mapping field form.
     *
     * @return array
     */
    public static function mappingFieldFormArticle()
    {
        return [
            'title' =>[
                'label' => 'Title',
                'type' => 'text',
                'class' => 'col-12',
            ],
            'content_text' =>[
                'label' => 'Content',
                'type' => 'wysiwyg',
                'class' => 'col-12'
            ],
            'category_id' => [
                'label' => 'Category',
                'type' => 'select',
                'class' => 'col-12 col-md-12',
                'options' => \App\Models\Category::orderBy('name')->get()->pluck('option_dropdown')
            ],
            'is_free' => [
                'label' => 'Free Article?',
                'type' => 'checkbox',
                'class' => 'col-12 col-md-12',
                'value' => 1,
            ],
        ];
    }

    /**
     * Get response for article excerpt object.
     *
     * @return array
     */
    public function getResponseArticleExcerptAttribute()
    {
        return [
            'title' => $this->title,
            'is_free' => $this->is_free,
            'slug' => $this->slug,
            'content_text' => substr(strip_tags($this->content_text), 0, 150),
            'content_image' => $this->content_image_url,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'visitor' => $this->visitor,
        ];
    }

    /**
     * Get response for article detail object.
     *
     * @return array
     */
    public function getResponseArticleDetailAttribute()
    {
        return [
            'title' => $this->title,
            'is_free' => $this->is_free,
            'slug' => $this->slug,
            'content_text' => $this->content_text,
            'content_image' => $this->content_image_url,
            'category_name' => $this->category_name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'visitor' => $this->visitor,
        ];
    }

    /**
     * Scope a query to only include banner.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBanner($query)
    {
        return $query->where('type', 'banner');
    }

    /**
     * Mapping field form.
     *
     * @return array
     */
    public static function mappingFieldFormBanner()
    {
        return [
            'slug' => [
                'label' => 'Category',
                'type' => 'select',
                'class' => 'col-12 col-md-12',
                'options' => [
                    [
                        'value' => 'heading',
                        'text' => 'Heading'
                    ],
                    [
                        'value' => 'promotion',
                        'text' => 'Promotion'
                    ],
                    [
                        'value' => 'ads',
                        'text' => 'Ads'
                    ],
                ]
            ],
            'title' =>[
                'label' => 'Title (Optional)',
                'type' => 'text',
                'class' => 'col-12',
            ],
            'content_url' =>[
                'label' => 'URL/Link (Optional)',
                'type' => 'text',
                'class' => 'col-12',
            ],
        ];
    }

    /**
     * Get response for article excerpt object.
     *
     * @return array
     */
    public function getResponseBannerAttribute()
    {
        return [
            'content_image' => $this->content_image_url,
            'title' => $this->title,
            'url' => $this->content_url,
        ];
    }

    /**
     * Categories relation.
     *
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Category::class, 'content_to_category', 'content_id', 'category_id');
    }

    /**
     * Get category attribute.
     *
     * @return integer
     */
    public function getCategoryIdAttribute()
    {
        $first = $this->categories()->first();
        if (!is_null($first)) {
            return $first->id;
        }
    }

    /**
     * Get category name.
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        $category = $this->categories()->first();
        if (!is_null($category)) {
            return $category->name;
        }
    }

    /**
     * Image link HTML.
     *
     * @return string
     */
    public function getContentImageLinkAttribute()
    {
        $link = $this->getContentImageUrlAttribute();
        if (is_null($link)) {
            return '-';
        }

        return '<a href="' . $link . '" target="_blank"><span class="fa fa-external-link"></span></a>';
    }
}
