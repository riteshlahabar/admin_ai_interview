<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminUserSeeder extends Seeder
{
  public function run(): void
  {
    User::updateOrCreate(
      ['email'=>'admin@example.com'],
      ['name'=>'Administrator','password'=>Hash::make('password123'),'role'=>'admin']
    );
    User::updateOrCreate(
      ['email'=>'teacher@example.com'],
      ['name'=>'Teacher One','password'=>Hash::make('password123'),'role'=>'teacher']
    );
  }
}
