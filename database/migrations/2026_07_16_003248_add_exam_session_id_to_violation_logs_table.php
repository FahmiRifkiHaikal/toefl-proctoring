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
        Schema::table('violation_logs', function (Blueprint $table) {
            // Sesuaikan nama tabel pelanggaranmu jika berbeda (misal: 'exam_violations')
            $table->foreignId('exam_session_id')->nullable()->constrained('exam_sessions')->onDelete('cascade')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('violation_logs', function (Blueprint $table) {
            $table->dropForeign(['exam_session_id']);
            $table->dropColumn('exam_session_id');
        });
    }
};
