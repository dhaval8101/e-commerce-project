<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'categories';    

    protected $fillable = [
        'id',
        'name',
    ];
    public function subCategories()
{
    return $this->hasMany(SubCategory::class);
}
public function products()
{
    return $this->hasMany(Product::class);
}   

}