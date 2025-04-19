<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@orioncc.com',
            'password' => Hash::make('password'),
            'role' => 'o-admin',
        ]);
        User::create([
            'name' => 'GM User',
            'email' => 'gm@orioncc.com',
            'password' => Hash::make('password'),
            'role' => 'gm',
        ]);
        User::create([
            'name' => 'CM User',
            'email' => 'cm@orioncc.com',
            'password' => Hash::make('password'),
            'role' => 'cm',
        ]);
        User::create([
            'name' => 'dm User',
            'email' => 'dm@orioncc.com',
            'password' => Hash::make('password'),
            'role' => 'dm',
        ]);
        User::create([
            'name' => 'pm User',
            'email' => 'pm@orioncc.com',
            'password' => Hash::make('password'),
            'role' => 'pm',
        ]);
        User::create([
            'name' => 'pm1 User',
            'email' => 'pm1@orioncc.com',
            'password' => Hash::make('password'),
            'role' => 'pm',
        ]);
        User::create([
            'name' => 'pm2 User',
            'email' => 'pm2@orioncc.com',
            'password' => Hash::make('password'),
            'role' => 'pm',
        ]);

        $managers = User::where('role', 'pm')->get();
        $projects = [
            [
                'name' => 'Website Redesign',
                'code' => 'WEB-RD',
                'description' => 'Complete redesign of the company website with new UI/UX.',
                'manager_id' => $managers->random()->id,
            ],
            [
                'name' => 'Mobile App Development',
                'code' => 'MOB-APP',
                'description' => 'Develop a new mobile application for iOS and Android platforms.',
                'manager_id' => $managers->random()->id,
            ],
            [
                'name' => 'E-commerce Platform',
                'code' => 'ECM-PLT',
                'description' => 'Build a scalable e-commerce platform with payment integration.',
                'manager_id' => $managers->random()->id,
            ],
            [
                'name' => 'CRM System',
                'code' => 'CRM-SYS',
                'description' => 'Customer Relationship Management system for sales team.',
                'manager_id' => $managers->random()->id,
            ],
            [
                'name' => 'Analytics Dashboard',
                'code' => 'ANL-DSH',
                'description' => 'Real-time analytics dashboard for business intelligence.',
                'manager_id' => $managers->random()->id,
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Add project manager to members
            $project->members()->attach($project->manager_id);


        }
    }
}
