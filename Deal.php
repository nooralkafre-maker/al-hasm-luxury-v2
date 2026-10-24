<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Deal extends Model {
    protected $table='deals';
    protected $fillable=['client','property_id','value','stage','agent'];
     
}
