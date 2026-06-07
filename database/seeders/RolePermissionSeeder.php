<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Student management
            'view students',
            'create students',
            'edit students',
            'delete students',
            
            // Lecturer management
            'view lecturers',
            'create lecturers',
            'edit lecturers',
            'delete lecturers',
            
            // Class management
            'view classes',
            'create classes',
            'edit classes',
            'delete classes',
            
            // Course management
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            
            // Study Plan management
            'view study_plans',
            'approve study_plans',
            'reject study_plans',
            
            // Grade management
            'view grades',
            'input grades',
            
            // Thesis management
            'view theses',
            'manage theses',
            
            // Internship management
            'view internships',
            'manage internships',
            
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Master data
            'manage faculties',
            'manage study_programs',
            'manage academic_years',
            'manage rooms',
            
            // Reports
            'view reports',
            'export reports',
            
            // Announcements
            'manage announcements',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles with permissions
        
        // Superadmin - has all permissions
        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $superadmin->givePermissionTo(Permission::all());

        // Admin Faculty - has most admin permissions but scoped to faculty
        $adminFaculty = Role::firstOrCreate(['name' => 'admin_faculty']);
        $adminFaculty->givePermissionTo([
            'view students', 'create students', 'edit students',
            'view lecturers', 'create lecturers', 'edit lecturers',
            'view classes', 'create classes', 'edit classes', 'delete classes',
            'view courses', 'create courses', 'edit courses',
            'view study_plans', 'approve study_plans', 'reject study_plans',
            'view grades',
            'view theses', 'manage theses',
            'view internships', 'manage internships',
            'manage study_programs',
            'manage rooms',
            'view reports', 'export reports',
            'manage announcements',
        ]);

        // Lecturer - can view and input grade, manage supervision
        $lecturer = Role::firstOrCreate(['name' => 'lecturer']);
        $lecturer->givePermissionTo([
            'view classes',
            'view grades', 'input grades',
            'view theses',
            'view internships',
            'view students',
        ]);

        // Student - minimal permissions (most access via routes)
        $student = Role::firstOrCreate(['name' => 'student']);
        // Student typically don't need explicit permissions
        // Their access is controlled by role middleware

        $this->command->info('Roles and permissions created successfully!');
    }
}
