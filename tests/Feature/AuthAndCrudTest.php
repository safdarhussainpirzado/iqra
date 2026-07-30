<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Chapter;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Subject;
use App\Models\SubjectClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $teacherUser;
    protected User $studentUser;
    protected Role $adminRole;
    protected Role $teacherRole;
    protected Role $studentRole;
    protected Permission $boardPermission;
    protected Permission $chapterPermission;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Roles
        $this->adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->teacherRole = Role::create(['name' => 'Teacher', 'slug' => 'teacher']);
        $this->studentRole = Role::create(['name' => 'Student', 'slug' => 'student']);

        // Create Permissions
        $this->boardPermission = Permission::create(['name' => 'Manage Boards', 'slug' => 'manage-boards']);
        $this->chapterPermission = Permission::create(['name' => 'Manage Chapters', 'slug' => 'manage-chapters']);

        // Assign Permissions
        $this->adminRole->permissions()->attach([$this->boardPermission->id, $this->chapterPermission->id]);
        $this->teacherRole->permissions()->attach($this->chapterPermission->id);

        // Create Users
        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@iqra.edu',
            'password' => bcrypt('password123'),
        ]);
        $this->adminUser->roles()->attach($this->adminRole->id);

        $this->teacherUser = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@iqra.edu',
            'password' => bcrypt('password123'),
        ]);
        $this->teacherUser->roles()->attach($this->teacherRole->id);

        $this->studentUser = User::create([
            'name' => 'Student User',
            'email' => 'student@iqra.edu',
            'password' => bcrypt('password123'),
        ]);
        $this->studentUser->roles()->attach($this->studentRole->id);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@iqra.edu',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => ['id', 'name', 'email', 'roles']
            ]);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@iqra.edu',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_user_can_access_profile()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'email' => 'admin@iqra.edu',
            ]);
    }

    public function test_admin_can_create_board()
    {
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/boards', [
                'name' => 'Federal Board',
                'code' => 'FBISE',
            ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'code' => 'FBISE',
            ]);

        $this->assertDatabaseHas('boards', ['code' => 'FBISE']);
    }

    public function test_teacher_cannot_create_board()
    {
        $response = $this->actingAs($this->teacherUser, 'sanctum')
            ->postJson('/api/boards', [
                'name' => 'Punjab Board',
                'code' => 'PUNJAB',
            ]);

        $response->assertStatus(403);
    }

    public function test_cannot_create_duplicate_chapter()
    {
        $board = Board::create(['name' => 'Sindh Board', 'code' => 'SINDH']);
        $class = SubjectClass::create(['name' => 'Class 9', 'level' => 9]);
        $subject = Subject::create(['class_id' => $class->id, 'name' => 'Physics', 'code' => 'PHYS-9']);

        // Create first chapter
        Chapter::create([
            'subject_id' => $subject->id,
            'board_id' => $board->id,
            'title' => 'Introduction to Physics',
            'chapter_number' => 1,
        ]);

        // Attempt duplicate creation
        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/chapters', [
                'subject_id' => $subject->id,
                'board_id' => $board->id,
                'title' => 'Basics of Physics',
                'chapter_number' => 1,
            ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'A chapter with this number already exists for the selected subject and board.'
            ]);
    }
}
