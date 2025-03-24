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
        Schema::table('bank_cards', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_cards', 'bank_account_id')) {
                $table->string('bank_account_id')->after('bank_card_name')->nullable();

                $table->foreign('bank_account_id')->references('bank_account_id')->on('bank_accounts')->onDelete('cascade')->onUpdate('cascade');
            }
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_cards', function (Blueprint $table) {
            $table->dropColumn('bank_account_id');
        });
    }
};
