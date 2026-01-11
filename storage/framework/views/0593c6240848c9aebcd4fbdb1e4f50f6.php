<?php $__env->startSection('title', 'Data Siswa - Sekolah Sepak Bola'); ?>

<?php $__env->startSection('page-title', 'Data Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0"><i class="bi bi-people-fill"></i> Daftar Siswa</h5>
        <a href="<?php echo e(route('siswa.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Siswa
        </a>
    </div>
    
    <!-- Filter Kelompok Umur -->
    <div class="card-body border-bottom">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link <?php echo e($kelompok === 'semua' ? 'active' : ''); ?>" 
                   href="<?php echo e(route('siswa.index', ['kelompok' => 'semua'])); ?>">
                    <i class="bi bi-list"></i> Semua Siswa
                    <span class="badge bg-secondary ms-1"><?php echo e(\App\Models\Siswa::count()); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e($kelompok === 'u7' ? 'active' : ''); ?>" 
                   href="<?php echo e(route('siswa.index', ['kelompok' => 'u7'])); ?>">
                    <i class="bi bi-people"></i> Kelompok U-7 (3-7 tahun)
                    <span class="badge bg-info ms-1">
                        <?php echo e(\App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 3 AND 7')->count()); ?>

                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo e($kelompok === 'u12' ? 'active' : ''); ?>" 
                   href="<?php echo e(route('siswa.index', ['kelompok' => 'u12'])); ?>">
                    <i class="bi bi-people"></i> Kelompok U-12 (8-12 tahun)
                    <span class="badge bg-success ms-1">
                        <?php echo e(\App\Models\Siswa::whereRaw('TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 8 AND 12')->count()); ?>

                    </span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="card-body">
        <?php if($siswas->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="8%">Foto</th>
                            <th width="20%">Nama</th>
                            <th width="12%">Tanggal Lahir</th>
                            <th width="10%">Umur</th>
                            <th width="10%">Kelompok</th>
                            <th width="15%">Minat Posisi</th>
                            <th width="8%">Status</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td>
                                <?php if($siswa->foto): ?>
                                    <img src="<?php echo e(asset($siswa->foto)); ?>" 
                                         alt="Foto <?php echo e($siswa->nama); ?>" 
                                         class="rounded-circle" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($siswa->nama); ?></strong>
                                <?php if($siswa->telepon): ?>
                                    <br><small class="text-muted"><i class="bi bi-phone"></i> <?php echo e($siswa->telepon); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($siswa->tanggal_lahir->format('d/m/Y')); ?></td>
                            <td>
                                <span class="badge bg-primary"><?php echo e($siswa->umur_detail); ?></span>
                            </td>
                            <td>
                                <?php if($siswa->kelompok_umur === 'U-7'): ?>
                                    <span class="badge bg-info"><?php echo e($siswa->kelompok_umur); ?></span>
                                <?php elseif($siswa->kelompok_umur === 'U-12'): ?>
                                    <span class="badge bg-success"><?php echo e($siswa->kelompok_umur); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($siswa->minat_posisi && count($siswa->minat_posisi) > 0): ?>
                                    <?php $__currentLoopData = $siswa->minat_posisi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $posisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-info me-1 mb-1"><?php echo e($posisi); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($siswa->status == 'Aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('siswa.edit', $siswa->id)); ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?php echo e(route('siswa.destroy', $siswa->id)); ?>" 
                                          method="POST" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                title="Hapus">
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
                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                <p class="text-muted mt-3">Belum ada data siswa</p>
                <a href="<?php echo e(route('siswa.create')); ?>" class="btn btn-primary mt-2">
                    <i class="bi bi-plus-circle"></i> Tambah Siswa Pertama
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/siswa/index.blade.php ENDPATH**/ ?>