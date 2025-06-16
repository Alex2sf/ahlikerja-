<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use App\Models\Booking;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_booking_with_complete_profile()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'user']);
        $contractor = User::factory()->create(['role' => 'kontraktor']);
        Profile::factory()->create([
            'user_id' => $user->id,
            'nama_lengkap' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->actingAs($user);

        $data = [
            'judul' => 'Rumah Minimalis',
            'deskripsi' => 'Desain rumah minimalis 2 lantai',
            'lokasi' => 'Jakarta',
            'estimasi_anggaran' => 500000000,
            'durasi' => '2 bulan',
            'gambar' => [UploadedFile::fake()->image('gambar1.jpg'), UploadedFile::fake()->image('gambar2.jpg')],
            'dokumen' => UploadedFile::fake()->create('spk.pdf', 1024, 'application/pdf'),
        ];

        $response = $this->post(route('bookings.store', $contractor->id), $data);

        $response->assertRedirect(route('bookings.index'));
        $response->assertSessionHas('success', 'Pesanan berhasil dibuat dan menunggu persetujuan kontraktor dalam 24 jam!');
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'contractor_id' => $contractor->id,
            'judul' => 'Rumah Minimalis',
            'status' => 'pending',
        ]);
    }
}
