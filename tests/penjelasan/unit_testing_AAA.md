# Laporan Implementasi Unit Testing dengan Mockery dan Test Case di Laravel

**Nama Kelompok:** Kelompok 6 
**Mata Kuliah:** Sistem Informasi Magang  
**Kelas:** TI-2B  
**Tahun:** 2025

---

## Anggota Kelompok

| No | Nama | Absen |
|----|------|-----|
| 1  | Ekya Muhammad | 09 |
| 2  | Faishal Harist | 10 |
| 3  | Rizqi Fauzan | 26 |
| 4  | Satrio Ahmad | 27 |

---

## 1. Konsep Dasar Unit Testing

### 1.1 Pola AAA (Arrange-Act-Assert)

Dalam implementasi unit testing kami, kami menggunakan pola **AAA** yang merupakan best practice dalam penulisan test case:

#### **Arrange (Persiapan)**
Tahap ini meliputi:
- Menyiapkan data yang dibutuhkan untuk test
- Membuat mock objects atau stub
- Mengkonfigurasi dependencies
- Menyiapkan state awal yang diperlukan

#### **Act (Aksi)**
Tahap ini melakukan:
- Eksekusi method atau fungsi yang sedang diuji
- Memanggil satu aksi utama (System Under Test)

#### **Assert (Verifikasi)**
Tahap ini memverifikasi:
- Hasil yang dikembalikan sesuai ekspektasi
- State berubah dengan benar
- Interaksi dengan dependencies terjadi sesuai harapan

---

## 2. Test Double: Mock vs Stub

### 2.1 Mock Object
**Mock** digunakan ketika kita ingin:
- Memverifikasi **interaksi** antara objects
- Memastikan method dipanggil dengan argumen yang tepat
- Memastikan jumlah pemanggilan sesuai ekspektasi
- Menguji **kolaborasi** antar komponen

**Karakteristik Mock:**
```php
$repoMock->shouldReceive('find')
    ->once()                    // Verifikasi dipanggil 1 kali
    ->with(1)                   // Verifikasi argumen yang diterima
    ->andReturn($expectedUser); // Stub behavior
```

### 2.2 Stub Object
**Stub** digunakan ketika kita hanya butuh:
- Nilai kembalian (return value) untuk melanjutkan test
- Tidak peduli dengan detail interaksi
- Implementasi sederhana tanpa verifikasi kompleks

**Karakteristik Stub:**
```php
$userRepoStub = new class implements UserRepository {
    public function find(int $id): User {
        return new User(['name' => 'NamaStub']);
    }
};
```

---

## 3. Implementasi Testing

### 3.1 Test Case 1: UserServiceTest.php

File ini mendemonstrasikan penggunaan dasar Mock dan Stub pada service sederhana.

#### **Skenario Test dengan Mock:**
```php
public function testGetUserReturnsCorrectDataWithMock()
```

**Alur Testing:**

1. **Arrange:**
   - Membuat mock dari `UserRepository` menggunakan Mockery
   - Membuat expected user dengan data dummy
   - Mengkonfigurasi mock untuk merespon pemanggilan `find(1)`
   - Membuat instance `UserService` dengan dependency mock

2. **Act:**
   - Memanggil `$service->getUser(1)`

3. **Assert:**
   - Memverifikasi return type adalah instance of `User`
   - Memverifikasi data user sesuai ekspektasi (name = 'Rizqi', id = 1)
   - Mock otomatis memverifikasi interaksi (once, with parameter 1)

#### **Skenario Test dengan Stub:**
```php
public function testGetUserReturnsCorrectDataWithStub()
```

**Alur Testing:**

1. **Arrange:**
   - Membuat stub menggunakan anonymous class yang implements `UserRepository`
   - Stub hanya mengembalikan User dengan name 'NamaStub'
   - Membuat instance `UserService` dengan dependency stub

2. **Act:**
   - Memanggil `$service->getUser(7)`

3. **Assert:**
   - Memverifikasi return type dan data
   - **Tidak ada** verifikasi interaksi (berbeda dengan mock)

