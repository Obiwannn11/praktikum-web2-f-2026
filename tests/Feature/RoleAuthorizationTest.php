<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\deleteJson;

uses(RefreshDatabase::class);

/**
 * Helper: buat user dengan role tertentu lalu kembalikan header Bearer token.
 */
function tokenHeader(string $role): array
{
    $user = User::create([
        'name' => 'User '.$role,
        'email' => $role.'@example.com',
        'password' => 'password',
        'role' => $role,
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    return ['Authorization' => "Bearer {$token}"];
}

it('menolak user biasa menghapus data (403)', function () {
    $category = Category::create(['name' => 'Fiksi']);

    deleteJson("/api/categories/{$category->id}", [], tokenHeader('user'))
        ->assertStatus(403)
        ->assertJsonPath('status', 'error');

    expect(Category::count())->toBe(1);
});

it('mengizinkan admin menghapus data', function () {
    $category = Category::create(['name' => 'Fiksi']);

    deleteJson("/api/categories/{$category->id}", [], tokenHeader('admin'))
        ->assertOk()
        ->assertJsonPath('status', 'success');

    expect(Category::count())->toBe(0);
});
