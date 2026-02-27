<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert the enum columns to plain strings so we don't run
        // into SQL errors when controllers/forms use values that
        // aren't part of the original enum.  This also makes it easier
        // to evolve the list of allowed values without another
        // database migration.
        Schema::table('comics', function (Blueprint $table) {
            // the change() method requires doctrine/dbal, which is already
            // present in composer.lock for this project.
            $table->string('edition_type', 50)->default('regular')->change();
            $table->string('condition', 50)->default('new')->change();
        });

        // migrate any existing data that used the old "collector" value
        // to the more common "collectors" spelling used throughout the
        // current UI; this is optional but keeps things consistent.
        DB::table('comics')
            ->where('edition_type', 'collector')
            ->update(['edition_type' => 'collectors']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // roll back to the previous enum definition.  This is not strictly
        // necessary in most applications, but we'll reproduce the original
        // state so the migration can be rolled back cleanly if needed.
        Schema::table('comics', function (Blueprint $table) {
            $table->enum('edition_type', ['regular', 'limited', 'collector'])
                  ->default('regular')->change();
            $table->enum('condition', ['new', 'like_new', 'used', 'discontinued'])
                  ->default('new')->change();
        });
    }
};
