<?php

namespace Mshm\Category\Repositories;

use Mshm\Category\Models\Category;

class CategoryRepo
{

    public function all()
    {
        return Category::all();
    }

    public function allExceptById($id)
    {
        // return Category::where('id', '!=', $id)->get();
        return $this->all()->filter(function ($item) use ($id) {
            return $item->id != $id;
        });
    }

    public function findById($id)
    {
        return Category::find($id);
    }

    public function store($values)
    {
        return Category::create([
            'title' => $values->title,
            'slug' => $values->slug,
            'parent_id' => $values->parent_id,
        ]);
    }

    public function update($id, $values)
    {
        Category::where('id', $id)->update([
            'title' => $values->title,
            'slug' => $values->slug,
            'parent_id' => $values->parent_id,
        ]);
    }

    public function delete($id)
    {
        Category::where('id', $id)->delete();
    }

}
