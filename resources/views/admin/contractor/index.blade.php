<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kontraktor Menunggu Persetujuan</title>
</head>
<body>
    <h1>Daftar Kontraktor Menunggu Persetujuan</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if ($contractors->isEmpty())
        <p>Tidak ada kontraktor yang menunggu persetujuan.</p>
    @else
        @foreach ($contractors as $contractor)
            <div>
                <h2>
                    <a href="{{ route('contractor.profile.showPublic', $contractor->user->id) }}">
                        {{ $contractor->user->name }}
                    </a>
                </h2>
                @if ($contractor->foto_profile)
                    <a href="{{ route('contractor.profile.showPublic', $contractor->user->id) }}">
                        <img src="{{ Storage::url('contractors/' . $contractor->foto_profile) }}" width="100" alt="Foto Profil">
                    </a>
                @else
                    <p>Tidak ada foto profil.</p>
                @endif
                <p>Nama Lengkap: {{ $contractor->nama_depan }} {{ $contractor->nama_belakang }}</p>
                <p>Perusahaan: {{ $contractor->perusahaan }}</p>
                <p>Nomor NPWP: {{ $contractor->nomor_npwp }}</p>
                <p>Bidang Usaha:
                    @if ($contractor->bidang_usaha && count($contractor->bidang_usaha) > 0)
                        @foreach ($contractor->bidang_usaha as $usaha)
                            {{ $usaha }};
                        @endforeach
                    @else
                        Tidak diisi
                    @endif
                </p>

                <!-- Dokumen Pendukung -->
                @if ($contractor->dokumen_pendukung && count($contractor->dokumen_pendukung) > 0)
                    <div class="mb-3">
                        <strong>Dokumen Pendukung:</strong>
                        <ul>
                            @foreach ($contractor->dokumen_pendukung as $dokumen)
                                <li>
                                    @php
                                        $extension = pathinfo($dokumen, PATHINFO_EXTENSION);
                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    @endphp
                                    @if (in_array(strtolower($extension), $imageExtensions))
                                        <a href="{{ Storage::url('contractors/documents/' . $dokumen) }}" target="_blank">
                                            <img src="{{ Storage::url('contractors/documents/' . $dokumen) }}" width="150" alt="Dokumen Pendukung" style="margin: 5px;">
                                        </a>
                                    @else
                                        <a href="{{ Storage::url('contractors/documents/' . $dokumen) }}" target="_blank">
                                            {{ $dokumen }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Gambar Data Diri -->
                @if (!empty($contractor->identity_images) && count($contractor->identity_images) > 0)
                <div class="mb-3">
                    <strong>Gambar Data Diri:</strong>
                    <ul>
                        @foreach ($contractor->identity_images as $image)
                            @php
                                $url = Storage::url($image);
                                $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            @endphp
                            <li>
                                <a href="{{ $url }}" target="_blank">
                                    @if (in_array($extension, $imageExtensions))
                                        <img src="{{ $url }}" width="150" alt="Gambar Data Diri" style="margin: 5px;">
                                    @else
                                        {{ basename($image) }}
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

                <!-- Portofolio -->
                @if ($contractor->portofolio && count($contractor->portofolio) > 0)
                    <div class="mb-3">
                        <strong>Portofolio:</strong>
                        <ul>
                            @foreach ($contractor->portofolio as $port)
                                <li>
                                    @php
                                        $extension = pathinfo($port, PATHINFO_EXTENSION);
                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                                    @endphp
                                    @if (in_array(strtolower($extension), $imageExtensions))
                                        <a href="{{ Storage::url('contractors/portfolios/' . $port) }}" target="_blank">
                                            <img src="{{ Storage::url('contractors/portfolios/' . $port) }}" width="150" alt="Portofolio" style="margin: 5px;">
                                        </a>
                                    @else
                                        <a href="{{ Storage::url('contractors/portfolios/' . $port) }}" target="_blank">
                                            {{ $port }}
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.contractors.approve', $contractor->id) }}" method="POST">
                    @csrf
                    <label>
                        <input type="radio" name="approved" value="1" required> Setujui
                    </label>
                    <label>
                        <input type="radio" name="approved" value="0"> Tolak
                    </label>
                    <br>
                    <label>Catatan (opsional):</label>
                    <textarea name="admin_note" placeholder="Tulis catatan untuk kontraktor..."></textarea>
                    <button type="submit">Simpan Keputusan</button>
                </form>
                <hr>
            </div>
        @endforeach
    @endif
    <a href="{{ route('home') }}">Kembali ke Home</a>
</body>
</html>
