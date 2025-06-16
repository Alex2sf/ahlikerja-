<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

beforeEach(function () {
    // Jalankan migrasi untuk memastikan tabel users tersedia
    $this->artisan('migrate', ['--env' => 'testing']);
});

it('can show register form', function () {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
    $response->assertViewIs('auth.register');
});

it('can register a new user with valid data', function () {
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'user',
    ];

    $response = $this->post(route('register'), $data);

    // Assert redirect ke halaman home
    $response->assertRedirect('/home');

    // Assert user tersimpan di database
    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'user',
    ]);

    // Assert user sudah login
    $this->assertAuthenticated();
    $this->assertEquals('John Doe', Auth::user()->name);
});

it('fails to register with invalid data', function () {
    $data = [
        'name' => '', // Nama kosong, seharusnya gagal
        'email' => 'not-an-email', // Email tidak valid
        'password' => 'short', // Password terlalu pendek
        'password_confirmation' => 'different', // Konfirmasi tidak cocok
        'role' => 'invalid', // Role tidak valid
    ];

    $response = $this->post(route('register'), $data);

    // Assert validasi gagal
    $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    $this->assertDatabaseMissing('users', [
        'email' => 'not-an-email',
    ]);
    $this->assertGuest(); // Pastikan user tidak login
});

it('fails to register with duplicate email', function () {
    // Buat user terlebih dahulu
    User::factory()->create(['email' => 'john@example.com']);

    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com', // Email sudah ada
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'user',
    ];

    $response = $this->post(route('register'), $data);

    // Assert validasi gagal karena email sudah terdaftar
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

it('can show login form', function () {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
    $response->assertViewIs('auth.login');
});

it('can login with valid credentials', function () {
    // Buat user untuk login
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response = $this->post(route('login'), [
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    // Assert redirect ke halaman home
    $response->assertRedirect('/home');
    $this->assertAuthenticated();
});

it('fails to login with invalid credentials', function () {
    $response = $this->post(route('login'), [
        'email' => 'john@example.com',
        'password' => 'wrong-password',
    ]);

    // Assert redirect kembali dengan error
    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

it('can logout', function () {
    // Buat user dan login
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('logout'));

    // Assert redirect ke halaman login
    $response->assertRedirect('/login');
    $this->assertGuest(); // Pastikan user sudah logout
    $this->assertFalse(Session::has('auth')); // Pastikan session diinvalidate
});
