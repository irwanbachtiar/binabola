<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Siswa;
use App\Models\KategoriPenilaian;
use App\Models\EvaluasiSiswa;
use App\Models\Absensi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class EvaluasiBatchTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $siswa;
    protected $kategori;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user (admin/coach)
        $this->user = User::factory()->create([
            'role' => 'admin'
        ]);
        
        // Create test siswa
        $this->siswa = Siswa::factory()->create([
            'nama' => 'Test Siswa Batch',
            'status' => 'Aktif'
        ]);
        
        // Create test kategori
        $this->kategori = KategoriPenilaian::create([
            'nama' => 'Test Kategori',
            'bobot' => 20,
            'aktif' => true,
            'urutan' => 1
        ]);
    }

    /** @test */
    public function batch_evaluation_page_can_be_accessed()
    {
        $response = $this->actingAs($this->user)
            ->get(route('evaluasi.batch'));
        
        $response->assertStatus(200);
        $response->assertViewIs('evaluasi.batch');
    }

    /** @test */
    public function can_get_siswa_by_tanggal_with_attendance()
    {
        // Create attendance for today
        $tanggal = date('Y-m-d');
        Absensi::create([
            'siswa_id' => $this->siswa->id,
            'tanggal' => $tanggal,
            'status' => 'Hadir'
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('evaluasi.batch.siswa', ['tanggal' => $tanggal]));
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'siswa' => [
                '*' => ['id', 'nama', 'kelompok_umur']
            ],
            'kategoris'
        ]);
        
        $this->assertCount(1, $response->json('siswa'));
        $this->assertEquals($this->siswa->nama, $response->json('siswa')[0]['nama']);
    }

    /** @test */
    public function batch_evaluation_data_syncs_with_regular_evaluation()
    {
        // Create attendance
        $tanggal = date('Y-m-d');
        $minggu = date('W');
        $tahun = date('Y');
        
        Absensi::create([
            'siswa_id' => $this->siswa->id,
            'tanggal' => $tanggal,
            'status' => 'Hadir'
        ]);

        // Submit batch evaluation
        $response = $this->actingAs($this->user)
            ->post(route('evaluasi.batch.store'), [
                'tanggal_evaluasi' => $tanggal,
                'minggu' => $minggu,
                'siswa_ids' => [$this->siswa->id],
                'nilai' => [
                    $this->siswa->id => [
                        $this->kategori->id => 85
                    ]
                ]
            ]);

        $response->assertRedirect(route('evaluasi.batch'));
        $response->assertSessionHas('success');

        // Verify data exists in database
        $this->assertDatabaseHas('evaluasi_siswas', [
            'siswa_id' => $this->siswa->id,
            'kategori_penilaian_id' => $this->kategori->id,
            'minggu' => $minggu,
            'tahun' => $tahun,
            'nilai' => 85,
            'tanggal_evaluasi' => $tanggal
        ]);

        // Verify data appears in regular evaluation page
        $evaluasi = EvaluasiSiswa::where('siswa_id', $this->siswa->id)
            ->where('minggu', $minggu)
            ->where('tahun', $tahun)
            ->first();

        $this->assertNotNull($evaluasi);
        $this->assertEquals(85, $evaluasi->nilai);
    }

    /** @test */
    public function batch_evaluation_updates_existing_data()
    {
        $tanggal = date('Y-m-d');
        $minggu = date('W');
        $tahun = date('Y');

        // Create existing evaluation
        EvaluasiSiswa::create([
            'siswa_id' => $this->siswa->id,
            'kategori_penilaian_id' => $this->kategori->id,
            'minggu' => $minggu,
            'tahun' => $tahun,
            'nilai' => 70,
            'tanggal_evaluasi' => $tanggal
        ]);

        // Create attendance
        Absensi::create([
            'siswa_id' => $this->siswa->id,
            'tanggal' => $tanggal,
            'status' => 'Hadir'
        ]);

        // Update via batch
        $response = $this->actingAs($this->user)
            ->post(route('evaluasi.batch.store'), [
                'tanggal_evaluasi' => $tanggal,
                'minggu' => $minggu,
                'siswa_ids' => [$this->siswa->id],
                'nilai' => [
                    $this->siswa->id => [
                        $this->kategori->id => 90
                    ]
                ]
            ]);

        $response->assertRedirect(route('evaluasi.batch'));

        // Verify updated value
        $evaluasi = EvaluasiSiswa::where('siswa_id', $this->siswa->id)
            ->where('kategori_penilaian_id', $this->kategori->id)
            ->where('minggu', $minggu)
            ->where('tahun', $tahun)
            ->first();

        $this->assertEquals(90, $evaluasi->nilai);
        
        // Verify only one record exists (update, not create new)
        $count = EvaluasiSiswa::where('siswa_id', $this->siswa->id)
            ->where('kategori_penilaian_id', $this->kategori->id)
            ->where('minggu', $minggu)
            ->where('tahun', $tahun)
            ->count();
        
        $this->assertEquals(1, $count);
    }

    /** @test */
    public function only_shows_siswa_with_hadir_status()
    {
        $tanggal = date('Y-m-d');
        
        // Create siswa with different attendance status
        $siswa2 = Siswa::factory()->create(['nama' => 'Siswa Izin', 'status' => 'Aktif']);
        $siswa3 = Siswa::factory()->create(['nama' => 'Siswa Sakit', 'status' => 'Aktif']);

        Absensi::create(['siswa_id' => $this->siswa->id, 'tanggal' => $tanggal, 'status' => 'Hadir']);
        Absensi::create(['siswa_id' => $siswa2->id, 'tanggal' => $tanggal, 'status' => 'Izin']);
        Absensi::create(['siswa_id' => $siswa3->id, 'tanggal' => $tanggal, 'status' => 'Sakit']);

        $response = $this->actingAs($this->user)
            ->getJson(route('evaluasi.batch.siswa', ['tanggal' => $tanggal]));
        
        // Should only return 1 siswa (status Hadir)
        $this->assertCount(1, $response->json('siswa'));
        $this->assertEquals($this->siswa->nama, $response->json('siswa')[0]['nama']);
    }
}
