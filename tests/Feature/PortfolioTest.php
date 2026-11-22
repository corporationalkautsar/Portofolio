<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_home_page_receives_database_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Home')
                ->has('projects', 10)
                ->where('projects.0.title', 'Odara')
                ->where('projects.1.title', 'Libraria')
                ->has('skills', 6)
                ->has('site.profile')
                ->where('site.profile.name', 'Muhammad Iqbal Fadholi')
                ->where('site.profile.email', 'akunmlbb223434@gmail.com')
                ->where('site.profile.whatsapp', 'https://wa.me/6281515274402'));
    }

    public function test_published_project_detail_works_and_drafts_are_hidden(): void
    {
        $project = Project::query()->first();
        $this->get(route('work.show', $project->slug))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Public/WorkDetail'));

        $project->update(['status' => 'draft']);
        $this->get(route('work.show', $project->slug))->assertNotFound();
    }

    public function test_contact_form_validates_and_stores_messages(): void
    {
        $this->post(route('contact.store'), [])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->post(route('contact.store'), [
            'name' => 'Test Visitor',
            'email' => 'visitor@example.com',
            'subject' => 'New project',
            'message' => 'Hello, I would like to discuss a project.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'visitor@example.com',
            'subject' => 'New project',
            'is_read' => false,
        ]);
    }

    public function test_admin_area_is_protected(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Dashboard')->has('stats'));
    }

    public function test_admin_login_authenticates_only_admin_accounts(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('secure-password-123'),
            'is_admin' => true,
        ]);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'secure-password-123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_admin_can_create_update_and_delete_projects_with_images(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::query()->first();

        $created = $this->actingAs($admin)->post(route('admin.projects.store'), [
            'category_id' => $category->id,
            'title' => 'Test Project',
            'description' => 'A test case study.',
            'year' => 2026,
            'status' => 'published',
            'featured' => true,
            'thumbnail' => UploadedFile::fake()->image('thumb.jpg'),
        ])->assertRedirect(route('admin.projects.index'));

        $project = Project::query()->where('title', 'Test Project')->firstOrFail();
        $this->assertNotNull($project->thumbnail);
        Storage::disk('public')->assertExists($project->thumbnail);
        $created->assertSessionHas('success');

        $this->actingAs($admin)->put(route('admin.projects.update', $project), [
            'category_id' => $category->id,
            'title' => 'Updated Project',
            'slug' => $project->slug,
            'description' => 'Updated description.',
            'year' => 2026,
            'status' => 'draft',
            'featured' => false,
        ])->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'Updated Project', 'status' => 'draft']);

        $this->actingAs($admin)->delete(route('admin.projects.destroy', $project))
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        Storage::disk('public')->assertMissing($project->thumbnail);
    }

    public function test_admin_can_manage_categories_and_messages(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Motion Design',
        ])->assertSessionHas('success');

        $category = Category::query()->where('name', 'Motion Design')->firstOrFail();
        $this->assertSame('motion-design', $category->slug);

        $message = ContactMessage::query()->create([
            'name' => 'Reader',
            'email' => 'reader@example.com',
            'subject' => 'Question',
            'message' => 'Do you take commissions?',
        ]);

        $this->actingAs($admin)->put(route('admin.messages.update', $message))->assertSessionHas('success');
        $this->assertTrue($message->fresh()->is_read);

        $this->actingAs($admin)->delete(route('admin.messages.destroy', $message))->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
    }

    public function test_admin_creation_command_trims_credentials_and_hashes_password(): void
    {
        $this->artisan('portfolio:admin', [
            'email' => '  command-admin@example.com  ',
            'name' => '  Command Admin  ',
        ])
            ->expectsQuestion('Password (minimum 12 characters)', '  command-password-123  ')
            ->expectsQuestion('Confirm password', "command-password-123\n")
            ->assertSuccessful();

        $admin = User::query()->where('email', 'command-admin@example.com')->firstOrFail();
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('command-password-123', $admin->password));
    }
}
