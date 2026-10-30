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
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('location');
            $table->string('province')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('total_area_ha', 10, 2)->default(0);
            $table->integer('elevation_m')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['organization_id', 'code']);
        });

        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('location');
            $table->decimal('area_ha', 10, 2);
            $table->string('ownership_status')->default('owned');
            $table->string('soil_type')->nullable();
            $table->decimal('soil_ph', 4, 2)->nullable();
            $table->text('soil_profile_notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->json('coordinates_geojson')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['organization_id', 'farm_id']);
        });

        Schema::create('land_plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('land_id')->constrained('lands')->cascadeOnDelete();
            $table->string('name');
            $table->string('plot_code');
            $table->decimal('area_ha', 10, 2);
            $table->string('soil_condition')->nullable();
            $table->string('irrigation_type')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('crop_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('land_id')->constrained('lands')->cascadeOnDelete();
            $table->foreignId('land_plot_id')->nullable()->constrained('land_plots')->nullOnDelete();
            $table->string('crop_name');
            $table->string('variety');
            $table->string('cycle_code');
            $table->date('planting_date');
            $table->date('estimated_harvest_date');
            $table->date('actual_harvest_date')->nullable();
            $table->decimal('planting_area_ha', 10, 2);
            $table->decimal('seed_requirement', 10, 2)->default(0);
            $table->string('seed_unit')->default('kg');
            $table->string('cultivation_status')->default('planned')->index();
            $table->decimal('target_yield_kg', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'cultivation_status']);
        });

        Schema::create('farm_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('land_id')->nullable()->constrained('lands')->nullOnDelete();
            $table->foreignId('crop_cycle_id')->nullable()->constrained('crop_cycles')->nullOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('activity_type')->index();
            $table->string('title');
            $table->date('activity_date');
            $table->unsignedInteger('labor_count')->default(0);
            $table->decimal('labor_hours', 8, 2)->nullable();
            $table->decimal('water_volume_liters', 12, 2)->nullable();
            $table->decimal('cost_incurred', 15, 2)->default(0);
            $table->string('status')->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'activity_date']);
        });

        Schema::create('farm_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('crop_cycle_id')->nullable()->constrained('crop_cycles')->nullOnDelete();
            $table->string('input_category')->index();
            $table->string('name');
            $table->string('brand_or_source')->nullable();
            $table->string('unit')->default('kg');
            $table->decimal('quantity_purchased', 12, 2);
            $table->decimal('quantity_used', 12, 2)->default(0);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->date('purchase_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('harvest_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('crop_cycle_id')->constrained('crop_cycles')->cascadeOnDelete();
            $table->date('harvest_date');
            $table->decimal('total_quantity_kg', 12, 2);
            $table->string('product_grade')->default('Grade A');
            $table->string('quality_notes')->nullable();
            $table->decimal('saleable_quantity_kg', 12, 2);
            $table->decimal('sold_quantity_kg', 12, 2)->default(0);
            $table->decimal('unsold_quantity_kg', 12, 2)->default(0);
            $table->decimal('post_harvest_loss_kg', 12, 2)->default(0);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('sales_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('crop_cycle_id')->nullable()->constrained('crop_cycles')->nullOnDelete();
            $table->foreignId('harvest_record_id')->nullable()->constrained('harvest_records')->nullOnDelete();
            $table->string('invoice_number');
            $table->string('buyer_name');
            $table->string('buyer_contact')->nullable();
            $table->string('buyer_type')->default('offtaker');
            $table->string('product_name');
            $table->decimal('quantity_sold_kg', 12, 2);
            $table->decimal('price_per_kg', 15, 2);
            $table->decimal('gross_revenue', 15, 2);
            $table->decimal('distribution_cost', 15, 2)->default(0);
            $table->decimal('net_revenue', 15, 2);
            $table->date('transaction_date');
            $table->string('payment_status')->default('paid')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('farm_id')->constrained('farms')->cascadeOnDelete();
            $table->foreignId('crop_cycle_id')->nullable()->constrained('crop_cycles')->nullOnDelete();
            $table->string('transaction_type')->index();
            $table->string('category')->index();
            $table->string('cost_nature')->default('variable');
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('reference_code')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organization_id', 'transaction_type', 'transaction_date']);
        });

        Schema::create('learning_articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('topic')->index();
            $table->string('difficulty_level')->default('pemula')->index();
            $table->unsignedInteger('reading_time_minutes')->default(5);
            $table->text('summary');
            $table->json('key_takeaways');
            $table->json('step_by_step');
            $table->text('practical_example')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_articles');
        Schema::dropIfExists('financial_records');
        Schema::dropIfExists('sales_records');
        Schema::dropIfExists('harvest_records');
        Schema::dropIfExists('farm_inputs');
        Schema::dropIfExists('farm_activities');
        Schema::dropIfExists('crop_cycles');
        Schema::dropIfExists('land_plots');
        Schema::dropIfExists('lands');
        Schema::dropIfExists('farms');
    }
};
