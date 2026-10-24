<?php
namespace App\Http\Controllers;
use App\Models\Property;
use App\Models\Lead;
use App\Models\Agent;
use App\Models\Appointment;
use App\Models\Deal;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class AdminDataController {
 private function model(string $type):string {return match($type) {'properties'=>Property::class,'leads'=>Lead::class,'agents'=>Agent::class,'appointments'=>Appointment::class,'deals'=>Deal::class,'posts'=>Post::class,default=>abort(404)};}
 private function rules(string $type):array {return match($type) {
  'properties'=>['title'=>'required|string|max:180','title_ar'=>'nullable|string|max:180','location'=>'required|string|max:180','type'=>['required',Rule::in(['Apartment','Villa','Townhouse','Penthouse'])],'listing_type'=>['required',Rule::in(['Sale','Rent'])],'price'=>'required|integer|min:0','bedrooms'=>'nullable|integer|min:0|max:100','bathrooms'=>'nullable|integer|min:0|max:100','area_sqft'=>'nullable|integer|min:0','featured'=>'boolean','status'=>['required',Rule::in(['Published','Draft','Archived'])],'image'=>'nullable|string|max:4000','backup_image'=>'nullable|string|max:255','description'=>'nullable|string|max:10000','amenities'=>'nullable|array','amenities.*'=>'string|max:120','slug'=>'nullable|string|max:180'],
  'leads'=>['name'=>'required|string|max:120','email'=>'nullable|email|max:190','phone'=>'nullable|string|max:40','property_id'=>'nullable|integer|exists:properties,id','message'=>'nullable|string|max:2000','source'=>'nullable|string|max:120','status'=>['required',Rule::in(['New','Contacted','Qualified','Won','Lost'])]],
  'agents'=>['name'=>'required|string|max:120','role'=>'nullable|string|max:150','email'=>'nullable|email|max:190','phone'=>'nullable|string|max:50','status'=>['required',Rule::in(['Active','Inactive'])],'deals'=>'nullable|integer|min:0'],
  'appointments'=>['client'=>'required|string|max:120','property_id'=>'nullable|integer|exists:properties,id','date'=>'required|date','time'=>'nullable|string|max:10','status'=>['required',Rule::in(['Scheduled','Completed','Cancelled'])],'notes'=>'nullable|string|max:2500'],
  'deals'=>['client'=>'required|string|max:120','property_id'=>'nullable|integer|exists:properties,id','value'=>'required|integer|min:0','stage'=>['required',Rule::in(['Lead','Proposal','Negotiation','Won','Lost'])],'agent'=>'nullable|string|max:120'],
  'posts'=>['title'=>'required|string|max:220','title_ar'=>'nullable|string|max:220','category'=>'nullable|string|max:120','excerpt'=>'nullable|string|max:2000','status'=>['required',Rule::in(['Published','Draft'])],'date'=>'nullable|date'],
  default=>abort(404)
 };}
 public function bootstrap(Request $r){abort_unless($r->user()?->is_admin,403);return response()->json([
  'properties'=>Property::latest()->get(),'leads'=>Lead::latest()->get(),'agents'=>Agent::latest()->get(),
  'appointments'=>Appointment::orderBy('date')->get(),'deals'=>Deal::latest()->get(),'posts'=>Post::latest()->get(),
  'settings'=>Setting::pluck('value','key'),
 ]);}
 public function store(Request $r,string $type){abort_unless($r->user()?->is_admin,403);$class=$this->model($type);$v=$r->validate($this->rules($type));if($type==='properties'){$v['slug']=Str::slug($v['title']).'-'.Str::lower(Str::random(6));$v['backup_image']=$v['backup_image']??'assets/img/architecture-1.svg';}return response()->json(['data'=>$class::create($v)],201);}
 public function update(Request $r,string $type,int $id){abort_unless($r->user()?->is_admin,403);$class=$this->model($type);$v=$r->validate($this->rules($type));if($type==='properties')unset($v['slug']);$row=$class::findOrFail($id);$row->update($v);return ['data'=>$row->fresh()];}
 public function destroy(Request $r,string $type,int $id){abort_unless($r->user()?->is_admin,403);$class=$this->model($type);$class::findOrFail($id)->delete();return response()->noContent();}
 public function settings(Request $r){abort_unless($r->user()?->is_admin,403);$v=$r->validate(['company'=>'required|string|max:150','email'=>'required|email|max:190','phone'=>'nullable|string|max:70','whatsapp'=>'nullable|string|max:70','address'=>'nullable|string|max:190','currency'=>['required',Rule::in(['AED'])],'facebook'=>'nullable|url|max:500','instagram'=>'nullable|url|max:500']);foreach($v as $key=>$value)Setting::updateOrCreate(['key'=>$key],['value'=>$value??'']);return ['success'=>true];}
 public function upload(Request $r){abort_unless($r->user()?->is_admin,403);$r->validate(['image'=>'required|image|mimes:jpg,jpeg,png,webp|max:4096']);$path=$r->file('image')->store('properties','public');return ['url'=>Storage::url($path)];}
}
