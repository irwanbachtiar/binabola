

<?php $__env->startSection('title', 'Monitoring Pembayaran - ' . $siswa->nama); ?>

<?php $__env->startSection('page-title'); ?>
    <div class="d-flex align-items-center">
        <a href="<?php echo e(route('orangtua.dashboard')); ?>" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <span>Monitoring Pembayaran</span>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Student Info -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <?php if($siswa->foto && file_exists(public_path($siswa->foto))): ?>
                        <img src="<?php echo e(asset($siswa->foto)); ?>" alt="<?php echo e($siswa->nama); ?>" 
                             class="rounded-circle" width="80" height="80" 
                             style="object-fit: cover; border: 3px solid #667eea;">
                    <?php else: ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 3px solid #667eea;">
                            <span class="text-white" style="font-size: 32px; font-weight: bold;">
                                <?php echo e(strtoupper(substr($siswa->nama, 0, 1))); ?>

                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col">
                    <h4 class="mb-1"><?php echo e($siswa->nama); ?></h4>
                    <p class="text-muted mb-0">
                        <span class="badge bg-<?php echo e($siswa->kelompok_umur == 'U-7' ? 'info' : 'primary'); ?> me-2">
                            <?php echo e($siswa->kelompok_umur); ?>

                        </span>
                        <?php if($siswa->paketIuran): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-credit-card"></i> <?php echo e($siswa->paketIuran->nama_paket); ?>

                            </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Pembayaran Bulan Ini -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-calendar-check text-<?php echo e($statusBulanIni ? 'success' : 'warning'); ?>" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Status Bulan Ini</h6>
                    <h5 class="mb-0">
                        <span class="badge bg-<?php echo e($statusBulanIni ? 'success' : 'warning'); ?>">
                            <?php echo e($statusBulanIni ? 'Sudah Bayar' : 'Belum Bayar'); ?>

                        </span>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-cash-stack text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Bayar <?php echo e($tahunIni); ?></h6>
                    <h5 class="mb-0 text-primary">Rp <?php echo e(number_format($totalBayarTahunIni, 0, ',', '.')); ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-calendar2-check text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Bulan Terbayar</h6>
                    <h5 class="mb-0 text-success"><?php echo e($jumlahBulanBayar); ?>/12 Bulan</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-2">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="text-muted mb-1">Tunggakan</h6>
                    <h5 class="mb-0 text-danger"><?php echo e($tagihanBelumBayar->count()); ?> Bulan</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Tagihan Belum Bayar -->
    <?php if($tagihanBelumBayar->count() > 0): ?>
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-exclamation-circle"></i> Tagihan Belum Bayar</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Paket</th>
                            <th>Nominal</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tagihanBelumBayar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tagihan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e($tagihan->periode); ?></strong>
                            </td>
                            <td><?php echo e($tagihan->paket->nama_paket ?? '-'); ?></td>
                            <td class="fw-bold text-danger"><?php echo e($tagihan->nominal_format); ?></td>
                            <td>
                                <?php echo e(\Carbon\Carbon::parse($tagihan->jatuh_tempo)->format('d M Y')); ?>

                                <?php if($tagihan->terlambat): ?>
                                    <span class="badge bg-danger ms-1">Terlambat</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-warning"><?php echo e(ucfirst(str_replace('_', ' ', $tagihan->status))); ?></span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('orangtua.siswa.pelunasan', $siswa->id)); ?>?tagihan_id=<?php echo e($tagihan->id); ?>" 
                                   class="btn btn-sm btn-primary">
                                    <i class="bi bi-credit-card"></i> Bayar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-success border-0 mb-4">
        <i class="bi bi-check-circle"></i> <strong>Tidak ada tunggakan!</strong> Semua tagihan telah dibayarkan.
    </div>
    <?php endif; ?>

    <!-- History Pembayaran -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Pembayaran</h5>
        </div>
        <div class="card-body">
            <?php if($historyPembayaran->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <th>Paket</th>
                            <th>Tanggal Bayar</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $historyPembayaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pembayaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($pembayaran->periode); ?></strong></td>
                            <td><?php echo e($pembayaran->paket->nama_paket ?? '-'); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y')); ?></td>
                            <td class="fw-bold text-success"><?php echo e($pembayaran->nominal_format); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo e(ucfirst($pembayaran->metode_pembayaran)); ?></span>
                            </td>
                            <td>
                                <?php if($pembayaran->status == 'lunas'): ?>
                                    <span class="badge bg-success">Lunas</span>
                                <?php elseif($pembayaran->status == 'pending'): ?>
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($pembayaran->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($pembayaran->bukti_pembayaran): ?>
                                    <a href="<?php echo e(asset('storage/' . $pembayaran->bukti_pembayaran)); ?>" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-image"></i> Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                <p class="mt-2">Belum ada riwayat pembayaran</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/orangtua/pembayaran.blade.php ENDPATH**/ ?>