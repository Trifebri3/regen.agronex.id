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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('land_id')->nullable()->constrained('lands')->nullOnDelete();
            $table->foreignId('land_plot_id')->nullable()->constrained('land_plots')->nullOnDelete();
            $table->string('device_uid')->unique();
            $table->string('name');
            $table->string('product_line')->index();
            $table->string('firmware_version')->default('v1.4.2');
            $table->string('connection_mode')->default('demo_simulation');
            $table->boolean('is_demo')->default(true);
            $table->string('status')->default('online')->index();
            $table->unsignedTinyInteger('battery_level')->nullable();
            $table->integer('signal_rssi')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->json('sensor_config')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'product_line']);
        });

        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->boolean('is_demo')->default(true);
            $table->timestamp('recorded_at')->index();
            $table->decimal('soil_ph', 4, 2)->nullable();
            $table->decimal('soil_moisture_pct', 5, 2)->nullable();
            $table->decimal('soil_ec_ds_m', 6, 3)->nullable();
            $table->decimal('nitrogen_mg_kg', 8, 2)->nullable();
            $table->decimal('phosphorus_mg_kg', 8, 2)->nullable();
            $table->decimal('potassium_mg_kg', 8, 2)->nullable();
            $table->decimal('water_flow_lpm', 8, 2)->nullable();
            $table->decimal('water_level_cm', 8, 2)->nullable();
            $table->decimal('ambient_temp_c', 5, 2)->nullable();
            $table->decimal('humidity_pct', 5, 2)->nullable();
            $table->decimal('rainfall_mm', 6, 2)->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();

            $table->index(['device_id', 'recorded_at']);
        });

        Schema::create('advisories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('crop_cycle_id')->nullable()->constrained('crop_cycles')->nullOnDelete();
            $table->foreignId('device_id')->nullable()->constrained('devices')->nullOnDelete();
            $table->string('source_type')->default('rule_based');
            $table->boolean('is_demo')->default(true);
            $table->string('severity')->default('info')->index();
            $table->string('category');
            $table->string('title');
            $table->text('recommendation');
            $table->text('rationale')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advisories');
        Schema::dropIfExists('sensor_readings');
        Schema::dropIfExists('devices');
    }
};
