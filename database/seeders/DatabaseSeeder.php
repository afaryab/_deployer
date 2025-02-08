<?php

namespace Database\Seeders;

use App\Models\Tenant;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Filament\Commands\MakeUserCommand as FilamentMakeUserCommand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $filamentMakeUserCommand = new FilamentMakeUserCommand();
        $reflector = new \ReflectionObject($filamentMakeUserCommand);

        $getUserModel = $reflector->getMethod('getUserModel');
        $getUserModel->setAccessible(true);
        $user = $getUserModel->invoke($filamentMakeUserCommand)::create([
            'name' => 'Ahmad Faryab Kokab',
            'email' => 'ahmadkokab@gmail.com',
            'password' => Hash::make('Kokab!23'),
        ]);

        $user->__set('type', 1);
        $user->save();

        $this->call(DeployerSeeder::class);
        $this->call(IdentitySeeder::class);
        $this->call(VentiForceSeeder::class);
        $this->call(TenantSeeder::class);






        // $applications = [
        //     [
        //         'name' => 'OPD - Counter',
        //         'slug' => 'opd-counter',
        //         'description' => 'This is a description for OPD Counter',
        //         'icon' => 'icon',
        //         'links' => [
        //             [
        //                 'title' => 'Appointments',
        //                 'description' => 'This is a description for Appointments',
        //                 'icon' => 'icon',
        //                 'slug' => 'appointments',
        //             ],
        //             [
        //                 'title' => 'OPD',
        //                 'description' => 'This is a description for OPD',
        //                 'icon' => 'icon',
        //                 'slug' => 'opd',
        //             ],
        //             [
        //                 'title' => 'Pathology',
        //                 'description' => 'This is a description for Pathology',
        //                 'icon' => 'icon',
        //                 'slug' => 'pathology',
        //             ],
        //             [
        //                 'title' => 'Radiology',
        //                 'description' => 'This is a description for Radiology',
        //                 'icon' => 'icon',
        //                 'slug' => 'radiology',
        //             ],
        //             [
        //                 'title' => 'Pharmacy',
        //                 'description' => 'This is a description for Pharmacy',
        //                 'icon' => 'icon',
        //                 'slug' => 'pharmacy',
        //             ],
        //             [
        //                 'title' => 'Billing',
        //                 'description' => 'This is a description for Billing',
        //                 'icon' => 'icon',
        //                 'slug' => 'billing',
        //             ],
        //             [
        //                 'title' => 'Closing Reports',
        //                 'description' => 'This is a description for closing reports',
        //                 'icon' => 'icon',
        //                 'slug' => 'reports',
        //             ],
        //             [
        //                 'title' => 'Change Requests',
        //                 'description' => 'This is a description for Change Requests',
        //                 'icon' => 'icon',
        //                 'slug' => 'change-requests',
        //             ]
        //         ],
        //     ],
        //     [
        //         'name' => 'OPD - Settings',
        //         'slug' => 'opd-settings',
        //         'description' => 'This is a description for OPD Settings',
        //         'icon' => 'icon',
        //         'links' => [
        //             [
        //                 'title' => 'Doctors',
        //                 'description' => 'This is a description for Doctors',
        //                 'icon' => 'icon',
        //                 'slug' => 'doctors',
        //             ],
        //             [
        //                 'title' => 'Departments',
        //                 'description' => 'This is a description for Departments',
        //                 'icon' => 'icon',
        //                 'slug' => 'departments',
        //             ],
        //             [
        //                 'title' => 'Services',
        //                 'description' => 'This is a description for Services',
        //                 'icon' => 'icon',
        //                 'slug' => 'services',
        //             ],
        //             [
        //                 'title' => 'Users',
        //                 'description' => 'This is a description for Users',
        //                 'icon' => 'icon',
        //                 'slug' => 'users',
        //             ],
        //             [
        //                 'title' => 'Roles',
        //                 'description' => 'This is a description for Roles',
        //                 'icon' => 'icon',
        //                 'slug' => 'roles',
        //             ],
        //             [
        //                 'title' => 'Permissions',
        //                 'description' => 'This is a description for Permissions',
        //                 'icon' => 'icon',
        //                 'slug' => 'permissions',
        //             ],
        //             [
        //                 'title' => 'Settings',
        //                 'description' => 'This is a description for Settings',
        //                 'icon' => 'icon',
        //                 'slug' => 'settings',
        //             ],
        //         ],
        //     ]
        // ];

        // foreach([
        //     'Processton',
        //     'Rahman & Rahman',
        // ] as $name) {

        //     $tenant = Tenant::create([
        //         'name' => $name,
        //     ]);

        //     foreach($applications as $application) {
        //         $app = $tenant->applications()->create([
        //             'name' => $application['name'],
        //             'slug' => strtolower($application['slug']),
        //             'description' => $application['description'],
        //             'icon' => $application['icon'],
        //             'domain' => '/comming-soon',
        //         ]);

        //         foreach($application['links'] as $link) {
        //             $app->links()->create([
        //                 'tenant_id' => $tenant->id,
        //                 'name' => $link['title'],
        //                 'slug' => strtolower($link['slug']),
        //             ]);
        //         }
        //     }

        // }
    }
}
