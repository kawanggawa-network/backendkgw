<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Program Category model.
 * @package App\Models\Program
 */
class Category extends Model
{
    use HasSlug;

    /**
     * Fillable attributes.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'parent_id',
        'slug',
        'title',
        'icon',
        'description',
        'is_featured',
        'is_hidden',
        'button_text',
        'sort',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

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
     * Mapping field form.
     *
     * @return array
     */
    public static function mappingFieldForm()
    {
        return [
            'title' => [
                'label' => 'Title',
                'type' => 'text',
                'class' => 'col-12',
            ],
            'description' => [
                'label' => 'Description',
                'type' => 'textarea',
                'class' => 'col-12'
            ],
            'is_featured' => [
                'label' => 'Featured',
                'type' => 'checkbox',
                'class' => 'col-12',
            ],
            'is_hidden' => [
                'label' => 'Hidden',
                'type' => 'checkbox',
                'class' => 'col-12',
            ],
            'button_text' => [
                'label' => 'Button Text',
                'type' => 'text',
                'class' => 'col-12',
                'default' => 'Donasi'
            ],
            'icon' => [
                'label' => 'Icon',
                'type' => 'file',
                'class' => 'col-12'
            ],
        ];
    }

    public function getIconUrlAttribute()
    {
        if (is_null($this->icon)) {
            return asset('assets/images/category-default-icon.png');
        }

        return \Storage::url($this->icon);
    }

    /**
     * Up position.
     *
     * @return void
     */
    public function upPosition()
    {
        $before = Category::where('sort', '<', $this->sort)->orderBy('sort', 'desc')->first();
        $sortBefore = $before->sort;
        $before->sort = $this->attributes['sort'];
        $this->attributes['sort'] = $sortBefore;
        self::save();
        $before->save();
    }

    /**
     * Down position.
     *
     * @return void
     */
    public function downPosition()
    {
        $after = Category::where('sort', '>', $this->sort)->orderBy('sort', 'asc')->first();
        $sortAfter = $after->sort;
        $after->sort = $this->attributes['sort'];
        $this->attributes['sort'] = $sortAfter;
        self::save();
        $after->save();
    }

    /**
     * Get API Response.
     *
     * @return array
     */
    public function getResponseAttribute()
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'icon_url' => $this->icon_url,
            'description' => $this->description,
            'is_featured' => boolval($this->is_featured),
        ];
    }

    /**
     * Boot method.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate sort key
            $lastSort = 0;
            $last = Category::orderBy('sort', 'desc')->first();
            if (!is_null($last)) {
                $lastSort = $last->sort;
            }

            $model->sort = $lastSort + 1;
        });
    }
}
