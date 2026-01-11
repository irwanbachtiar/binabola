

<?php $__env->startSection('title', 'Manajemen User Orangtua'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Manajemen User Orangtua</h2>
                <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah User Orangtua
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Unit Kerja</th>
                                    <th>Siswa Terhubung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td>
                                            <?php if($user->unit_kerja): ?>
                                                <span class="text-muted"><?php echo e($user->unit_kerja); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($user->siswas->count() > 0): ?>
                                                <span class="badge bg-success"><?php echo e($user->siswas->count()); ?> siswa</span>
                                                <br>
                                                <small class="text-muted">
                                                    <?php echo e($user->siswas->pluck('nama')->join(', ')); ?>

                                                </small>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Belum ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada user orangtua</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <?php echo e($users->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/admin/users/index.blade.php ENDPATH**/ ?>