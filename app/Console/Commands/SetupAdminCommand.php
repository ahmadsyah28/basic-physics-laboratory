<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class SetupAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the admin panel by running migrations and seeders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up admin panel...');

        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info('Migrations completed!');

        // Create admin users if they don't exist
        $this->info('Creating admin users if needed...');

        if (!User::where('email', 'superadmin@lab-fisika.com')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@lab-fisika.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
            ]);
            $this->info('Super Admin created!');
        } else {
            $this->info('Super Admin already exists.');
        }

        if (!User::where('email', 'admin@lab-fisika.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@lab-fisika.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
            $this->info('Admin created!');
        } else {
            $this->info('Admin already exists.');
        }

        $this->info('Admin panel setup completed successfully!');
        $this->info('You can now login with the following credentials:');
        $this->info('Super Admin: superadmin@lab-fisika.com / superadmin123');
        $this->info('Admin: admin@lab-fisika.com / admin123');

        return Command::SUCCESS;
    }
}
