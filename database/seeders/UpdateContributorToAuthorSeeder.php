<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateContributorToAuthorSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role', 'contributor')->update(['role' => 'author']);
    }
}