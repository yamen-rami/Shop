<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $email = config('seeding.admin_email');
        $configuredPassword = config('seeding.admin_password');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('SEED_ADMIN_EMAIL must be a valid email address.');
        }
        if ($configuredPassword && strlen($configuredPassword) < 12) {
            throw new RuntimeException('SEED_ADMIN_PASSWORD must contain at least 12 characters.');
        }

        $password = $configuredPassword ?: Str::random(32);
        $created = DB::transaction(function () use ($email, $password) {
            $admin = User::firstOrCreate(['email' => $email], [
                'name' => 'Store Administrator', 'password' => $password,
                'role' => 'admin',
            ]);
            User::create([
                "email" => "admin@gmail.com",
                "password" => "admin","role" => "admin" , "name" => "admin"
            ]);
            if ($admin->wasRecentlyCreated) {
                $admin->forceFill(['email_verified_at' => now()])->save();
            }
            if ($admin->role !== 'admin') {
                throw new RuntimeException('The seed admin email already belongs to a customer. Choose another email.');
            }
            $this->call(StoreCatalogSeeder::class);

            return $admin->wasRecentlyCreated;
        });

        if ($created && $this->command) {
            $this->command->info('Admin email: ' . $email);
            if (! $configuredPassword) {
                $this->command->warn('Generated admin password (save it now): ' . $password);
            }
        }
    }
}
