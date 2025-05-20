<form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Profil Akademik</label>
    <input type="text" name="academic_profile" value="{{ old('academic_profile') }}">

    <label>Keterampilan</label>
    <textarea name="skills">{{ old('skills') }}</textarea>

    <label>Sertifikasi</label>
    <textarea name="certifications">{{ old('certifications') }}</textarea>

    <label>Pengalaman</label>
    <textarea name="experiences">{{ old('experiences') }}</textarea>

    <label>Lokasi Magang</label>
    <input type="text" name="preferred_location" value="{{ old('preferred_location') }}">

    <label>Jenis Magang</label>
    <input type="text" name="internship_type" value="{{ old('internship_type') }}">

    <label>Upload CV</label>
    <input type="file" name="cv">

    <label>Upload Surat Pengantar</label>
    <input type="file" name="cover_letter">

    <label>Upload Sertifikat</label>
    <input type="file" name="certificate">

    <button type="submit">Simpan Profil</button>
</form>
