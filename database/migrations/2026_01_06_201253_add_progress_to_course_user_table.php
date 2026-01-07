<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->unsignedTinyInteger('progress')->default(0);
        });
    }

    public function down()
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};
