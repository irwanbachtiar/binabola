

<?php $__env->startSection('title', 'Pilih Siswa'); ?>
<?php $__env->startSection('page-title', 'Pilih Siswa untuk Evaluasi'); ?>

<?php $__env->startSection('content'); ?>
<!-- Search Box -->
<div class="card mb-3">
    <div class="card-body">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama siswa...">
        </div>
    </div>
</div>

<!-- Siswa List -->
<div id="siswaList">
    <?php $__empty_1 = true; $__currentLoopData = $siswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <a href="<?php echo e(route('evaluasi.create', $siswa->id)); ?>" class="siswa-card">
        <?php if($siswa->foto): ?>
            <img src="<?php echo e(asset($siswa->foto)); ?>" alt="<?php echo e($siswa->nama); ?>">
        <?php else: ?>
            <div class="placeholder-img">
                <i class="bi bi-person fs-3 text-white"></i>
            </div>
        <?php endif; ?>
        <div class="siswa-info flex-grow-1">
            <h6><?php echo e($siswa->nama); ?></h6>
            <small><i class="bi bi-calendar"></i> <?php echo e($siswa->umur); ?> tahun</small><br>
            <small><i class="bi bi-trophy"></i> <?php echo e($siswa->minat_posisi_string); ?></small>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-5">
        <i class="bi bi-people fs-1 text-muted"></i>
        <p class="text-muted mt-3">Belum ada siswa aktif</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const siswaCards = document.querySelectorAll('.siswa-card');
        
        siswaCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? 'flex' : 'none';
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/evaluasi/index-mobile.blade.php ENDPATH**/ ?>