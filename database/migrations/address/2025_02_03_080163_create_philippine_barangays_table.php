<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'address-sqlite';

    public function up(): void
    {
        Schema::connection($this->getConnection())->create('philippine_barangays', function (Blueprint $table) {
            $table->increments('id');
            $table->string('barangay_code')->index();
            $table->string('barangay_description');
            $table->string('region_code')->index();
            $table->string('province_code')->index();
            $table->string('city_municipality_code')->index();
            $table->timestamps();
        });

    }
    public function down(): void
    {
        Schema::connection($this->getConnection())->dropIfExists('philippine_barangays');
    }
};
