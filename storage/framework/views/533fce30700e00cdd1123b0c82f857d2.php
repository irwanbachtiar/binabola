

<?php $__env->startSection('title', 'Laporan Pembayaran Iuran'); ?>

<?php $__env->startSection('page-title', 'Laporan Pembayaran Iuran'); ?>

<?php $__env->startSection('content'); ?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-file-text"></i> Filter Laporan</h5>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('pembayaran.laporan')); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select">
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php echo e($bulan == $i ? 'selected' : ''); ?>>
                                    <?php echo e(DateTime::createFromFormat('!m', $i)->format('F')); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select">
                            <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tampilkan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Summary -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-cash-stack text-success" style="font-size: 2rem;"></i>
                <h5 class="mt-2 mb-0">Rp <?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></h5>
                <small class="text-muted">Total Pemasukan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-receipt text-primary" style="font-size: 2rem;"></i>
                <h5 class="mt-2 mb-0"><?php echo e($jumlahTransaksi); ?></h5>
                <small class="text-muted">Total Transaksi</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-calculator text-info" style="font-size: 2rem;"></i>
                <h5 class="mt-2 mb-0">Rp <?php echo e($jumlahTransaksi > 0 ? number_format($totalPemasukan / $jumlahTransaksi, 0, ',', '.') : '0'); ?></h5>
                <small class="text-muted">Rata-rata per Transaksi</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <i class="bi bi-calendar-month text-warning" style="font-size: 2rem;"></i>
                <h5 class="mt-2 mb-0"><?php echo e(DateTime::createFromFormat('!m', $bulan)->format('F')); ?> <?php echo e($tahun); ?></h5>
                <small class="text-muted">Periode Laporan</small>
            </div>
        </div>
    </div>
</div>

<!-- Breakdown per Metode Pembayaran -->
<?php if($byMetode->count() > 0): ?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Breakdown per Metode Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $byMetode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metode => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-<?php echo e($metode == 'cash' ? 'success' : ($metode == 'transfer' ? 'primary' : ($metode == 'qris' ? 'info' : 'secondary'))); ?>">
                                        <?php echo e(strtoupper($metode)); ?>

                                    </span>
                                    <small class="text-muted"><?php echo e($data['jumlah']); ?> transaksi</small>
                                </div>
                                <h5 class="mb-0">Rp <?php echo e(number_format($data['total'], 0, ',', '.')); ?></h5>
                                <small class="text-muted"><?php echo e(round(($data['total'] / $totalPemasukan) * 100, 1)); ?>% dari total</small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Tabel Detail Pembayaran -->
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-table"></i> Detail Transaksi</h5>
                <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-printer"></i> Cetak
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Tanggal Bayar</th>
                                <th>Nama Siswa</th>
                                <th>Kelompok</th>
                                <th>Paket Iuran</th>
                                <th class="text-end">Nominal</th>
                                <th>Metode</th>
                                <th class="text-center">Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $pembayarans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e(\Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y')); ?></td>
                                    <td>
                                        <strong><?php echo e($p->siswa->nama); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($p->siswa->kelompok_umur == 'U-7' ? 'info' : 'primary'); ?>">
                                            <?php echo e($p->siswa->kelompok_umur); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($p->paket): ?>
                                            <?php echo e($p->paket->nama_paket); ?>

                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp <?php echo e(number_format($p->nominal, 0, ',', '.')); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($p->metode_pembayaran == 'cash' ? 'success' : ($p->metode_pembayaran == 'transfer' ? 'primary' : ($p->metode_pembayaran == 'qris' ? 'info' : 'secondary'))); ?>">
                                            <?php echo e(strtoupper($p->metode_pembayaran)); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><?php echo e(strtoupper($p->status)); ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted"><?php echo e($p->catatan ?: '-'); ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-0">Tidak ada data pembayaran untuk periode ini</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <?php if($pembayarans->count() > 0): ?>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">Total:</th>
                                <th class="text-end">Rp <?php echo e(number_format($totalPemasukan, 0, ',', '.')); ?></th>
                                <th colspan="3"></th>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <a href="<?php echo e(route('pembayaran.index')); ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @media print {
        .sidebar, .card-header button, .card-footer, .btn-toolbar {
            display: none !important;
        }
        .col-md-9 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/pembayaran/laporan.blade.php ENDPATH**/ ?>