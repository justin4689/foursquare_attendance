<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $indexes = DB::select("SHOW INDEX FROM members WHERE Key_name = 'members_phone_unique'");
        if (count($indexes) > 0) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropUnique(['phone']);
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot restore unique constraint — duplicate phone values exist in the database
    }
};
