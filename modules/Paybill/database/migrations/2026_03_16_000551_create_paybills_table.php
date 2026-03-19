<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pay_bills', function (Blueprint $table) {

            $table->id();

            // payment method type
            $table->enum('method', [
                'bkash',
                'nagad',
                'rocket',
                'bank',
                'qr'
            ])->index();

            // step title or bank name
            $table->string('title')->nullable();

            // instruction
            $table->text('description')->nullable();

            // step order for mobile banking
            $table->unsignedInteger('step_no')->nullable();

            // extra data (bank info / qr image)
            $table->json('meta')->nullable();

            // created by
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->enum('status', ['active','inactive'])
                ->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paybills');
    }
};
