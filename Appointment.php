<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Appointment extends Model {
    protected $table='appointments';
    protected $fillable=['client','property_id','date','time','status','notes'];
     
}
