<?php

namespace Database\Seeders;

use App\Enums\Roles\RolesEnums;
use App\Models\Security\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::findOrCreate(RolesEnums::SUPERADMIN->value, 'web');
        $password = '123456789@';

        if (blank($password)) {
            throw new \RuntimeException('ADMIN_SEEDER_PASSWORD must be configured in production.');
        }

        foreach ($this->accounts() as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    ...$account,
                    'password' => $password,
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'is_valid' => true,
                ]
            );

            $user->syncRoles([$role]);
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function accounts(): array
    {
        return [
            [
                'last_name' => 'Elmarzougui',
                'first_name' => 'Abdelghafour',
                'email' => 'abdelgha4or@gmail.com',
                'phone' => '+212677512753',
                'gender' => 'male',
            ],
            [
                'last_name' => 'Dahmane',
                'first_name' => 'Mohamed',
                'email' => 'moroccanmeraki@gmail.com',
                'phone' => '+212750666919',
                'gender' => 'male',
            ]
        ];
    }
}
