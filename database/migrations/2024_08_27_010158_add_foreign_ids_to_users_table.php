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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('user_role_id')->references('id')->on('user_roles')->onDelete('cascade');

            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            
            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['user_role_id']);
                $table->dropForeign(['manager_id']);
                $table->dropForeign(['department_id']);
                $table->dropForeign(['created_by_user_id']);
        });
        
        Schema::dropIfExists('users');
    }
};
