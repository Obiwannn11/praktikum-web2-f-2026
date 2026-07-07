<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('bisa registrasi dan mendapatkan token', function () {
    postJson('/api/register', [
        'name' => 'Andi',
        'email' => 'andi@example.com',
        'password' => 'rahasia',
        'password_confirmation' => 'rahasia',
    ])
        ->assertStatus(201)
        ->assertJsonPath('status', 'success')
        ->assertJsonPath('data.user.role', 'user')
        ->assertJsonStructure(['data' => ['token']]);
});

it('bisa login dan mendapatkan token', function () {
    User::create([
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'password' => Hash::make('rahasia'),
        'role' => 'user',
    ]);

    postJson('/api/login', [
        'email' => 'budi@example.com',
        'password' => 'rahasia',
    ])
        ->assertOk()
        ->assertJsonPath('message', 'Berhasil Login')
        ->assertJsonStructure(['data' => ['token']]);
});

it('menolak login dengan password salah', function () {
    User::create([
        'name' => 'Budi',
        'email' => 'budi@example.com',
        'password' => Hash::make('rahasia'),
        'role' => 'user',
    ]);

    postJson('/api/login', [
        'email' => 'budi@example.com',
        'password' => 'salah',
    ])->assertStatus(422);
});

it('menolak akses route terproteksi tanpa token (401)', function () {
    getJson('/api/categories')->assertUnauthorized();
});

it('mengizinkan akses route terproteksi dengan token (200)', function () {
    $user = User::create([
        'name' => 'Andi',
        'email' => 'andi@example.com',
        'password' => Hash::make('rahasia'),
        'role' => 'user',
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    getJson('/api/categories', ['Authorization' => "Bearer {$token}"])
        ->assertOk()
        ->assertJsonPath('status', 'success');
});
