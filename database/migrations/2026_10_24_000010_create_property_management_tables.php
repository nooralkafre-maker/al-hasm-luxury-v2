<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up():void {
  Schema::create('properties',function(Blueprint $t){$t->id();$t->string('title');$t->string('title_ar')->nullable();$t->string('slug')->unique();$t->string('location');$t->string('type')->default('Apartment');$t->string('listing_type')->default('Sale');$t->unsignedBigInteger('price')->default(0);$t->unsignedInteger('bedrooms')->default(0);$t->unsignedInteger('bathrooms')->default(0);$t->unsignedInteger('area_sqft')->default(0);$t->boolean('featured')->default(false);$t->string('status')->default('Draft');$t->text('image')->nullable();$t->text('backup_image')->nullable();$t->longText('description')->nullable();$t->json('amenities')->nullable();$t->timestamps();$t->index(['status','listing_type']);$t->index('location');});
  Schema::create('leads',function(Blueprint $t){$t->id();$t->string('name');$t->string('email')->nullable();$t->string('phone')->nullable();$t->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();$t->string('source')->default('Website');$t->string('status')->default('New');$t->text('message')->nullable();$t->timestamps();$t->index(['status','created_at']);});
  Schema::create('agents',function(Blueprint $t){$t->id();$t->string('name');$t->string('role')->nullable();$t->string('email')->nullable();$t->string('phone')->nullable();$t->string('status')->default('Active');$t->unsignedInteger('deals')->default(0);$t->timestamps();});
  Schema::create('appointments',function(Blueprint $t){$t->id();$t->string('client');$t->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();$t->date('date');$t->string('time')->nullable();$t->string('status')->default('Scheduled');$t->text('notes')->nullable();$t->timestamps();});
  Schema::create('deals',function(Blueprint $t){$t->id();$t->string('client');$t->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();$t->unsignedBigInteger('value')->default(0);$t->string('stage')->default('Lead');$t->string('agent')->nullable();$t->timestamps();});
  Schema::create('posts',function(Blueprint $t){$t->id();$t->string('title');$t->string('title_ar')->nullable();$t->string('category')->default('Insights');$t->text('excerpt')->nullable();$t->string('status')->default('Draft');$t->date('date')->nullable();$t->timestamps();});
  Schema::create('settings',function(Blueprint $t){$t->id();$t->string('key')->unique();$t->text('value')->nullable();$t->timestamps();});
 }
 public function down():void {foreach(['settings','posts','deals','appointments','agents','leads','properties'] as $t)Schema::dropIfExists($t);}
};
