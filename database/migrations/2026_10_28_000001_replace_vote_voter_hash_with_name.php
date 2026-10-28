<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasIndex('votes', 'votes_exhibition_id_index')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->index('exhibition_id', 'votes_exhibition_id_index');
            });
        }

        if (! Schema::hasColumn('votes', 'voter_name')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->string('voter_name')->nullable();
            });
        }

        if (Schema::hasIndex('votes', 'votes_exhibition_id_voter_hash_unique')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropUnique('votes_exhibition_id_voter_hash_unique');
            });
        }

        if (Schema::hasColumn('votes', 'voter_hash')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropColumn('voter_hash');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('votes', 'voter_hash')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->string('voter_hash', 64)->nullable();
            });
        }

        DB::table('votes')->whereNull('voter_hash')->orderBy('id')->get(['id'])->each(function ($vote) {
            DB::table('votes')->where('id', $vote->id)->update([
                'voter_hash' => hash('sha256', 'vote-'.$vote->id),
            ]);
        });

        if (! Schema::hasIndex('votes', 'votes_exhibition_id_voter_hash_unique')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->unique(['exhibition_id', 'voter_hash']);
            });
        }

        if (Schema::hasIndex('votes', 'votes_exhibition_id_index')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropIndex('votes_exhibition_id_index');
            });
        }

        if (Schema::hasColumn('votes', 'voter_name')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropColumn('voter_name');
            });
        }
    }
};