**Kesimpulan:** Test ini menunjukkan perbedaan fundamental antara Mock (verifikasi interaksi) dan Stub (hanya return value).

---

### 3.2 Test Case 2: PengajuanMagangWithMockTest.php

File ini mendemonstrasikan penggunaan Mock dan Stub pada konteks yang lebih relevan dengan domain aplikasi kami.

#### **Skenario Test dengan Mock:**
```php
public function testAjukanMengembalikanModelDenganMock()
```

**Alur Testing:**

1. **Arrange:**
   - Membuat mock dari `PengajuanMagangRepository`
   - Membuat model pengajuan dengan data (mahasiswa_id: 10, lowongan_id: 99)
   - Mengkonfigurasi mock dengan `shouldReceive('create')`:
     * Harus dipanggil `once()`
     * Menerima parameter dengan kondisi tertentu menggunakan `Mockery::on()`
     * Memverifikasi struktur data (mahasiswa_id, lowongan_id, status)
   - Membuat instance service dengan mock repository

2. **Act:**
   - Memanggil `$service->ajukan(10, 99)`

3. **Assert:**
   - Memverifikasi return type adalah `PengajuanMagangModel`
   - Memverifikasi semua properti (pengajuan_id, mahasiswa_id, lowongan_id, status)
   - Mock memverifikasi method `create` dipanggil dengan parameter yang tepat

#### **Skenario Test dengan Stub:**
```php
public function testAjukanMengembalikanModelDenganStub()
```

**Alur Testing:**

1. **Arrange:**
   - Membuat stub repository menggunakan anonymous class
   - Stub selalu mengembalikan model dengan pengajuan_id: 5678
   - Membuat service dengan stub repository

2. **Act:**
   - Memanggil `$service->ajukan(20, 77)`

3. **Assert:**
   - Memverifikasi return type dan semua properti model
   - Tidak ada verifikasi interaksi (karakteristik stub)

**Kesimpulan:** Test ini menunjukkan implementasi Mock dan Stub pada use case nyata dalam sistem magang kami.

---

### 3.3 Test Case 3: PengajuanMagangTest.php (Integration Test)

File ini berbeda dari dua test sebelumnya karena merupakan **Integration Test** yang menggunakan database real dengan `DatabaseTransactions`.

#### **Test 1: Pengajuan Magang Sukses**
```php
public function test_store_successful_pengajuan()
```

**Alur Testing:**

1. **Arrange:**
   - Mengambil data master dari database (ProgramStudi, Wilayah, Skema, dll)
   - Membuat user mahasiswa baru
   - Membuat data mahasiswa lengkap dengan relasi
   - Membuat lowongan magang
   - Mock `Auth::id()` untuk simulasi login
   - Membuat Request dengan lowongan_id

2. **Act:**
   - Membuat instance `PengajuanMagangController`
   - Memanggil `$controller->store($request)`

3. **Assert:**
   - Memverifikasi status code response adalah 302 (redirect)
   - Memverifikasi data tersimpan di database menggunakan `assertDatabaseHas`
   - Memverifikasi status pengajuan adalah 'diajukan'

**Karakteristik:**
- Menggunakan database real
- `DatabaseTransactions` memastikan rollback otomatis setelah test
- Menguji flow lengkap dari controller hingga database

#### **Test 2: Pengajuan Duplikat**
```php
public function test_store_pengajuan_duplicate()
```

**Alur Testing:**

1. **Arrange:**
   - Setup sama dengan test sukses
   - **Tambahan:** Membuat pengajuan yang sudah ada sebelumnya
   - Simulasi skenario mahasiswa mengajukan lowongan yang sama dua kali

2. **Act:**
   - Memanggil `$controller->store($request)` dengan lowongan yang sama

3. **Assert:**
   - Memverifikasi session memiliki error
   - Memverifikasi pesan error berisi 'sudah mengajukan'

**Tujuan:** Menguji business logic yang mencegah duplikasi pengajuan.

#### **Test 3: Mahasiswa Sudah Diterima**
```php
public function test_store_pengajuan_sudah_diterima()
```

**Alur Testing:**

