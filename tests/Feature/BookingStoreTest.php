<?php

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingCreatedNotification;

beforeEach(function () {
    // Fake storage untuk testing file upload
    Storage::fake('public');
    // Fake notification untuk memastikan notifikasi dikirim
    Notification::fake();
    // Buat user dan kontraktor untuk testing
    $this->user = User::factory()->create(['role' => 'user']);
    $this->contractor = User::factory()->create(['role' => 'kontraktor']);
    // Login sebagai user
    $this->actingAs($this->user);
});

it('can create a booking with valid data', function () {
    $data = [
        'judul' => 'Renovasi Rumah',
        'deskripsi' => 'Renovasi ruang tamu dan dapur.',
        'lokasi' => 'Jakarta Selatan',
        'estimasi_anggaran' => 50000000,
        'durasi' => '2 bulan',
        'gambar' => [
            UploadedFile::fake()->image('gambar1.jpg'),
            UploadedFile::fake()->image('gambar2.jpg'),
        ],
        'dokumen' => UploadedFile::fake()->create('dokumen.pdf', 1000, 'application/pdf'),
    ];

    $response = $this->post(route('bookings.store', $this->contractor->id), $data);

    // Assert redirect ke halaman index dengan pesan sukses
    $response->assertRedirect(route('bookings.index'));
    $response->assertSessionHas('success', 'Pesanan berhasil dibuat dan menunggu persetujuan kontraktor dalam 24 jam!');

    // Assert data tersimpan di database
    $this->assertDatabaseHas('bookings', [
        'user_id' => $this->user->id,
        'contractor_id' => $this->contractor->id,
        'judul' => 'Renovasi Rumah',
        'lokasi' => 'Jakarta Selatan',
        'status' => 'pending',
    ]);

    // Assert file gambar dan dokumen tersimpan
    $booking = Booking::first();
    expect(Storage::disk('public')->exists('bookings/' . $booking->gambar[0]))->toBeTrue();
    expect(Storage::disk('public')->exists($booking->dokumen))->toBeTrue();

    // Assert notifikasi dikirim ke kontraktor
    Notification::assertSentTo(
        $this->contractor,
        BookingCreatedNotification::class,
        function ($notification, $channels) {
            return in_array('mail', $channels); // Pastikan notifikasi dikirim via mail
        }
    );
});

it('fails to create a booking with invalid data', function () {
    $data = [
        'judul' => '', // Judul kosong, seharusnya gagal
        'deskripsi' => 'Renovasi ruang tamu.',
        'lokasi' => 'Jakarta',
        'estimasi_anggaran' => 50000000,
        'durasi' => '2 bulan',
    ];

    $response = $this->post(route('bookings.store', $this->contractor->id), $data);

    // Assert validasi gagal
    $response->assertSessionHasErrors(['judul']);
    $this->assertDatabaseMissing('bookings', [
        'user_id' => $this->user->id,
        'contractor_id' => $this->contractor->id,
    ]);
});

it('fails if contractor is not a kontraktor', function () {
    $invalidContractor = User::factory()->create(['role' => 'user']); // Bukan kontraktor

    $data = [
        'judul' => 'Renovasi Rumah',
        'deskripsi' => 'Renovasi ruang tamu.',
        'lokasi' => 'Jakarta',
        'estimasi_anggaran' => 50000000,
        'durasi' => '2 bulan',
    ];

    $response = $this->post(route('bookings.store', $invalidContractor->id), $data);

    // Assert redirect dengan pesan error
    $response->assertRedirect();
    $response->assertSessionHas('error', 'Pengguna ini bukan kontraktor.');
    $this->assertDatabaseMissing('bookings', [
        'user_id' => $this->user->id,
        'contractor_id' => $invalidContractor->id,
    ]);
});

it('fails if user profile is incomplete', function () {
    // Asumsi ProfileController::isProfileComplete mengembalikan false
    // Mock method ini menggunakan Laravel's mocking
    $this->mock(\App\Http\Controllers\ProfileController::class, function ($mock) {
        $mock->shouldReceive('isProfileComplete')->andReturn(false);
    });

    $data = [
        'judul' => 'Renovasi Rumah',
        'deskripsi' => 'Renovasi ruang tamu.',
        'lokasi' => 'Jakarta',
        'estimasi_anggaran' => 50000000,
        'durasi' => '2 bulan',
    ];

    $response = $this->post(route('bookings.store', $this->contractor->id), $data);

    // Assert redirect ke halaman edit profil
    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
    $this->assertDatabaseMissing('bookings', [
        'user_id' => $this->user->id,
        'contractor_id' => $this->contractor->id,
    ]);
});
