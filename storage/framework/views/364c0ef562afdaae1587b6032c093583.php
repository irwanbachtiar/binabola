

<?php $__env->startSection('title', 'Detail Siswa - ' . $siswa->nama); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo e(route('orangtua.dashboard')); ?>" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <?php if($siswa->foto): ?>
                                <img src="<?php echo e(asset('uploads/siswa/' . $siswa->foto)); ?>" 
                                     alt="<?php echo e($siswa->nama); ?>" 
                                     class="img-fluid rounded-circle mb-3" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 150px; height: 150px; font-size: 60px;">
                                    <?php echo e(strtoupper(substr($siswa->nama, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-9">
                            <h3><?php echo e($siswa->nama); ?></h3>
                            <span class="badge bg-<?php echo e($siswa->status == 'aktif' ? 'success' : 'secondary'); ?> mb-3">
                                <?php echo e(ucfirst($siswa->status)); ?>

                            </span>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Tanggal Lahir:</strong><br>
                                    <?php echo e($siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-'); ?>

                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Umur:</strong><br>
                                    <?php echo e($siswa->umur_detail); ?>

                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Email:</strong><br>
                                    <?php echo e($siswa->email ?? '-'); ?>

                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Telepon:</strong><br>
                                    <?php echo e($siswa->telepon ?? '-'); ?>

                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Tinggi Badan:</strong><br>
                                    <?php echo e($siswa->tinggi_badan ? $siswa->tinggi_badan . ' cm' : '-'); ?>

                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Berat Badan:</strong><br>
                                    <?php echo e($siswa->berat_badan ? $siswa->berat_badan . ' kg' : '-'); ?>

                                </div>
                                <div class="col-12 mb-3">
                                    <strong>Minat Posisi:</strong><br>
                                    <?php echo e($siswa->minat_posisi_string); ?>

                                </div>
                                <div class="col-12 mb-3">
                                    <strong>Alamat:</strong><br>
                                    <?php echo e($siswa->alamat ?? '-'); ?>

                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <a href="<?php echo e(route('orangtua.siswa.absensi', $siswa->id)); ?>" class="btn btn-primary">
                                    <i class="bi bi-calendar-check"></i> Lihat Absensi
                                </a>
                                <a href="<?php echo e(route('orangtua.siswa.evaluasi', $siswa->id)); ?>" class="btn btn-primary">
                                    <i class="bi bi-clipboard-data"></i> Lihat Evaluasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/orangtua/siswa-detail.blade.php ENDPATH**/ ?>