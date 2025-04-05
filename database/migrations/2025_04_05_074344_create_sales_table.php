<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id('sale_id');  // More explicit naming
                
                // Foreign keys with consistent naming
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('order_line_id');
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('user_id');
                
                // Sales data
                $table->unsignedInteger('quantity')->default(1);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total_price', 10, 2)->storedAs('quantity * unit_price');
                $table->date('sale_date')->index();
                
                // Timestamps with precision
                $table->timestamps(6);  // Microsecond precision
                
                // Composite indexes for common queries
                $table->index(['product_id', 'sale_date']);
                $table->index(['user_id', 'sale_date']);
                $table->index(['order_id', 'sale_date']);
            });

            // Add foreign keys separately for better error handling
            $this->addForeignConstraints();
        }
    }

    protected function addForeignConstraints()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Verify tables exist before adding constraints
            if (Schema::hasTable('orders')) {
                $table->foreign('order_id')
                    ->references('id')
                    ->on('orders')
                    ->onDelete('cascade')
                    ->onUpdate('restrict');
            }

            if (Schema::hasTable('order_lines')) {
                $table->foreign('order_line_id')
                    ->references('id')
                    ->on('order_lines')
                    ->onDelete('cascade')
                    ->onUpdate('restrict');
            }

            if (Schema::hasTable('products')) {
                $table->foreign('product_id')
                    ->references('product_id')
                    ->on('products')
                    ->onDelete('cascade')
                    ->onUpdate('restrict');
            }

            if (Schema::hasTable('users')) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('restrict');
            }
        });
    }

    public function down()
    {
        // First remove foreign key constraints
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['order_line_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['user_id']);
        });
        
        // Then drop the table
        Schema::dropIfExists('sales');
    }
};