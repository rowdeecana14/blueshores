<?php

namespace App\Console\Commands;

use App\Enums\User\Active;
use App\Enums\User\Role;
use App\Enums\User\Status;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class InitializeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize the application by running migrations, seeding, and other setup tasks.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Initializing the application...');
        $this->newLine();

        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->newLine();

        $this->info('Seeding the database...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->newLine();

        $this->info('Clearing cache...');
        Artisan::call('optimize');
        $this->newLine();

        $this->info('Creating admin user...');

        $password = 'superadmin';
        $user = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
            'email_verified' => Carbon::now(),
            'password' => Hash::make($password),
            'active' => Active::YES,
            'role' => Role::ADMIN,
            'status' => Status::APPROVED,
        ]);
        $this->newLine();
        $this->info('Email: '.$user->email);
        $this->info('Password: '.$password);
        $this->newLine();

        $this->info('Application initialized successfully!');
    }
}
