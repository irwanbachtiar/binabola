

<?php $__env->startSection('title', 'Laporan Tahunan'); ?>

<?php $__env->startSection('page-title', 'Laporan Tahunan - Statistik Siswa Per Bulan'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('laporan.tahunan')); ?>" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Pilih Tahun</label>
                        <select class="form-select" name="tahun" required>
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-info w-100">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-7 text-end">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <a href="<?php echo e(route('laporan.index')); ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <h4>Tahun: <?php echo e($tahun); ?></h4>
    </div>
</div>

<!-- Ringkasan -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6>Total Siswa Aktif</h6>
                <h2><?php echo e($totalSiswa); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6>Total Hadir</h6>
                <h2><?php echo e($absensi['Hadir'] ?? 0); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6>Total Izin/Sakit</h6>
                <h2><?php echo e(($absensi['Izin'] ?? 0) + ($absensi['Sakit'] ?? 0)); ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h6>Total Alpa</h6>
                <h2><?php echo e($absensi['Alpa'] ?? 0); ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Per Bulan -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistik Evaluasi Per Bulan</h5>
            </div>
            <div class="card-body">
                <?php if($evaluasiPerBulan->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-center">Jumlah Siswa</th>
                                    <th class="text-center">Rata-rata Nilai</th>
                                    <th class="text-center">Nilai Tertinggi</th>
                                    <th class="text-center">Nilai Terendah</th>
                                    <th class="text-center">Total Evaluasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $evaluasiPerBulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bulan => $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e(\Carbon\Carbon::parse($bulan)->format('F Y')); ?></strong></td>
                                    <td class="text-center"><?php echo e($stat['jumlah_siswa']); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?php echo e($stat['rata_rata_nilai']); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?php echo e($stat['nilai_tertinggi']); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger"><?php echo e($stat['nilai_terendah']); ?></span>
                                    </td>
                                    <td class="text-center"><?php echo e($stat['total_evaluasi']); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted py-4">Belum ada data evaluasi untuk tahun ini</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Top 10 Siswa dan Statistik Kategori -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 10 Siswa Terbaik</h5>
            </div>
            <div class="card-body">
                <?php if($topSiswa->count() > 0): ?>
                    <?php $__currentLoopData = $topSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-2 pb-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                            <div class="me-2" style="width: 30px;">
                                <?php if($index < 3): ?>
                                    <i class="bi bi-trophy-fill <?php echo e($index == 0 ? 'text-warning' : ($index == 1 ? 'text-secondary' : '')); ?>" style="<?php echo e($index == 2 ? 'color: #cd7f32;' : ''); ?>"></i>
                                <?php else: ?>
                                    <span class="text-muted"><?php echo e($index + 1); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <strong><?php echo e($siswa->nama); ?></strong>
                            </div>
                            <span class="badge bg-success"><?php echo e($siswa->avg_nilai ? number_format($siswa->avg_nilai, 1) : '-'); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p class="text-center text-muted">Belum ada data</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistik Per Kategori Penilaian</h5>
            </div>
            <div class="card-body">
                <?php if($statistikKategori->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-center">Rata-rata</th>
                                    <th class="text-center">Tertinggi</th>
                                    <th class="text-center">Terendah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $statistikKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($stat['nama']); ?></td>
                                    <td class="text-center"><span class="badge bg-info"><?php echo e($stat['rata_rata']); ?></span></td>
                                    <td class="text-center"><span class="badge bg-success"><?php echo e($stat['tertinggi']); ?></span></td>
                                    <td class="text-center"><span class="badge bg-danger"><?php echo e($stat['terendah']); ?></span></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted">Belum ada data</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/laporan/tahunan.blade.php ENDPATH**/ ?>