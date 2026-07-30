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
            'password' => bcrypt('Admin@12345'),
            'email_verified_at' => now(),
        ]);
        $admin->roles()->attach($roleModels['super-admin']->id);

        // 4. Seed Classes 1 to 12
        $classes = [];
        for ($i = 1; $i <= 12; $i++) {
            $classes[$i] = SubjectClass::create([
                'name' => "Class {$i}",
                'level' => $i,
                'slug' => "class-{$i}",
                'sort_order' => $i,
            ]);
        }

        // 5. Seed Board Groups and Boards
        $boardGroupsData = [
            'fg' => 'FG Schools / Federal Board Islamabad',
            'punjab' => 'Punjab Boards',
            'sindh' => 'Sindh Boards',
            'kpk' => 'Khyber Pakhtunkhwa Boards (KPK)',
            'balochistan' => 'Balochistan Boards',
        ];

        $boardGroups = [];
        $bgOrder = 1;
        foreach ($boardGroupsData as $slug => $name) {
            $boardGroups[$slug] = \App\Models\BoardGroup::create([
                'name' => $name,
                'slug' => $slug,
                'sort_order' => $bgOrder++,
            ]);
        }

        $boards = [
            'FG Schools / Federal Board Islamabad' => ['code' => 'FBISE', 'group' => 'fg', 'slug' => 'federal-board'],
            'Punjab Boards' => ['code' => 'PUNJAB', 'group' => 'punjab', 'slug' => 'punjab-board'],
            'Sindh Boards' => ['code' => 'SINDH', 'group' => 'sindh', 'slug' => 'sindh-board'],
            'Khyber Pakhtunkhwa Boards (KPK)' => ['code' => 'KPK', 'group' => 'kpk', 'slug' => 'kpk-board'],
            'Balochistan Boards' => ['code' => 'BALOCHISTAN', 'group' => 'balochistan', 'slug' => 'balochistan-board'],
        ];

        $boardModels = [];
        foreach ($boards as $name => $info) {
            $boardModels[$info['code']] = Board::create([
                'name' => $name,
                'code' => $info['code'],
                'board_group_id' => $boardGroups[$info['group']]->id,
                'slug' => $info['slug'],
                'is_active' => true,
            ]);
        }

        // 6. Subject data organized by class and board
        $classSubjects = [
            12 => [
                'FBISE' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'English', 'Urdu', 'Pakistan Studies', 'Business Statistics', 'Principles of Accounting'],
                'PUNJAB' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'Economics', 'Education', 'English', 'Islamic Studies', 'Urdu', 'Pakistan Studies', 'Civics', 'Persian', 'Punjabi', 'Sociology', 'History of Pakistan'],
                'SINDH' => ['Mathematics', 'Chemistry', 'Physics', 'English', 'Urdu', 'Pakistan Studies'],
                'KPK' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'English', 'Urdu', 'Pakistan Studies'],
                'BALOCHISTAN' => [],
            ],
            11 => [
                'FBISE' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'English', 'Urdu', 'Islamiyat (Islamic Education)', 'Business Mathematics', 'Principles of Accounting', 'Basic Statistics'],
                'PUNJAB' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'English', 'Urdu', 'Islamic Education', 'Civics', 'Economics', 'Education', 'History of Pakistan', 'Islamic Studies', 'Persian', 'Punjabi', 'Sociology'],
                'SINDH' => ['Mathematics', 'Chemistry', 'Physics', 'English', 'Islamic Education', 'Urdu'],
                'KPK' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'English', 'Islamic Education', 'Urdu'],
                'BALOCHISTAN' => [],
            ],
            10 => [
                'FBISE' => ['Mathematics', 'General Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'English', 'Urdu', 'Islamiyat (Islamic Education)', 'Pakistan Studies', 'General Science', 'Civics'],
                'PUNJAB' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'English', 'Urdu', 'Islamic Education', 'General Mathematics', 'General Science', 'History of Pakistan', 'Islamic Studies'],
                'SINDH' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'English', 'Urdu', 'Islamic Education', 'Pakistan Studies', 'General Mathematics', 'Civics', 'Economics', 'Sindhi'],
                'KPK' => ['Mathematics', 'Chemistry', 'Physics', 'Biology', 'English', 'Islamic Education', 'Pakistan Studies', 'Urdu', 'General Mathematics'],
                'BALOCHISTAN' => [],
            ],
            9 => [
                'FBISE' => ['Mathematics', 'General Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'English', 'Urdu', 'Islamiyat (Islamic Education)', 'Pakistan Studies', 'General Science', 'Civics'],
                'PUNJAB' => ['Mathematics', 'General Mathematics', 'Chemistry', 'Physics', 'Biology', 'Computer Science', 'English', 'Urdu', 'Islamic Education', 'Pakistan Studies', 'General Science', 'Islamic Studies'],
                'SINDH' => ['Mathematics', 'General Mathematics', 'Chemistry', 'Physics', 'Biology', 'English', 'Urdu', 'Islamic Education', 'Pakistan Studies', 'Civics', 'Economics'],
                'KPK' => ['Mathematics', 'General Mathematics', 'Chemistry', 'Physics', 'Biology', 'English', 'Urdu', 'Islamic Education', 'Pakistan Studies'],
                'BALOCHISTAN' => [],
            ],
            8 => [
                'FBISE' => ['Mathematics', 'General Science', 'English', 'Computer', 'History', 'Geography', 'Urdu', 'Islamiyat', 'Electric'],
                'PUNJAB' => ['Mathematics', 'Science', 'English', 'Computer', 'History', 'Geography', 'Urdu', 'Islamiyat', 'Arabic', 'Agriculture', 'Home Economics'],
                'SINDH' => ['Mathematics', 'General Science', 'English', 'Social Studies', 'Urdu', 'Islamiyat', 'Arabic', 'Sindhi'],
                'KPK' => ['Mathematics', 'General Science', 'English', 'Computer', 'History', 'Geography', 'Urdu', 'Islamiyat', 'Arabic', 'Pashto', 'Home Economics', 'Health & Physical Education', 'Drawing'],
                'BALOCHISTAN' => ['Mathematics', 'General Science', 'English', 'History', 'Geography', 'Computer', 'Urdu', 'Islamiyat', 'Tarjama-tul-Quran', 'Arabic (Old Course)'],
            ],
            7 => [
                'FBISE' => ['Mathematics', 'General Science', 'English', 'Computer', 'History', 'Geography', 'Urdu', 'Islamiyat'],
                'PUNJAB' => ['Mathematics', 'Science', 'English', 'Computer', 'History', 'Geography', 'Urdu', 'Islamiyat', 'Arabic', 'Agriculture', 'Home Economics'],
                'SINDH' => ['Mathematics', 'Science', 'English', 'Social Studies', 'Urdu', 'Islamiyat', 'Arabic', 'Sindhi'],
                'KPK' => ['Mathematics', 'General Science', 'English', 'History', 'Geography', 'Computer Science', 'Islamiyat', 'Urdu', 'Home Economics', 'Health & Physical Education', 'Arabic', 'Pashto', 'Drawing'],
                'BALOCHISTAN' => ['Mathematics', 'Science', 'English', 'History', 'Geography', 'Computer', 'Urdu', 'Islamiyat', 'Tarjama-tul-Quran', 'Arabic (Old Course)'],
            ],
            6 => [
                'FBISE' => ['Mathematics', 'General Science', 'English', 'Computer', 'History', 'Geography', 'Urdu', 'Islamiyat'],
                'PUNJAB' => ['Mathematics', 'Science', 'English', 'Computer', 'History', 'Geography', 'Urdu', 'Islamiyat', 'Arabic', 'Agriculture', 'Home Economics'],
                'SINDH' => ['Mathematics', 'Science', 'English', 'Social Studies', 'Urdu', 'Islamiyat', 'Arabic', 'Sindhi'],
                'KPK' => ['Mathematics', 'Science', 'English', 'Computer', 'History', 'Geography', 'Urdu', 'Islamiyat', 'Arabic', 'Home Economics', 'Health & Physical Education', 'Pashto', 'Drawing'],
                'BALOCHISTAN' => ['Mathematics', 'General Science', 'English', 'History', 'Geography', 'Computer', 'Urdu', 'Islamiyat', 'Tarjamat-ul-Quran', 'Arabic(Old Course)'],
            ],
            5 => [
                'FBISE' => ['Mathematics', 'General Science', 'English', 'Urdu', 'Islamiyat', 'Social Studies'],
                'PUNJAB' => ['Mathematics', 'Science', 'English', 'Pakistan Studies', 'Urdu', 'Islamiyat'],
                'SINDH' => ['English', 'Pakistan Studies', 'Science', 'Sindhi', 'Urdu'],
                'KPK' => ['Mathematics', 'General Science', 'English', 'Social Studies', 'Urdu', 'Islamiyat', 'Pashto'],
                'BALOCHISTAN' => ['Mathematics', 'General Science', 'English', 'Social Studies', 'Urdu', 'Islamiyat'],
            ],
            4 => [
                'FBISE' => ['Mathematics', 'General Science', 'English', 'Urdu', 'Islamiyat', 'Social Studies'],
                'PUNJAB' => ['Mathematics', 'General Science', 'English', 'Social Studies', 'Urdu', 'Islamiyat'],
                'SINDH' => ['Mathematics', 'Science', 'English', 'Social Studies', 'Sindhi'],
                'KPK' => ['Mathematics', 'General Science', 'English', 'Social Studies', 'Urdu', 'Islamiyat', 'Pashto'],
                'BALOCHISTAN' => ['Mathematics', 'Science', 'English', 'Social Studies', 'Urdu', 'Islamiyat'],
            ],
            3 => [
                'FBISE' => ['Mathematics', 'English', 'General Knowledge', 'Urdu', 'Islamiyat'],
                'PUNJAB' => ['Mathematics', 'English', 'General Knowledge', 'Urdu', 'Islamiyat'],
                'SINDH' => [],
                'KPK' => [],
                'BALOCHISTAN' => [],
            ],
            2 => [
                'FBISE' => [],
                'PUNJAB' => [],
                'SINDH' => [],
                'KPK' => [],
                'BALOCHISTAN' => [],
            ],
            1 => [
                'FBISE' => [],
                'PUNJAB' => [],
                'SINDH' => [],
                'KPK' => [],
                'BALOCHISTAN' => [],
            ],
        ];

        // Seed subjects for all classes and boards
        foreach ($classSubjects as $classLevel => $boardsData) {
            $class = $classes[$classLevel];
            foreach ($boardsData as $boardCode => $subjects) {
                if (empty($subjects)) continue;
                $board = $boardModels[$boardCode];
                foreach ($subjects as $subjectName) {
                    $code = strtoupper(substr(str_replace([' ', '-', '(', ')'], '', $subjectName), 0, 4));
                    $subj = Subject::firstOrCreate(
                        ['code' => "{$code}-{$class->level}"],
                        [
                            'class_id' => $class->id,
                            'name' => "{$subjectName} - {$class->name}",
                            'slug' => Str::slug($subjectName),
                            'color_hex' => '#10B981',
                        ]
                    );

                    // Create demo chapters
                    for ($ch = 1; $ch <= 2; $ch++) {
                        $chapter = Chapter::firstOrCreate(
                            [
                                'subject_id' => $subj->id,
                                'board_id' => $board->id,
                                'chapter_number' => $ch
                            ],
                            [
                                'title' => "Chapter {$ch}: Basics of {$subjectName}",
                            ]
                        );

                        // Seed questions for this chapter
                        $q1 = \App\Models\Question::create([
                            'board_id' => $board->id,
                            'class_id' => $class->id,
                            'subject_id' => $subj->id,
                            'chapter_id' => $chapter->id,
                            'type' => 'MCQ',
                            'question_text' => "What is the fundamental unit of {$subjectName}?",
                            'difficulty' => 'Medium',
                            'marks' => 2,
                            'language' => 'English',
                        ]);
                        \App\Models\MCQOption::create(['question_id' => $q1->id, 'option_text' => 'Option A (Correct)', 'is_correct' => true]);
                        \App\Models\MCQOption::create(['question_id' => $q1->id, 'option_text' => 'Option B', 'is_correct' => false]);
                        \App\Models\MCQOption::create(['question_id' => $q1->id, 'option_text' => 'Option C', 'is_correct' => false]);
                        \App\Models\MCQOption::create(['question_id' => $q1->id, 'option_text' => 'Option D', 'is_correct' => false]);

                        \App\Models\Question::create([
                            'board_id' => $board->id,
                            'class_id' => $class->id,
                            'subject_id' => $subj->id,
                            'chapter_id' => $chapter->id,
                            'type' => 'Short',
                            'question_text' => "Explain the core concepts of {$subjectName} in your own words.",
                            'difficulty' => 'Medium',
                            'marks' => 5,
                            'language' => 'English',
                        ]);
                    }
                }
            }
        }

        // Seed our parsed premium Class 7 Computer Science units
        $jsonPath = database_path('seeders/computer_science_7.json');
        if (file_exists($jsonPath)) {
            $this->command->info("Seeding structured digital textbook for Class 7 Computer Science...");
            \Illuminate\Support\Facades\Artisan::call('app:import-unit', [
                'file' => $jsonPath,
                '--board' => 'PUNJAB',
                '--class' => 7,
                '--subject' => 'COMP-7'
            ]);
        }
    }
}