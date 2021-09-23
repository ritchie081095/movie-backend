<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->json('roles')->nullable();
            $table->integer('user_status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        User::create([
            'name' => "Administrator",
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'roles' => ["admin" => "admin"],
            'password' => app('hash')->make('admin123')
        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
