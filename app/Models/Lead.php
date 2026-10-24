<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lead extends Model {
    protected $table='leads';
    protected $fillable=['name','email','phone','property_id','message','status','source'];
     
}
