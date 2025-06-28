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
        Schema::table('attendances', function (Blueprint $table) {
            // Add the user_id column
            $table->foreignId('user_id')->constrained('users')->after('id'); // Add after 'id' column for good order
            // Or if you don't want a foreign key constraint for some reason:
            // $table->unsignedBigInteger('user_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // If you added it in up()
            $table->dropColumn('user_id');
        });
    }
};