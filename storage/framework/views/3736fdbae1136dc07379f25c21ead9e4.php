

<?php $__env->startSection('title', 'Input Pembayaran'); ?>

<?php $__env->startSection('page-title', 'Input Pembayaran Iuran'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-cash-coin"></i> Form Pembayaran Iuran</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('pembayaran.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="siswa_id" class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                        <select name="siswa_id" id="siswa_id" class="form-select <?php $__errorArgs = ['siswa_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s->id); ?>" <?php echo e(old('siswa_id', $siswa?->id) == $s->id ? 'selected' : ''); ?>>
                                    <?php echo e($s->nama); ?> - <?php echo e($s->kelompok_umur); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['siswa_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <?php if($siswa && $tagihanBelumBayar && $tagihanBelumBayar->count() > 0): ?>
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Tagihan Belum Dibayar:</h6>
                        <ul class="mb-0">
                            <?php $__currentLoopData = $tagihanBelumBayar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tagihan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($tagihan->periode); ?> - <?php echo e($tagihan->nominal_format); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="paket_iuran_id" class="form-label">Paket Iuran</label>
                        <select name="paket_iuran_id" id="paket_iuran_id" class="form-select <?php $__errorArgs = ['paket_iuran_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Tanpa Paket --</option>
                            <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($paket->id); ?>" 
                                        data-nominal="<?php echo e($paket->nominal); ?>" 
                                        <?php echo e(old('paket_iuran_id', $siswa?->paket_iuran_id) == $paket->id ? 'selected' : ''); ?>>
                                    <?php echo e($paket->nama_paket); ?> (<?php echo e($paket->kelompok_umur); ?>) - <?php echo e($paket->nominal_format); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['paket_iuran_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="periode_bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                            <select name="periode_bulan" id="periode_bulan" class="form-select <?php $__errorArgs = ['periode_bulan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(old('periode_bulan', date('n')) == $i ? 'selected' : ''); ?>>
                                        <?php echo e(DateTime::createFromFormat('!m', $i)->format('F')); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                            <?php $__errorArgs = ['periode_bulan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="periode_tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                            <select name="periode_tahun" id="periode_tahun" class="form-select <?php $__errorArgs = ['periode_tahun'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <?php for($y = date('Y'); $y >= 2020; $y--): ?>
                                    <option value="<?php echo e($y); ?>" <?php echo e(old('periode_tahun', date('Y')) == $y ? 'selected' : ''); ?>>
                                        <?php echo e($y); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                            <?php $__errorArgs = ['periode_tahun'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_bayar" class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control <?php $__errorArgs = ['tanggal_bayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('tanggal_bayar', date('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['tanggal_bayar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nominal" class="form-label">Nominal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" id="nominal" class="form-control <?php $__errorArgs = ['nominal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('nominal', $siswa?->paketIuran?->nominal)); ?>" required min="0" step="1000">
                            </div>
                            <?php $__errorArgs = ['nominal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select <?php $__errorArgs = ['metode_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="cash" <?php echo e(old('metode_pembayaran') == 'cash' ? 'selected' : ''); ?>>Cash</option>
                            <option value="transfer" <?php echo e(old('metode_pembayaran') == 'transfer' ? 'selected' : ''); ?>>Transfer Bank</option>
                            <option value="qris" <?php echo e(old('metode_pembayaran') == 'qris' ? 'selected' : ''); ?>>QRIS</option>
                            <option value="lainnya" <?php echo e(old('metode_pembayaran') == 'lainnya' ? 'selected' : ''); ?>>Lainnya</option>
                        </select>
                        <?php $__errorArgs = ['metode_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran (Upload Foto/Scan)</label>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="form-control <?php $__errorArgs = ['bukti_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                        <?php $__errorArgs = ['bukti_pembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"><?php echo e(old('catatan')); ?></textarea>
                        <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('pembayaran.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto-fill paket dan nominal ketika siswa dipilih (redirect)
    document.getElementById('siswa_id').addEventListener('change', function() {
        if (this.value) {
            window.location.href = '<?php echo e(route("pembayaran.create")); ?>?siswa_id=' + this.value;
        }
    });
    
    // Auto-fill nominal ketika paket dipilih secara manual
    document.getElementById('paket_iuran_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const nominal = selectedOption.getAttribute('data-nominal');
        if (nominal) {
            document.getElementById('nominal').value = nominal;
        } else {
            document.getElementById('nominal').value = '';
        }
    });
    
    // Auto-fill nominal saat page load jika paket sudah terpilih
    window.addEventListener('DOMContentLoaded', function() {
        const paketSelect = document.getElementById('paket_iuran_id');
        if (paketSelect.value) {
            const selectedOption = paketSelect.options[paketSelect.selectedIndex];
            const nominal = selectedOption.getAttribute('data-nominal');
            if (nominal && !document.getElementById('nominal').value) {
                document.getElementById('nominal').value = nominal;
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/pembayaran/create.blade.php ENDPATH**/ ?>