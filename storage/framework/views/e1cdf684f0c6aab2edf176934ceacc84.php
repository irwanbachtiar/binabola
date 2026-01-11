

<?php $__env->startSection('title', 'Laporan Summary Bulanan'); ?>

<?php $__env->startSection('page-title'); ?>
<span style="font-size: calc(1em - 2px);">Laporan Summary Bulanan</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('laporan.bulanan')); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Bulan</label>
                        <input type="month" class="form-control" name="bulan" value="<?php echo e($bulan); ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tampilkan
                        </button>
                    </div>
                    <div class="col-md-6 text-end">
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

<!-- Tabel Laporan U-7 -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Kelompok U-7 (Usia 3-7 Tahun)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center align-middle" rowspan="2" style="width: 50px;">Peringkat</th>
                                <th class="align-middle" rowspan="2">Nama Siswa</th>
                                
                                <?php
                                    $regularParentsU7 = $parentKategorisU7->where('minggu_terakhir', 0);
                                    $miniGameParentsU7 = $parentKategorisU7->where('minggu_terakhir', 1);
                                ?>
                                
                                <?php $__currentLoopData = $regularParentsU7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <th colspan="<?php echo e($children->count()); ?>" class="text-center 
                                        <?php if($parent->nama === 'Teknik'): ?> bg-primary
                                        <?php elseif($parent->nama === 'Etika'): ?> bg-success
                                        <?php else: ?> bg-info
                                        <?php endif; ?> text-white">
                                        <?php echo e($parent->nama); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <?php $__currentLoopData = $miniGameParentsU7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <th colspan="<?php echo e($children->count()); ?>" class="text-center bg-warning text-white">
                                        <?php echo e($parent->nama); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <th class="text-center align-middle" rowspan="2" style="width: 100px;">Rata-rata Total</th>
                            </tr>
                            <tr>
                                <?php $__currentLoopData = $regularParentsU7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center" style="min-width: 70px; font-size: 11px;"><?php echo e($child->nama); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <?php $__currentLoopData = $miniGameParentsU7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $laporanDataU7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center">
                                    <?php if($index == 0): ?>
                                        <span class="badge bg-warning text-dark fs-6">🥇 <?php echo e($index + 1); ?></span>
                                    <?php elseif($index == 1): ?>
                                        <span class="badge bg-secondary fs-6">🥈 <?php echo e($index + 1); ?></span>
                                    <?php elseif($index == 2): ?>
                                        <span class="badge bg-danger fs-6">🥉 <?php echo e($index + 1); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark"><?php echo e($index + 1); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($data['siswa']->nama); ?></strong>
                                </td>
                                
                                <?php $__currentLoopData = $regularParentsU7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center">
                                            <?php
                                                $nilai = $data['nilai_kategori'][$child->id] ?? '-';
                                            ?>
                                            <?php if($nilai !== '-'): ?>
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
                                
                                <?php $__currentLoopData = $miniGameParentsU7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center">
                                            <?php
                                                $nilai = $data['nilai_kategori'][$child->id] ?? '-';
                                            ?>
                                            <?php if($nilai !== '-'): ?>
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
                                
                                <td class="text-center bg-light">
                                    <?php if($data['total_rata'] > 0): ?>
                                        <strong class="text-dark" style="font-size: 12px;"><?php echo e($data['total_rata']); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <?php
                                    $totalCols = 2;
                                    $regularParentsU7 = $parentKategorisU7->where('minggu_terakhir', 0);
                                    $miniGameParentsU7 = $parentKategorisU7->where('minggu_terakhir', 1);
                                    foreach($regularParentsU7 as $p) {
                                        $totalCols += $allKategoris->where('parent_id', $p->id)->count();
                                    }
                                    foreach($miniGameParentsU7 as $p) {
                                        $totalCols += $allKategoris->where('parent_id', $p->id)->count();
                                    }
                                    $totalCols += 1; // rata-rata column
                                ?>
                                <td colspan="<?php echo e($totalCols); ?>" class="text-center text-muted">
                                    Tidak ada siswa U-7 dengan data evaluasi untuk bulan ini.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Laporan U-12 -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Kelompok U-12 (Usia 8-12 Tahun)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-success">
                            <tr>
                                <th class="text-center align-middle" rowspan="2" style="width: 50px;">Peringkat</th>
                                <th class="align-middle" rowspan="2">Nama Siswa</th>
                                
                                <?php
                                    $regularParentsU12 = $parentKategorisU12->where('minggu_terakhir', 0);
                                    $miniGameParentsU12 = $parentKategorisU12->where('minggu_terakhir', 1);
                                ?>
                                
                                <?php $__currentLoopData = $regularParentsU12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <th colspan="<?php echo e($children->count()); ?>" class="text-center 
                                        <?php if($parent->nama === 'Teknik'): ?> bg-primary
                                        <?php elseif($parent->nama === 'Etika'): ?> bg-success
                                        <?php else: ?> bg-info
                                        <?php endif; ?> text-white">
                                        <?php echo e($parent->nama); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <?php $__currentLoopData = $miniGameParentsU12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <th colspan="<?php echo e($children->count()); ?>" class="text-center bg-warning text-white">
                                        <?php echo e($parent->nama); ?>

                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <th class="text-center align-middle" rowspan="2" style="width: 100px;">Rata-rata Total</th>
                            </tr>
                            <tr>
                                <?php $__currentLoopData = $regularParentsU12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center" style="min-width: 70px; font-size: 11px;"><?php echo e($child->nama); ?></th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <?php $__currentLoopData = $miniGameParentsU12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $laporanDataU12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center">
                                    <?php if($index == 0): ?>
                                        <span class="badge bg-warning text-dark fs-6">🥇 <?php echo e($index + 1); ?></span>
                                    <?php elseif($index == 1): ?>
                                        <span class="badge bg-secondary fs-6">🥈 <?php echo e($index + 1); ?></span>
                                    <?php elseif($index == 2): ?>
                                        <span class="badge bg-danger fs-6">🥉 <?php echo e($index + 1); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark"><?php echo e($index + 1); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($data['siswa']->nama); ?></strong>
                                </td>
                                
                                <?php $__currentLoopData = $regularParentsU12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center">
                                            <?php
                                                $nilai = $data['nilai_kategori'][$child->id] ?? '-';
                                            ?>
                                            <?php if($nilai !== '-'): ?>
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
                                
                                <?php $__currentLoopData = $miniGameParentsU12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $children = $allKategoris->where('parent_id', $parent->id);
                                    ?>
                                    <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="text-center">
                                            <?php
                                                $nilai = $data['nilai_kategori'][$child->id] ?? '-';
                                            ?>
                                            <?php if($nilai !== '-'): ?>
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
                                
                                <td class="text-center bg-light">
                                    <?php if($data['total_rata'] > 0): ?>
                                        <strong class="text-dark" style="font-size: 12px;"><?php echo e($data['total_rata']); ?></strong>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <?php
                                    $totalCols = 2;
                                    $regularParentsU12 = $parentKategorisU12->where('minggu_terakhir', 0);
                                    $miniGameParentsU12 = $parentKategorisU12->where('minggu_terakhir', 1);
                                    foreach($regularParentsU12 as $p) {
                                        $totalCols += $allKategoris->where('parent_id', $p->id)->count();
                                    }
                                    foreach($miniGameParentsU12 as $p) {
                                        $totalCols += $allKategoris->where('parent_id', $p->id)->count();
                                    }
                                    $totalCols += 1; // rata-rata column
                                ?>
                                    $totalCols += 1; // rata-rata column
                                @endphp
                                <td colspan="<?php echo e($totalCols); ?>" class="text-center text-muted">
                                    Tidak ada siswa U-12 dengan data evaluasi untuk bulan ini.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keterangan -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6>Keterangan:</h6>
                <ul class="small text-muted">
                    <li>Nilai rata-rata dihitung dari semua evaluasi dalam bulan yang dipilih</li>
                    <li>Kategori dengan minggu terakhir (Mini Game) tidak dihitung dalam rata-rata</li>
                    <li>Siswa dikelompokkan berdasarkan kelompok umur dan diurutkan berdasarkan rata-rata total tertinggi</li>
                    <li>Warna Badge:
                        <span class="badge bg-success">≥ 80</span>
                        <span class="badge bg-warning text-dark">60-79</span>
                        <span class="badge bg-danger">< 60</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/laporan/bulanan.blade.php ENDPATH**/ ?>