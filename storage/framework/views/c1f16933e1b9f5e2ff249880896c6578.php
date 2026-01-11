<?php $__env->startSection('title', 'Progress Evaluasi - ' . $siswa->nama); ?>

<?php $__env->startSection('page-title', 'Progress & Evaluasi Siswa'); ?>

<?php $__env->startPush('styles'); ?>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<style>
    /* Statistik kehadiran - penyesuaian ukuran dan jarak agar muat di kotak */
    .stat-card .card-body { padding: 0.9rem; }
    .stat-card .rounded-circle { width: 44px; height: 44px; font-size: 18px; }
    .stat-card .h4 { font-size: 1.25rem; margin-bottom: 0; line-height: 1; }
    .stat-card small { font-size: 0.85rem; }
    .stat-card .flex-grow-1 { padding-left: 4px; }
    @media (max-width: 576px) {
        .stat-card .rounded-circle { width: 40px; height: 40px; font-size: 16px; }
        .stat-card .h4 { font-size: 1.1rem; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <?php if($siswa->foto): ?>
                        <img src="<?php echo e(asset($siswa->foto)); ?>" alt="Foto" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person fs-3 text-white"></i>
                        </div>
                    <?php endif; ?>
                    <div class="flex-grow-1">
                        <h4 class="mb-1"><?php echo e($siswa->nama); ?></h4>
                        <p class="mb-1 text-muted"><?php echo e($siswa->umur_detail); ?> • <?php echo e($siswa->minat_posisi_string); ?></p>
                    </div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-evaluasi')): ?>
                    <a href="<?php echo e(route('evaluasi.create', $siswa->id)); ?>" class="btn btn-primary disabled" role="button" aria-disabled="true" tabindex="-1" onclick="event.preventDefault();">
                        <i class="bi bi-plus-circle"></i> Input Nilai Baru
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Line Charts - One per Parent Category -->
    <?php $__currentLoopData = $lineChartData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentNama => $chartData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> <?php echo e($parentNama); ?></h5>
            </div>
            <div class="card-body">
                <canvas id="lineChart<?php echo e($loop->index); ?>" height="100"></canvas>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <!-- Monthly Average Chart -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar-month"></i> Rata-rata Bulanan</h5>
                <small class="text-muted">Rata-rata nilai per bulan (12 bulan terakhir)</small>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Radar Chart -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-radar"></i> Penilaian Terkini</h5>
            </div>
            <div class="card-body" style="height: 300px;">
                <canvas id="radarChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Attendance Information -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Statistik Kehadiran</h5>
            </div>
            <div class="card-body" style="height: 300px;">
                <div class="row align-items-center mb-3">
                    <div class="col-6 text-center">
                        <h1 class="text-primary mb-1" style="font-size: 2.4rem; font-weight: 700;"><?php echo e($persenKehadiran); ?>%</h1>
                        <p class="text-muted mb-0">Tingkat Kehadiran</p>
                    </div>
                    <div class="col-6 text-center">
                        <div class="h4 mb-1 text-secondary" style="font-size:1.25rem;"><?php echo e($totalLatihan); ?></div>
                        <p class="text-muted mb-0">Total sesi latihan</p>
                    </div>
                </div>
                
                <div class="row g-2">
                    <div class="col-12 col-sm-6">
                        <div class="card border-success h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white" style="width:44px;height:44px;">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="h4 mb-0"><?php echo e($totalHadir); ?></div>
                                    <small class="text-muted">Hadir</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="card border-warning h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning text-dark" style="width:44px;height:44px;">
                                    <i class="bi bi-info-circle"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="h4 mb-0"><?php echo e($totalIzin); ?></div>
                                    <small class="text-muted">Izin</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="card border-info h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-info text-white" style="width:44px;height:44px;">
                                    <i class="bi bi-heart-pulse"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="h4 mb-0"><?php echo e($totalSakit); ?></div>
                                    <small class="text-muted">Sakit</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="card border-danger h-100">
                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-danger text-white" style="width:44px;height:44px;">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="flex-grow-1 text-start">
                                    <div class="h4 mb-0"><?php echo e($totalAlpa); ?></div>
                                    <small class="text-muted">Alpa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Riwayat Evaluasi</h5>
            </div>
            <div class="card-body">
                <?php if($evaluasi->count() > 0): ?>
                    <?php
                        $evaluasiPerMinggu = $evaluasi->groupBy('minggu')->sortByDesc(function($item, $key) {
                            return $key;
                        });
                        $parentKategoris = $allKategoris->whereNull('parent_id');
                    ?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="table-primary">
                                <tr>
                                    <th class="text-center align-middle" rowspan="2" style="width: 80px;">Minggu</th>
                                    <th class="text-center align-middle" rowspan="2" style="width: 100px;">Tanggal</th>
                                    
                                    <?php $__currentLoopData = $parentKategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $children = $allKategoris->where('parent_id', $parent->id);
                                        ?>
                                        <th colspan="<?php echo e($children->count()); ?>" class="text-center 
                                            <?php if($parent->nama === 'Teknik'): ?> bg-primary
                                            <?php elseif($parent->nama === 'Etika'): ?> bg-success
                                            <?php elseif($parent->minggu_terakhir): ?> bg-warning
                                            <?php else: ?> bg-info
                                            <?php endif; ?> text-white">
                                            <?php echo e($parent->nama); ?>

                                        </th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <th class="text-center align-middle" rowspan="2" style="width: 80px;">Rata-rata</th>
                                    <th class="align-middle" rowspan="2" style="width: 150px;">Catatan</th>
                                </tr>
                                <tr>
                                    <?php $__currentLoopData = $parentKategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $children = $allKategoris->where('parent_id', $parent->id);
                                        ?>
                                        <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th class="text-center" style="min-width: 70px; font-size: 11px;"><?php echo e($child->nama); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $evaluasiPerMinggu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $minggu => $evaluasiMinggu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-primary">M<?php echo e($minggu); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <small><?php echo e($evaluasiMinggu->first()->tanggal_evaluasi ? \Carbon\Carbon::parse($evaluasiMinggu->first()->tanggal_evaluasi)->format('d/m/Y') : '-'); ?></small>
                                    </td>
                                    
                                    <?php $__currentLoopData = $parentKategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $children = $allKategoris->where('parent_id', $parent->id);
                                        ?>
                                        <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $evalKategori = $evaluasiMinggu->firstWhere('kategori_penilaian_id', $child->id);
                                                $nilai = $evalKategori ? $evalKategori->nilai : null;
                                            ?>
                                            <td class="text-center">
                                                <?php if($nilai !== null): ?>
                                                    <span class="badge 
                                                        <?php if($nilai >= 80): ?> bg-success
                                                        <?php elseif($nilai >= 60): ?> bg-warning text-dark
                                                        <?php else: ?> bg-danger
                                                        <?php endif; ?>
                                                    " style="font-size: 11px; min-width: 35px;">
                                                        <?php echo e($nilai); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted" style="font-size: 11px;">-</span>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <td class="text-center">
                                        <?php
                                            $catatanPertama = $evaluasiMinggu->first()->catatan;
                                            $isTidakHadir = $catatanPertama && str_contains($catatanPertama, 'Tidak hadir');
                                            // Filter hanya nilai yang tidak null (nilai 0 dari ketidakhadiran tetap dihitung)
                                            $nilaiValid = $evaluasiMinggu->filter(function($eval) {
                                                return $eval->nilai !== null;
                                            });
                                            $rataRata = $nilaiValid->isNotEmpty() ? $nilaiValid->avg('nilai') : 0;
                                        ?>
                                        <?php if($isTidakHadir): ?>
                                            <span class="badge bg-danger" style="font-size: 11px;">
                                                <i class="bi bi-exclamation-triangle"></i> <?php echo e(number_format($rataRata, 1)); ?>

                                            </span>
                                        <?php else: ?>
                                            <strong class="text-info" style="font-size: 12px;"><?php echo e(number_format($rataRata, 1)); ?></strong>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $catatan = $evaluasiMinggu->first()->catatan ?: '-';
                                            $isTidakHadir = str_contains($catatan, 'Tidak hadir');
                                        ?>
                                        <?php if($isTidakHadir): ?>
                                            <span class="badge bg-danger" style="font-size: 11px;">
                                                <i class="bi bi-exclamation-triangle"></i> <?php echo e($catatan); ?>

                                            </span>
                                        <?php else: ?>
                                            <small style="font-size: 11px;"><?php echo e($catatan); ?></small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada data evaluasi</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-evaluasi')): ?>
                        <a href="<?php echo e(route('evaluasi.create', $siswa->id)); ?>" class="btn btn-primary btn-sm disabled" role="button" aria-disabled="true" tabindex="-1" onclick="event.preventDefault();">
                            <i class="bi bi-plus-circle"></i> Input Evaluasi Pertama
                        </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Histori Ketidakhadiran -->
<?php if($historiTidakHadir->count() > 0): ?>
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-calendar-x"></i> Histori Ketidakhadiran</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-secondary">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th class="text-center" style="width: 120px;">Tanggal</th>
                                <th class="text-center" style="width: 100px;">Sesi</th>
                                <th class="text-center" style="width: 100px;">Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $historiTidakHadir; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($index + 1); ?></td>
                                <td class="text-center">
                                    <small><?php echo e(\Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y')); ?></small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info"><?php echo e($absen->sesi); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge 
                                        <?php if($absen->status === 'Izin'): ?> bg-warning text-dark
                                        <?php elseif($absen->status === 'Sakit'): ?> bg-primary
                                        <?php else: ?> bg-danger
                                        <?php endif; ?>
                                    ">
                                        <?php if($absen->status === 'Izin'): ?>
                                            <i class="bi bi-info-circle"></i>
                                        <?php elseif($absen->status === 'Sakit'): ?>
                                            <i class="bi bi-heart-pulse"></i>
                                        <?php else: ?>
                                            <i class="bi bi-x-circle"></i>
                                        <?php endif; ?>
                                        <?php echo e($absen->status); ?>

                                    </span>
                                </td>
                                <td>
                                    <small><?php echo e($absen->keterangan ?: '-'); ?></small>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Floating Back Button (bottom-right) -->
<button type="button" class="btn btn-secondary position-fixed" style="right:20px; bottom:20px; z-index:1000;" onclick="history.back()" aria-label="Kembali">
    <i class="bi bi-arrow-left-circle"></i> Kembali
</button>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('vendor/chartjs/chart.min.js')); ?>"></script>
<script>
    // Multiple Line Charts - One per Parent Category
    const lineChartData = <?php echo json_encode($lineChartData); ?>;
    const weeks = <?php echo json_encode($weeks); ?>;
    const weekLabels = weeks.map(w => 'Minggu ' + w);
    
    let chartIndex = 0;
    for (const [parentNama, subData] of Object.entries(lineChartData)) {
        const canvasId = 'lineChart' + chartIndex;
        const canvas = document.getElementById(canvasId);
        
        if (canvas) {
            const ctx = canvas.getContext('2d');
            const datasets = [];
            let colorIndex = 0;
            
            for (const [subNama, values] of Object.entries(subData)) {
                datasets.push({
                    label: subNama,
                    data: values,
                    borderColor: getColor(colorIndex),
                    backgroundColor: getColor(colorIndex, 0.1),
                    tension: 0.4,
                    fill: true
                });
                colorIndex++;
            }
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: weekLabels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { 
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                font: { size: 10 }
                            }
                        },
                        title: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: { display: true, text: 'Nilai' }
                        }
                    }
                }
            });
        }
        
        chartIndex++;
    }

    // Monthly Average Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyLabels = <?php echo json_encode(array_values($monthlyData)); ?>;
    const monthlyValues = <?php echo json_encode(array_values($monthlyAverages)); ?>;
    
    console.log('=== MONTHLY CHART DEBUG ===');
    console.log('Monthly Labels:', monthlyLabels);
    console.log('Monthly Values:', monthlyValues);
    
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Rata-rata Bulanan',
                data: monthlyValues,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true,
                spanGaps: true // Connect lines even if there's null data
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { 
                    display: false
                },
                title: { 
                    display: false 
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: { 
                        display: true, 
                        text: 'Nilai' 
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        }
    });

    // Radar Chart - Latest Week
    const radarCtx = document.getElementById('radarChart').getContext('2d');
    const radarLabels = <?php echo json_encode(array_keys($radarChartData)); ?>;
    const radarData = <?php echo json_encode(array_values($radarChartData)); ?>;
    
    console.log('=== RADAR CHART DEBUG (SHOW) ===');
    console.log('Siswa Kelompok Umur:', '<?php echo e($siswa->kelompok_umur); ?>');
    console.log('Radar Labels:', radarLabels);
    console.log('Radar Values:', radarData);
    console.log('Full Radar Data:', <?php echo json_encode($radarChartData); ?>);
    console.log('Label Count:', radarLabels.length);
    
    const radarChart = new Chart(radarCtx, {
        type: 'radar',
        data: {
            labels: radarLabels,
            datasets: [{
                label: 'Nilai Terkini',
                data: radarData,
                fill: true,
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                borderColor: 'rgb(102, 126, 234)',
                pointBackgroundColor: 'rgb(102, 126, 234)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgb(102, 126, 234)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { stepSize: 20 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    function getColor(index, alpha = 1) {
        const colors = [
            `rgba(102, 126, 234, ${alpha})`,
            `rgba(255, 99, 132, ${alpha})`,
            `rgba(54, 162, 235, ${alpha})`,
            `rgba(255, 206, 86, ${alpha})`,
            `rgba(75, 192, 192, ${alpha})`,
        ];
        return colors[index % colors.length];
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/evaluasi/show.blade.php ENDPATH**/ ?>