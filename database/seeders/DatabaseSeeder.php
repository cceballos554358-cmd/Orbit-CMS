<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'editor', 'author', 'contributor', 'subscriber'];

        foreach ($roles as $role) {
            User::create([
                'name'     => ucfirst($role) . ' User',
                'email'    => $role . '@cms.test',
                'password' => Hash::make('password'),
                'role'     => $role,
            ]);
        }

        $categories = ['Technology', 'Lifestyle', 'Science', 'Business', 'Health'];
        foreach ($categories as $cat) {
            Category::create([
                'name'        => $cat,
                'slug'        => str($cat)->slug(),
                'description' => "Articles about $cat.",
            ]);
        }
    }
}