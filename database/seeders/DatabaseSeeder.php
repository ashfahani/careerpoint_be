<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserRoleSeeder::class);
        $this->call(ProdiSeeder::class);
        $this->call(ActivityCategorySeeder::class);
        $this->call(ActivityTypeSeeder::class);
        $this->call(LevelCommitteeSeeder::class);
        $this->call(LevelCompetitionSeeder::class);
        $this->call(LevelInternshipSeeder::class);
        $this->call(LevelOrganizationSeeder::class);
        $this->call(LevelPublicationSeeder::class);
        $this->call(LevelSeminarSeeder::class);
        $this->call(RoleCommitteeSeeder::class);
        $this->call(RoleCompetitionSeeder::class);
        $this->call(RoleInternshipSeeder::class);
        $this->call(RoleOrganizationSeeder::class);
        $this->call(RolePublicationSeeder::class);
        $this->call(RoleSeminarSeeder::class);
        $this->call(CommitteeTypeSeeder::class);
    }
}
