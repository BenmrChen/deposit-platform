<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->json('client_info')->after('name')->nullable();
            $table->boolean('deposit_enabled')->default(0)->after('ip_allowlist_enabled')->comment('是否開啟儲值平台功能');
        });
    }

    public function down()
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->dropColumn('client_info');
            $table->dropColumn('deposit_enabled');
        });
    }
};
