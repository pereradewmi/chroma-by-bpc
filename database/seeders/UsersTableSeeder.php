<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('logindetails')->insert([
            'lName' => 'CROMA Admin',
            'lUserName' => 'admin@croma.com',
            'lPassword' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
