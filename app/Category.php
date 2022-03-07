<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // use Columns;

    public function parent() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public static function tree() {
        $return = collect();
        $category = Category::with('parent')->whereNull('parent_id')->get();
        foreach($category as $i => $grandparent) {
            $string = $grandparent->name;

            foreach($grandparent->parent as $i => $parent) {
                $string .= " > ". $parent->name;

                foreach($parent->parent as $i => $child) {
                    $return->push(collect([
                        'id' => $child->id,
                        'name' => $string . " > ". $child->name
                    ]));
                }
            }
        }

        return $return;
    }
}
