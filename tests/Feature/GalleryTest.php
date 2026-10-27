<?php
namespace Tests\Feature;

use App\Models\{Exhibition, User, Vote};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    private function exhibition(string $status = 'open'): Exhibition
    {
        $exhibition = Exhibition::create(['title' => 'Olhares do Brasil', 'status' => $status]);
        $exhibition->artworks()->create(['title' => 'Horizontes', 'artist' => 'Ana', 'image_path' => 'artworks/test.jpg']);
        return $exhibition;
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();
        return $user;
    }

    public function test_public_gallery_hides_drafts_and_renders_exhibitions(): void
    {
        $draft = $this->exhibition('draft');
        $open = $this->exhibition();
        $this->get('/')->assertOk()->assertSee('Olhares do Brasil');
        $this->get(route('gallery.show', $draft))->assertNotFound();
        $this->get(route('gallery.show', $open))->assertOk()->assertSee('Horizontes');
    }

    public function test_vote_is_counted_once_per_normalized_email_per_exhibition(): void
    {
        $exhibition = $this->exhibition();
        $data = ['email' => 'Pessoa@example.com', 'consent' => '1', 'artwork_id' => $exhibition->artworks->first()->id];
        $this->post(route('gallery.vote', $exhibition), $data)->assertSessionHasNoErrors();
        $this->post(route('gallery.vote', $exhibition), [...$data, 'email' => 'pessoa@example.com'])->assertSessionHasErrors('email');
        $this->assertDatabaseCount('votes', 1);
        $other = $this->exhibition();
        $this->post(route('gallery.vote', $other), [...$data, 'artwork_id' => $other->artworks->first()->id])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('votes', 2);
        $this->assertNotEquals($data['email'], Vote::first()->voter_hash);
    }

    public function test_closed_draft_and_foreign_artwork_votes_are_rejected(): void
    {
        foreach (['draft', 'closed'] as $status) {
            $exhibition = $this->exhibition($status);
            $this->post(route('gallery.vote', $exhibition), ['email' => 'a@example.com', 'consent' => 1, 'artwork_id' => $exhibition->artworks->first()->id])->assertSessionHasErrors('vote');
        }
        $open = $this->exhibition();
        $other = $this->exhibition();
        $this->post(route('gallery.vote', $open), ['email' => 'a@example.com', 'consent' => 1, 'artwork_id' => $other->artworks->first()->id])->assertNotFound();
        $this->post(route('gallery.vote', $open), ['email' => 'invalid', 'artwork_id' => $open->artworks->first()->id])->assertSessionHasErrors(['email', 'consent']);
        $this->assertDatabaseCount('votes', 0);
    }

    public function test_admin_and_reports_are_protected(): void
    {
        $exhibition = $this->exhibition();
        $this->get('/admin')->assertRedirect('/login');
        $this->get(route('admin.pdf', $exhibition))->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
        $this->get(route('admin.pdf', $exhibition))->assertForbidden();
    }

    public function test_admin_can_create_upload_open_and_close_exhibition(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());
        $this->post(route('admin.store'), ['title' => 'Nova exposição'])->assertSessionHasNoErrors();
        $exhibition = Exhibition::first();
        $this->patch(route('admin.status', $exhibition), ['status' => 'open'])->assertSessionHasErrors('status');
        $this->post(route('admin.upload', $exhibition), ['title' => 'Obra', 'image' => UploadedFile::fake()->image('obra.jpg')])->assertSessionHasNoErrors();
        Storage::disk('public')->assertExists($exhibition->artworks()->first()->image_path);
        $this->patch(route('admin.status', $exhibition), ['status' => 'open'])->assertSessionHasNoErrors();
        $this->post(route('admin.upload', $exhibition), ['title' => 'Outra', 'artist' => 'Artista', 'image' => UploadedFile::fake()->image('outra.png')])->assertSessionHasErrors('image');
        $this->assertCount(1, Storage::disk('public')->allFiles());
        $this->patch(route('admin.status', $exhibition), ['status' => 'closed'])->assertSessionHasNoErrors();
        $this->patch(route('admin.status', $exhibition), ['status' => 'open'])->assertSessionHasErrors('status');
        $this->assertSame('closed', $exhibition->fresh()->status);
    }

    public function test_upload_rejects_non_images(): void
    {
        Storage::fake('public');
        $exhibition = $this->exhibition('draft');
        $this->actingAs($this->admin())->post(route('admin.upload', $exhibition), ['title' => 'Arquivo', 'artist' => 'Ana', 'image' => UploadedFile::fake()->create('script.php', 1, 'text/plain')])->assertSessionHasErrors('image');
        $this->assertCount(0, Storage::disk('public')->allFiles());
    }

    public function test_results_keep_ties_and_export_a_real_pdf_including_empty_results(): void
    {
        $exhibition = $this->exhibition('closed');
        $second = $exhibition->artworks()->create(['title' => 'Segunda', 'artist' => 'Bia', 'image_path' => 'test.png']);
        foreach ([$exhibition->artworks()->first()->id, $second->id] as $id) {
            Vote::create(['exhibition_id' => $exhibition->id, 'artwork_id' => $id, 'voter_hash' => hash('sha256', (string) $id)]);
        }
        $this->actingAs($this->admin())->get(route('admin.show', $exhibition))->assertOk()->assertViewHas('total', 2)->assertViewHas('artworks', fn ($works) => $works->pluck('rank')->all() === [1, 1]);
        $this->get('/admin')->assertOk();
        $pdf = $this->get(route('admin.pdf', $exhibition))->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $pdf->getContent());
        $empty = Exhibition::create(['title' => 'Sem votos']);
        $this->get(route('admin.show', $empty))->assertOk()->assertViewHas('total', 0);
        $this->get(route('admin.pdf', $empty))->assertOk();
    }

    public function test_admin_login_and_logout(): void
    {
        $admin = $this->admin();
        $this->post('/login', ['email' => $admin->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }
}
