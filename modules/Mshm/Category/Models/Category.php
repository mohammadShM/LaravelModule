<?php

namespace Mshm\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Mshm\Course\Models\Course;

/**
 * @method static create(array $array)
 * @method static where(string $string, $id)
 * @method static find($id)
 * @method static whereTitle(string $newTitle)
 * @method static findOrFail($id)
 * @property mixed parent_id
 * @property mixed parentCategory
 * @property mixed id
 */
class Category extends Model
{

    protected $guarded = [];

    /** @noinspection PhpUnused */
    public function getParentAttribute()
    {
        return (is_null($this->parent_id)) ? 'ندارد' : $this->parentCategory->title;
    }

    /** @noinspection PhpUnused */
    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /** @noinspection PhpUnused */
    public function subCategories()
    {
        $this->hasMany(Category::class, 'parent_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
