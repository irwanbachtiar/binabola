<?php $__env->startSection('title', 'Evaluasi Siswa - Sekolah Sepak Bola'); ?>

<?php $__env->startSection('page-title', 'Penilaian & Evaluasi Siswa'); ?>

<?php $__env->startSection('content'); ?>


<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Pilih Siswa untuk Evaluasi</h5>
    </div>
    <div class="card-body">
        <!-- Kelompok U-12 -->
        <div class="mb-5">
            <div class="d-flex align-items-center mb-3">
                <h5 class="mb-0 me-2">
                    <i class="bi bi-people-fill text-success"></i> Kelompok Umur U-12 (8-12 tahun)
                </h5>
                <span class="badge bg-success"><?php echo e($siswaU12->count()); ?> Siswa</span>
            </div>
            
            <?php if($siswaU12->count() > 0): ?>
                <div class="list-group">
                    <?php $__currentLoopData = $siswaU12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <?php if($siswa->foto): ?>
                                <img src="<?php echo e(asset($siswa->foto)); ?>" 
                                     alt="Foto <?php echo e($siswa->nama); ?>" 
                                     class="rounded-circle me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-person fs-4 text-white"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo e($siswa->nama); ?></h6>
                                <p class="mb-1 text-muted small">
                                    <i class="bi bi-calendar3"></i> <?php echo e($siswa->umur_detail); ?>

                                    <span class="badge bg-success ms-2"><?php echo e($siswa->kelompok_umur); ?></span>
                                    <?php if($siswa->minat_posisi && count($siswa->minat_posisi) > 0): ?>
                                        <span class="ms-2">
                                            <i class="bi bi-trophy"></i>
                                            <?php $__currentLoopData = $siswa->minat_posisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $posisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-info me-1"><?php echo e($posisi); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="btn-group" role="group">
                                
                                <a href="<?php echo e(route('evaluasi.show', $siswa->id)); ?>" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-graph-up"></i> Lihat Progress
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada siswa aktif di kelompok U-12
                </div>
            <?php endif; ?>
        </div>

        <!-- Kelompok U-7 -->
        <div>
            <div class="d-flex align-items-center mb-3">
                <h5 class="mb-0 me-2">
                    <i class="bi bi-people-fill text-info"></i> Kelompok Umur U-7 (3-7 tahun)
                </h5>
                <span class="badge bg-info"><?php echo e($siswaU7->count()); ?> Siswa</span>
            </div>
            
            <?php if($siswaU7->count() > 0): ?>
                <div class="list-group">
                    <?php $__currentLoopData = $siswaU7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex align-items-center">
                            <?php if($siswa->foto): ?>
                                <img src="<?php echo e(asset($siswa->foto)); ?>" 
                                     alt="Foto <?php echo e($siswa->nama); ?>" 
                                     class="rounded-circle me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="bi bi-person fs-4 text-white"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo e($siswa->nama); ?></h6>
                                <p class="mb-1 text-muted small">
                                    <i class="bi bi-calendar3"></i> <?php echo e($siswa->umur_detail); ?>

                                    <span class="badge bg-info ms-2"><?php echo e($siswa->kelompok_umur); ?></span>
                                    <?php if($siswa->minat_posisi && count($siswa->minat_posisi) > 0): ?>
                                        <span class="ms-2">
                                            <i class="bi bi-trophy"></i>
                                            <?php $__currentLoopData = $siswa->minat_posisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $posisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-info me-1"><?php echo e($posisi); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="btn-group" role="group">
                                
                                <a href="<?php echo e(route('evaluasi.show', $siswa->id)); ?>" 
                                   class="btn btn-sm btn-success">
                                    <i class="bi bi-graph-up"></i> Lihat Progress
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada siswa aktif di kelompok U-7
                </div>
            <?php endif; ?>
        </div>
        
        <?php if($siswaU12->count() === 0 && $siswaU7->count() === 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Belum ada siswa aktif</p>
                <a href="<?php echo e(route('siswa.create')); ?>" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Tambah Siswa
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/evaluasi/index.blade.php ENDPATH**/ ?>