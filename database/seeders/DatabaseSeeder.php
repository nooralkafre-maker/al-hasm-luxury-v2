<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Property;
use App\Models\Lead;
use App\Models\Agent;
use App\Models\Appointment;
use App\Models\Deal;
use App\Models\Post;
use App\Models\Setting;
class DatabaseSeeder extends Seeder {
 public function run():void {
  $data=json_decode(file_get_contents(__DIR__.'/demo_data.json'),true,512,JSON_THROW_ON_ERROR);
  foreach(['properties'=>Property::class,'leads'=>Lead::class,'agents'=>Agent::class,'appointments'=>Appointment::class,'deals'=>Deal::class,'posts'=>Post::class] as $key=>$class)
   foreach($data[$key] as $row) {unset($row['created_at']);$class::updateOrCreate(['id'=>$row['id']],$row);}
  foreach($data['settings'] as $k=>$v) Setting::updateOrCreate(['key'=>$k],['value'=>$v]);
  $email=env('ADMIN_EMAIL');$password=env('ADMIN_PASSWORD');
  if($email && $password && strlen($password)>=12) User::updateOrCreate(['email'=>$email],['name'=>'AL HASM Administrator','password'=>Hash::make($password),'is_admin'=>true]);
  else $this->command?->warn('No admin created. Set ADMIN_EMAIL and ADMIN_PASSWORD (12+ chars) in .env, then run php artisan db:seed.');
  $this->command?->info('AL HASM sample content seeded successfully. Replace demo data before public launch.');
 }
}
