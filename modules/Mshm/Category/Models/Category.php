<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace Mshm\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function getParentAttribute(): string
    {
        return (is_null($this->parent_id)) ? 'ندارد' : $this->parentCategory->title;
    }

    public function parentCategory(): BelongsTo
    {
        // return $this->belongsTo(Category::class, 'parent_id');
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function subCategories(): HasMany
    {
       // return $this->hasMany(Category::class, 'parent_id');
       return $this->hasMany(__CLASS__, 'parent_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function path(): string
    {
        return route('categories.show', $this->id);
    }

}
