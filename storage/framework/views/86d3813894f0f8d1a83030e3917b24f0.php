

<?php $__env->startSection('title', 'Hari Libur'); ?>

<?php $__env->startSection('page-title', 'Master Hari Libur'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <h2><i class="bi bi-calendar-x"></i> Master Hari Libur</h2>
        <a href="<?php echo e(route('hari-libur.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Hari Libur
        </a>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Daftar Hari Libur</h5>
    </div>
    <div class="card-body">
        <?php if($hariLibur->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th width="100" class="text-center">Status</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $hariLibur; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $libur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($libur->tanggal->format('d/m/Y')); ?></td>
                            <td><?php echo e($libur->keterangan); ?></td>
                            <td>
                                <span class="badge 
                                    <?php if($libur->jenis === 'Nasional'): ?> bg-danger
                                    <?php elseif($libur->jenis === 'Keagamaan'): ?> bg-success
                                    <?php elseif($libur->jenis === 'Sekolah'): ?> bg-primary
                                    <?php else: ?> bg-secondary
                                    <?php endif; ?>
                                ">
                                    <?php echo e($libur->jenis); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <?php if($libur->aktif): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo e(route('hari-libur.edit', $libur->id)); ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?php echo e(route('hari-libur.destroy', $libur->id)); ?>" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus hari libur ini?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-muted py-4">Belum ada data hari libur</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/hari-libur/index.blade.php ENDPATH**/ ?>