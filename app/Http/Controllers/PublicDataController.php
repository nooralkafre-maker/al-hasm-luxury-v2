<?php
namespace App\Http\Controllers;
use App\Models\Property;
use App\Models\Post;
use App\Models\Agent;
use App\Models\Setting;
use App\Models\Lead;
use Illuminate\Http\Request;
class PublicDataController {
 public function index(){return response()->json([
  'properties'=>Property::where('status','Published')->latest()->get(),
  'posts'=>Post::where('status','Published')->latest()->get(),
  'agents'=>Agent::where('status','Active')->get(),
  'settings'=>Setting::pluck('value','key'),
 ]);}
 public function properties(Request $r){return Property::where('status','Published')
  ->when($r->filled('location'),fn($q)=>$q->where('location',$r->input('location')))
  ->when($r->filled('type'),fn($q)=>$q->where('type',$r->input('type')))
  ->when($r->filled('listing_type'),fn($q)=>$q->where('listing_type',$r->input('listing_type')))
  ->when($r->filled('search'),fn($q)=>$q->where(function($t)use($r){$s=$r->input('search');$t->where('title','like','%'.$s.'%')->orWhere('location','like','%'.$s.'%');}))
  ->paginate(18);}
 public function property(int $id){return Property::where('status','Published')->findOrFail($id);}
 public function inquiry(Request $r){$data=$r->validate([
  'name'=>'required|string|max:120','email'=>'required|email|max:190','phone'=>'required|string|max:40',
  'message'=>'nullable|string|max:2000','source'=>'nullable|string|max:80','subject'=>'nullable|string|max:160',
  'property_location'=>'nullable|string|max:200','property_id'=>'nullable|integer|exists:properties,id',
 ]);
  $message=trim(implode(' — ',array_filter([$data['subject']??null,$data['property_location']??null,$data['message']??null])));
  $lead=Lead::create(['name'=>$data['name'],'email'=>$data['email'],'phone'=>$data['phone'],'property_id'=>$data['property_id']??null,'source'=>$data['source']??'Website','status'=>'New','message'=>$message]);
  return response()->json(['message'=>'Your enquiry has been received.','id'=>$lead->id],201);
 }
}
