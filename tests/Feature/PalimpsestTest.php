<?php

use App\Models\User;
use App\Models\Document;
use App\Models\Finding;
use App\Jobs\ScanDocumentJob;
use App\Services\RegexDetector;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

// ── Upload Flow ──────────────────────────────────────────────

test('authenticated user can upload a document', function () {
    Storage::fake('local');
    Queue::fake();

    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->post(route('documents.store'), [
        'document' => UploadedFile::fake()->create('test-file.txt', 100, 'text/plain'),
    ]);

    $response->assertRedirect(route('documents.index'));
    $this->assertDatabaseHas('documents', [
        'user_id' => $user->id,
        'original_filename' => 'test-file.txt',
        'status' => 'pending',
    ]);
});

test('upload dispatches a scan job', function () {
    Storage::fake('local');
    Queue::fake();

    $user = User::factory()->create();

    $this->actingAs($user)->post(route('documents.store'), [
        'document' => UploadedFile::fake()->create('scan-me.txt', 50, 'text/plain'),
    ]);

    Queue::assertPushed(ScanDocumentJob::class);
});

test('upload creates an audit log entry', function () {
    Storage::fake('local');
    Queue::fake();

    $user = User::factory()->create();

    $this->actingAs($user)->post(route('documents.store'), [
        'document' => UploadedFile::fake()->create('audit-test.txt', 50, 'text/plain'),
    ]);

    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $user->id,
        'action' => 'document_uploaded',
    ]);
});

test('unauthenticated user cannot upload', function () {
    $response = $this->post(route('documents.store'), [
        'document' => UploadedFile::fake()->create('blocked.txt', 50),
    ]);

    $response->assertRedirect(route('login'));
});

// ── Regex Detector ───────────────────────────────────────────

test('regex detector catches credit card numbers', function () {
    $detector = new RegexDetector();
    $findings = $detector->scan('My card is 4111111111111111 please charge it.');

    expect($findings)->not->toBeEmpty();
    expect($findings[0]['type'])->toBe('credit_card');
    expect($findings[0]['severity'])->toBe('high');
});

test('regex detector catches SSN patterns', function () {
    $detector = new RegexDetector();
    $findings = $detector->scan('SSN: 123-45-6789');

    expect($findings)->not->toBeEmpty();
    expect($findings[0]['type'])->toBe('ssn');
});

test('regex detector catches email addresses', function () {
    $detector = new RegexDetector();
    $findings = $detector->scan('Contact me at john@example.com');

    expect($findings)->not->toBeEmpty();
    expect($findings[0]['type'])->toBe('email');
    expect($findings[0]['severity'])->toBe('low');
});

test('regex detector catches API keys', function () {
    $detector = new RegexDetector();
    $findings = $detector->scan('API_KEY=sk_live_abc123def456ghi789jkl012');

    expect($findings)->not->toBeEmpty();
    $types = array_column($findings, 'type');
    expect($types)->toContain('api_key');
});

test('regex detector catches passwords in text', function () {
    $detector = new RegexDetector();
    $findings = $detector->scan('password: MySecretP@ss123');

    expect($findings)->not->toBeEmpty();
    expect($findings[0]['type'])->toBe('password');
    expect($findings[0]['severity'])->toBe('high');
});

test('regex detector returns empty for clean text', function () {
    $detector = new RegexDetector();
    $findings = $detector->scan('This is a perfectly normal sentence about the weather.');

    expect($findings)->toBeEmpty();
});

// ── RBAC / Authorization ─────────────────────────────────────

test('regular user cannot see raw findings', function () {
    $user = User::factory()->create(['role' => 'user']);

    expect($user->canViewRawFindings())->toBeFalse();
});

test('admin can see raw findings', function () {
    $user = User::factory()->create(['role' => 'admin']);

    expect($user->canViewRawFindings())->toBeTrue();
});

test('compliance user can see raw findings', function () {
    $user = User::factory()->create(['role' => 'compliance']);

    expect($user->canViewRawFindings())->toBeTrue();
});

test('regular user sees redacted view of document', function () {
    Storage::fake('local');

    $user = User::factory()->create(['role' => 'user']);
    $document = Document::factory()->create(['user_id' => $user->id, 'status' => 'complete']);

    $response = $this->actingAs($user)->get(route('documents.show', $document));

    $response->assertStatus(200);
    $response->assertSee('Redacted View');
});

test('admin sees raw view of document', function () {
    Storage::fake('local');

    $admin = User::factory()->create(['role' => 'admin']);
    $document = Document::factory()->create(['user_id' => $admin->id, 'status' => 'complete']);

    $response = $this->actingAs($admin)->get(route('documents.show', $document));

    $response->assertStatus(200);
    $response->assertSee('Raw View');
});

test('user cannot access another user document', function () {
    $user1 = User::factory()->create(['role' => 'user']);
    $user2 = User::factory()->create(['role' => 'user']);
    $document = Document::factory()->create(['user_id' => $user1->id]);

    $response = $this->actingAs($user2)->get(route('documents.show', $document));

    $response->assertStatus(403);
});

// ── Custom Rules ─────────────────────────────────────────────

test('admin can access custom rules page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('custom-rules.index'));

    $response->assertStatus(200);
});

test('regular user cannot access custom rules page', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get(route('custom-rules.index'));

    $response->assertStatus(403);
});

// ── Dashboard ────────────────────────────────────────────────

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
});
