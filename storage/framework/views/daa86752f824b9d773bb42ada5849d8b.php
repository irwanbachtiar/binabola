<?php $__env->startSection('title', 'Edit Siswa - Sekolah Sepak Bola'); ?>

<?php $__env->startSection('page-title', 'Edit Data Siswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Form Edit Siswa</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('siswa.update', $siswa->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Nama -->
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nama" name="nama" value="<?php echo e(old('nama', $siswa->nama)); ?>" required>
                                <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $siswa->tanggal_lahir->format('Y-m-d'))); ?>" required>
                                <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Tanggal Masuk -->
                            <div class="mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk / Daftar <span class="text-danger">*</span></label>
                                <input type="date" class="form-control <?php $__errorArgs = ['tanggal_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tanggal_masuk" name="tanggal_masuk" value="<?php echo e(old('tanggal_masuk', $siswa->tanggal_masuk ? $siswa->tanggal_masuk->format('Y-m-d') : '')); ?>" required>
                                <small class="text-muted">Tanggal siswa mulai bergabung di sekolah sepak bola</small>
                                <?php $__errorArgs = ['tanggal_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Telepon -->
                            <div class="mb-3">
                                <label for="telepon" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="telepon" name="telepon" value="<?php echo e(old('telepon', $siswa->telepon)); ?>">
                                <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email', $siswa->email)); ?>">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Foto -->
                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Siswa</label>
                                <?php if($siswa->foto): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo e(asset($siswa->foto)); ?>" alt="Foto" class="img-thumbnail" style="max-width: 150px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="foto" name="foto" accept="image/*">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                                <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Tinggi & Berat -->
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="tinggi_badan" class="form-label">Tinggi (cm)</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['tinggi_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tinggi_badan" name="tinggi_badan" value="<?php echo e(old('tinggi_badan', $siswa->tinggi_badan)); ?>">
                                    <?php $__errorArgs = ['tinggi_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="berat_badan" class="form-label">Berat (kg)</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['berat_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="berat_badan" name="berat_badan" value="<?php echo e(old('berat_badan', $siswa->berat_badan)); ?>">
                                    <?php $__errorArgs = ['berat_badan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                                    <option value="Aktif" <?php echo e(old('status', $siswa->status) == 'Aktif' ? 'selected' : ''); ?>>Aktif</option>
                                    <option value="Non-Aktif" <?php echo e(old('status', $siswa->status) == 'Non-Aktif' ? 'selected' : ''); ?>>Non-Aktif</option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Paket Iuran -->
                            <div class="mb-3">
                                <label for="paket_iuran_id" class="form-label">
                                    <i class="bi bi-cash-coin"></i> Paket Iuran Bulanan
                                </label>
                                <select class="form-select <?php $__errorArgs = ['paket_iuran_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="paket_iuran_id" 
                                        name="paket_iuran_id">
                                    <option value="">-- Pilih Paket Iuran (Opsional) --</option>
                                    <?php $__currentLoopData = $pakets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($paket->id); ?>" 
                                                data-nominal="<?php echo e($paket->nominal); ?>"
                                                <?php echo e(old('paket_iuran_id', $siswa->paket_iuran_id) == $paket->id ? 'selected' : ''); ?>>
                                            <?php echo e($paket->nama_paket); ?> (<?php echo e($paket->kelompok_umur); ?>) - <?php echo e($paket->nominal_format); ?>/<?php echo e($paket->durasi_bulan); ?> bulan
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-muted">Paket iuran bulanan yang akan dibayarkan oleh siswa</small>
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
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="alamat" name="alamat" rows="2"><?php echo e(old('alamat', $siswa->alamat)); ?></textarea>
                        <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Minat Posisi -->
                    <div class="mb-3">
                        <label class="form-label">Minat Posisi <span class="text-danger">*</span></label>
                        <small class="text-muted d-block mb-2">Pilih satu atau lebih posisi</small>
                        <?php
                            $posisiList = ['Kiper', 'Belakang', 'Tengah', 'Striker'];
                            $selectedPosisi = old('minat_posisi', $siswa->minat_posisi ?? []);
                        ?>
                        <div class="border rounded p-3 <?php $__errorArgs = ['minat_posisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <div class="row">
                                <?php $__currentLoopData = $posisiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $posisi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="minat_posisi[]" value="<?php echo e($posisi); ?>" id="posisi_<?php echo e($loop->index); ?>" <?php echo e(in_array($posisi, $selectedPosisi) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="posisi_<?php echo e($loop->index); ?>"><?php echo e($posisi); ?></label>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php $__errorArgs = ['minat_posisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Akun Orangtua -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-person-badge"></i> Hubungkan dengan Akun Orangtua
                        </label>
                        <small class="text-muted d-block mb-2">Cari dan pilih akun orangtua yang akan terhubung dengan siswa ini</small>
                        
                        <?php if($orangtuaUsers->isEmpty()): ?>
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i> Belum ada akun orangtua. 
                                <a href="<?php echo e(route('admin.users.create')); ?>" target="_blank">Buat akun orangtua</a> terlebih dahulu.
                            </div>
                        <?php else: ?>
                            <!-- Search Input -->
                            <div class="mb-2">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="searchOrangtua" 
                                           placeholder="Cari nama atau email orangtua...">
                                </div>
                            </div>

                            <!-- Selected Items -->
                            <div id="selectedOrangtua" class="mb-2" style="min-height: 40px;">
                                <!-- Selected items will appear here -->
                            </div>

                            <!-- Dropdown List -->
                            <div class="border rounded" style="max-height: 300px; overflow-y: auto;">
                                <div id="orangtuaList">
                                    <?php $__currentLoopData = $orangtuaUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orangtua): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="orangtua-item p-2 border-bottom" 
                                         data-id="<?php echo e($orangtua->id); ?>" 
                                         data-name="<?php echo e($orangtua->name); ?>" 
                                         data-email="<?php echo e($orangtua->email); ?>"
                                         style="cursor: pointer;">
                                        <div class="form-check">
                                            <input class="form-check-input orangtua-checkbox" 
                                                   type="checkbox" 
                                                   name="orangtua_ids[]" 
                                                   value="<?php echo e($orangtua->id); ?>" 
                                                   id="orangtua_<?php echo e($orangtua->id); ?>"
                                                   <?php echo e(in_array($orangtua->id, old('orangtua_ids', $assignedOrangtuaIds)) ? 'checked' : ''); ?>>
                                            <label class="form-check-label w-100" for="orangtua_<?php echo e($orangtua->id); ?>" style="cursor: pointer;">
                                                <strong><?php echo e($orangtua->name); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo e($orangtua->email); ?></small>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div id="noResults" class="text-center text-muted p-3" style="display: none;">
                                    <i class="bi bi-search"></i> Tidak ada hasil ditemukan
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo e(route('siswa.index')); ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .orangtua-item:hover {
        background-color: #f8f9fa;
    }
    .orangtua-item.selected {
        background-color: #e7f3ff;
    }
    .selected-badge {
        display: inline-flex;
        align-items: center;
        background-color: #0d6efd;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        margin: 3px;
        font-size: 14px;
    }
    .selected-badge .remove-btn {
        margin-left: 8px;
        cursor: pointer;
        font-weight: bold;
        opacity: 0.8;
    }
    .selected-badge .remove-btn:hover {
        opacity: 1;
    }
    #selectedOrangtua:empty::before {
        content: 'Belum ada orangtua yang dipilih';
        color: #6c757d;
        font-style: italic;
        font-size: 14px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchOrangtua');
    const orangtuaList = document.getElementById('orangtuaList');
    const selectedContainer = document.getElementById('selectedOrangtua');
    const noResults = document.getElementById('noResults');
    
    if (!searchInput || !orangtuaList) return;

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = orangtuaList.querySelectorAll('.orangtua-item');
        let visibleCount = 0;

        items.forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const email = item.dataset.email.toLowerCase();
            
            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    });

    // Handle checkbox changes
    const checkboxes = document.querySelectorAll('.orangtua-checkbox');
    
    checkboxes.forEach(checkbox => {
        // Initialize selected items
        if (checkbox.checked) {
            checkbox.closest('.orangtua-item').classList.add('selected');
        }
        
        updateSelectedDisplay();

        checkbox.addEventListener('change', function() {
            const item = this.closest('.orangtua-item');
            
            if (this.checked) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
            
            updateSelectedDisplay();
        });
    });

    // Handle item click (toggle checkbox)
    const items = orangtuaList.querySelectorAll('.orangtua-item');
    items.forEach(item => {
        item.addEventListener('click', function(e) {
            if (e.target.type === 'checkbox' || e.target.tagName === 'LABEL') {
                return;
            }
            
            const checkbox = this.querySelector('.orangtua-checkbox');
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
        });
    });

    function updateSelectedDisplay() {
        selectedContainer.innerHTML = '';
        
        const checkedBoxes = document.querySelectorAll('.orangtua-checkbox:checked');
        
        checkedBoxes.forEach(checkbox => {
            const item = checkbox.closest('.orangtua-item');
            const name = item.dataset.name;
            const email = item.dataset.email;
            
            const badge = document.createElement('span');
            badge.className = 'selected-badge';
            badge.innerHTML = `
                <span>${name}</span>
                <span class="remove-btn" data-id="${checkbox.value}">×</span>
            `;
            
            badge.querySelector('.remove-btn').addEventListener('click', function() {
                checkbox.checked = false;
                checkbox.dispatchEvent(new Event('change'));
            });
            
            selectedContainer.appendChild(badge);
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project ai\bina bola\binabola\resources\views/siswa/edit.blade.php ENDPATH**/ ?>