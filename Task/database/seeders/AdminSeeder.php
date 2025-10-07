<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
 

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
    public function run(): void
    {
        Admin::create([
        'name' => 'Admin',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('147852'),
        'phone' => '1234567890',
        'image' => '1.png',
    ]);
    }
}
