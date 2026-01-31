<?php
// app\Models\Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'animal_id',
        'category_id',
        'name',
        'details',
        'benefits',
        'nutrition',
        'image_path',
        'is_sustainable',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function specifications()
    {
        return $this->hasMany(Specification::class);
    }
}

