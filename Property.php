<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Property extends Model {
    protected $table='properties';
    protected $fillable=['title','title_ar','slug','location','type','listing_type','price','bedrooms','bathrooms','area_sqft','featured','status','image','backup_image','description','amenities'];
    protected function casts(): array {return ['amenities'=>'array','featured'=>'boolean','price'=>'integer','area_sqft'=>'integer'];}
}
