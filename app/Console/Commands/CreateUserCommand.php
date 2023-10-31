<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user['name'] = $this->ask('What is your name?');
        $user['email'] = $this->ask('What is your email?');
        $user['password'] = HASH::make($this->secret('What is the password?'));
        $roleName = $this->choice('What is your role?', ['admin', 'editor'], 1);
        $role = \App\Models\Role::where('name', $roleName)->first();
        if(! $role) {
            $this->error('Role does not exist');
            return -1;
        }

        DB::transaction(function() use ($user, $role) {
            $user = User::create($user);
            $user->roles()->attach($role->id);
        });



        $this->info('User created successfully.');

        return 0;

    }
}