1. **Arrange:**
   - Setup mahasiswa dan lowongan
   - Membuat pengajuan pertama dengan status 'diterima'
   - Membuat lowongan kedua untuk diajukan

2. **Act:**
   - Mahasiswa mencoba mengajukan lowongan kedua

3. **Assert:**
   - Memverifikasi session memiliki error
   - Memverifikasi pesan error berisi 'sudah memiliki magang'

**Tujuan:** Menguji business rule bahwa mahasiswa hanya bisa memiliki satu magang aktif.

---

## 4. Perbandingan Pendekatan Testing

| Aspek | Unit Test (Mock/Stub) | Integration Test (Database) |
|-------|----------------------|---------------------------|
| **Kecepatan** | Sangat cepat | Lebih lambat |
| **Isolasi** | Tinggi (isolated) | Rendah (banyak dependency) |
| **Database** | Tidak perlu | Butuh database |
| **Scope** | Satu unit/method | Flow lengkap |
| **Maintenance** | Mudah | Lebih kompleks |
| **Confidence** | Lower (karena isolated) | Higher (test real scenario) |

**Implementasi Kami:**
- `UserServiceTest.php` & `PengajuanMagangWithMockTest.php`: **Pure Unit Test**
- `PengajuanMagangTest.php`: **Integration Test**

---

## 5. Best Practices yang Kami Terapkan

### 5.1 Naming Convention
- Test method selalu diawali dengan `test_` atau menggunakan annotation `@test`
- Nama test menjelaskan skenario yang diuji
- Format: `test_metodYangDiuji_kondisi_hasilYangDiharapkan`

### 5.2 Test Isolation
- Setiap test berdiri sendiri dan tidak bergantung pada test lain
- Menggunakan `DatabaseTransactions` untuk rollback otomatis
- `tearDown()` untuk cleanup mock (Mockery::close())

### 5.3 Pola AAA yang Konsisten
- Selalu memisahkan tiga fase dengan komentar yang jelas
- Arrange: Setup semua yang dibutuhkan
- Act: Satu aksi utama
- Assert: Verifikasi hasil

### 5.4 Mock Configuration
- Menggunakan `shouldReceive()` untuk define ekspektasi
- `once()` untuk verifikasi jumlah pemanggilan
- `with()` untuk verifikasi parameter
- `andReturn()` untuk stub behavior

---

## 6. Kesimpulan

Dari implementasi unit testing ini, kami telah mempelajari dan menerapkan:

1. **Pola AAA** sebagai struktur standar penulisan test yang clean dan mudah dipahami

2. **Mock vs Stub:**
   - Mock untuk verifikasi interaksi dan kolaborasi
   - Stub untuk menyediakan return value sederhana
   - Mockery sebagai tool yang powerful untuk membuat test double

3. **Dua Pendekatan Testing:**
   - Unit Test murni dengan mock/stub (cepat, isolated)
   - Integration Test dengan database (slower, higher confidence)

4. **Testing Business Logic:**
   - Skenario sukses (happy path)
   - Skenario error (edge cases)
   - Validasi business rules

5. **Best Practices:**
   - Test isolation dengan DatabaseTransactions
   - Proper cleanup dengan tearDown()
   - Descriptive test names
   - Consistent AAA pattern

Implementasi ini memberikan kami confidence bahwa logic pengajuan magang di sistem kami bekerja sesuai ekspektasi, baik dari sisi unit (method individual) maupun integration (flow lengkap dengan database).

---

## 7. File Testing yang Disubmit

1. **tests/Unit/ExampleTest.php** - Basic test template
2. **tests/Unit/UserServiceTest.php** - Demo Mock vs Stub pattern
3. **tests/Unit/PengajuanMagangWithMockTest.php** - Mock/Stub pada domain aplikasi
4. **tests/Unit/PengajuanMagangTest.php** - Integration test dengan database

Semua test dapat dijalankan dengan command:
```bash
php artisan test tests/Unit
```

---

**Terima kasih atas bimbingan Bapak/Ibu Dosen dalam memberikan pemahaman tentang unit testing, mockery, dan test case di Laravel. Semoga implementasi ini sesuai dengan ekspektasi.**