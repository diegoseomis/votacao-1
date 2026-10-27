<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', fn (Blueprint $t) => $t->boolean('is_admin')->default(false));
        Schema::create('exhibitions', function (Blueprint $t) {
            $t->id(); $t->string('title'); $t->text('description')->nullable(); $t->string('status')->default('draft'); $t->timestamps();
        });
        Schema::create('artworks', function (Blueprint $t) {
            $t->id(); $t->foreignId('exhibition_id')->constrained()->cascadeOnDelete();
            $t->string('title'); $t->string('artist'); $t->text('description')->nullable(); $t->string('image_path'); $t->timestamps();
        });
        Schema::create('votes', function (Blueprint $t) {
            $t->id(); $t->foreignId('exhibition_id')->constrained()->cascadeOnDelete();
            $t->foreignId('artwork_id')->constrained()->cascadeOnDelete(); $t->string('voter_hash', 64); $t->timestamps();
            $t->unique(['exhibition_id', 'voter_hash']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('votes'); Schema::dropIfExists('artworks'); Schema::dropIfExists('exhibitions');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_admin'));
    }
};
