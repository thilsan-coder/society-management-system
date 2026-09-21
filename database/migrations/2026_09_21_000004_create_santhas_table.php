<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santhas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->decimal('amount', 10, 2)->default(300.00);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->date('due_date');
            $table->enum('status', ['paid', 'unpaid', 'partially_paid'])->default('unpaid');
            $table->timestamps();

            $table->unique(['member_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santhas');
    }
};
