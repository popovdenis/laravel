<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateNewUserCommand extends Command
{
    /**
     * Example: php artisan user:create "John Doe" john@example.com "securePass123" "Student"
     */
    protected $signature = 'user:create
                            {firstname : The firstname of the user}
                            {lastname : The lastname of the user}
                            {email : The email of the user}
                            {password? : The password}
                            {role? : The roles of the user (optional)}';

    protected $description = 'Create a new user';

    public function handle(): int
    {
        $firstname = $this->argument('firstname');
        $lastname = $this->argument('lastname');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $role = $this->argument('role');

        if (User::where('email', $email)->exists()) {
            $this->error('User with email ' . $email . ' already exists.');
            return self::FAILURE;
        }

        $user = User::create([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        if ($role) {
            $user->assignRole($role);
            $this->info("Role '{$role}' has been assigned to the user.");
        }

        $this->info("User {$firstname} {$lastname} with email <{$email}> has been created.");

        return self::SUCCESS;
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
