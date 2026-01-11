

<?php $__env->startSection('title', 'Master Paket Iuran'); ?>

<?php $__env->startSection('page-title', 'Master Paket Iuran'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-box-seam"></i> Daftar Paket Iuran</h5>
        <a href="<?php echo e(route('paket-iuran.create')); ?>" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Paket
        </a>
    </div>
    <div class="card-body">
        <?php if($pakets->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama Paket</th>
                            <th>Kelompok Umur</th>
                            <th>Nominal</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td>
                                <strong><?php echo e($paket->nama_paket); ?></strong>
                                <?php if($paket->keterangan): ?>
                                    <br><small class="text-muted"><?php echo e($paket->keterangan); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo e($paket->kelompok_umur === 'U-7' ? 'bg-primary' : 'bg-success'); ?>">
                                    <?php echo e($paket->kelompok_umur); ?>

                                </span>
                            </td>
                            <td><strong><?php echo e($paket->nominal_format); ?></strong></td>
                            <td><?php echo e($paket->durasi_bulan); ?> bulan</td>
                            <td>
                                <?php if($paket->aktif): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?php echo e(route('paket-iuran.edit', $paket->id)); ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('paket-iuran.destroy', $paket->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus paket ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted">Belum ada paket iuran. Silakan tambah paket baru.</p>
                <a href="<?php echo e(route('paket-iuran.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Paket
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-3">
    <a href="<?php echo e(route('pembayaran.index')); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Pembayaran
    </a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/paket-iuran/index.blade.php ENDPATH**/ ?>