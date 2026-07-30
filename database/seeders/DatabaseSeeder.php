<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Board;
use App\Models\SubjectClass;
use App\Models\Subject;
use App\Models\Chapter;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Roles
        $roles = [
            'Super Admin' => 'super-admin',
            'Admin' => 'admin',
            'Teacher' => 'teacher',
            'Paper Setter' => 'paper-setter',
            'Student' => 'student',
        ];

        $roleModels = [];
        foreach ($roles as $name => $slug) {
            $roleModels[$slug] = Role::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        // 2. Seed Permissions
        $permissions = [
            'Manage Users' => 'manage-users',
            'Manage Boards' => 'manage-boards',
            'Manage Classes' => 'manage-classes',
            'Manage Subjects' => 'manage-subjects',
            'Manage Chapters' => 'manage-chapters',
            'Upload Notes' => 'upload-notes',
            'Run OCR' => 'run-ocr',
            'Manage Question Bank' => 'manage-questions',
            'Generate Paper' => 'generate-paper',
        ];

        $permissionModels = [];
        foreach ($permissions as $name => $slug) {
            $permissionModels[$slug] = Permission::create([
                'name' => $name,
                'slug' => $slug,
            ]);
        }

        // Assign all permissions to Super Admin and Admin
        foreach ($permissionModels as $perm) {
            $roleModels['super-admin']->permissions()->attach($perm->id);
            $roleModels['admin']->permissions()->attach($perm->id);
        }

        // Assign selective permissions to Teacher
        $teacherPerms = ['upload-notes', 'generate-paper'];
        foreach ($teacherPerms as $slug) {
            $roleModels['teacher']->permissions()->attach($permissionModels[$slug]->id);
        }

        // Assign selective permissions to Paper Setter
        $setterPerms = ['run-ocr', 'manage-questions'];
        foreach ($setterPerms as $slug) {
            $roleModels['paper-setter']->permissions()->attach($permissionModels[$slug]->id);
        }

        // 3. Seed Super Admin User
        $admin = User::create([
            'name' => 'IQRA Administrator',
            'email' => 'admin@iqra.edu',
            'password' => bcrypt('Admin@12345'), // secure default password conforming to guidelines
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach($roleModels['super-admin']->id);

        // 4. Seed Classes 1 to 12
        $classes = [];
        for ($i = 1; $i <= 12; $i++) {
            $classes[$i] = SubjectClass::create([
                'name' => "Class {$i}",
                'level' => $i,
            ]);
        }

        // 5. Seed Boards
        $boards = [
            'Federal Board' => 'FBISE',
            'Sindh Board' => 'SINDH',
            'Punjab Board' => 'PUNJAB',
        ];
        $boardModels = [];
        foreach ($boards as $name => $code) {
            $boardModels[] = Board::create([
                'name' => $name,
                'code' => $code,
            ]);
        }

        // 6. Seed Subjects for Class 9 and 10
        $subjectsData = [
            'Mathematics' => 'MATH',
            'Physics' => 'PHYS',
            'Chemistry' => 'CHEM',
            'English' => 'ENGL',
        ];

        foreach ([$classes[9], $classes[10]] as $class) {
            foreach ($subjectsData as $name => $code) {
                $subj = Subject::create([
                    'class_id' => $class->id,
                    'name' => "{$name} - {$class->name}",
                    'code' => "{$code}-{$class->level}",
                ]);

                // Create a couple of demo chapters per subject per board
                foreach ($boardModels as $board) {
                    for ($ch = 1; $ch <= 2; $ch++) {
                        Chapter::create([
                            'subject_id' => $subj->id,
                            'board_id' => $board->id,
                            'title' => "Chapter {$ch}: Basics of " . str_replace(" - {$class->name}", "", $subj->name),
                            'chapter_number' => $ch,
                        ]);
                    }
                }
            }
        }
    }
}
