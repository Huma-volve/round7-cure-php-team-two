@props([
    'currentImageUrl' => null,
    'defaultName' => 'User',
    'inputName' => 'profile_image',
    'removeInputName' => 'remove_image'
])

@php
    // بنعمل ID عشوائي عشان لو استخدمنا الكومبوننت ده مرتين في نفس الصفحة
    $componentId = 'cropper_' . \Illuminate\Support\Str::random(8);
@endphp

<style>
    .image-container { position: relative; cursor: pointer; width: 120px; height: 120px; overflow: hidden; border-radius: 50%; transition: 0.3s; border: 3px solid #f1f1f1; }
    .image-container:hover { opacity: 0.9; }
    .image-container img { width: 100%; height: 100%; object-fit: cover; }
    .overlay-icon { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; }
    .image-container:hover .overlay-icon { opacity: 1; }
    .overlay-icon i { color: white; font-size: 2rem; }
</style>

<div class="d-flex flex-column align-items-center mb-4">
    <div class="image-container mb-3" onclick="document.getElementById('upload_{{ $componentId }}').click()">
        @if($currentImageUrl)
            <img src="{{ $currentImageUrl }}" id="displayImage_{{ $componentId }}" />
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($defaultName) }}&background=random" id="displayImage_{{ $componentId }}" />
        @endif
        <div class="overlay-icon"><i class="bx bx-camera"></i></div>
    </div>

    <p class="text-muted small">Click image to change</p>

    {{-- الانبوت المخفي --}}
    <input type="file" name="{{ $inputName }}" id="upload_{{ $componentId }}" hidden accept="image/png, image/jpeg" />

    {{-- زرار الحذف --}}
    <button type="button" class="btn btn-sm btn-outline-danger {{ $currentImageUrl ? '' : 'd-none' }}" id="removeAvatarBtn_{{ $componentId }}">
        Remove Photo
    </button>
    <input type="hidden" name="{{ $removeInputName }}" id="removeImageInput_{{ $componentId }}" value="0">
</div>

<div class="modal fade" id="cropModal_{{ $componentId }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div style="max-height: 400px; width: 100%; overflow: hidden; background: #333;">
                    <img id="imageToCrop_{{ $componentId }}" style="max-width: 100%; display: block;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="cropAndSave_{{ $componentId }}">Crop & Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {
            // بنستخدم الـ ID العشوائي عشان الكود يشتغل صح
            const componentId = '{{ $componentId }}';
            let cropperInstance = null;

            const uploadInput = document.getElementById('upload_' + componentId);
            const imageToCrop = document.getElementById('imageToCrop_' + componentId);
            const cropModalEl = document.getElementById('cropModal_' + componentId);
            const cropModal = new bootstrap.Modal(cropModalEl);
            const displayImage = document.getElementById('displayImage_' + componentId);
            const removeBtn = document.getElementById('removeAvatarBtn_' + componentId);
            const removeInput = document.getElementById('removeImageInput_' + componentId);
            const cropAndSaveBtn = document.getElementById('cropAndSave_' + componentId);
            const defaultAvatar = "https://ui-avatars.com/api/?name={{ urlencode($defaultName) }}&background=random";

            uploadInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imageToCrop.src = e.target.result;
                        cropModal.show();
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            cropModalEl.addEventListener('shown.bs.modal', function () {
                cropperInstance = new Cropper(imageToCrop, {
                    aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 1,
                });
            });

            cropModalEl.addEventListener('hidden.bs.modal', function () {
                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }
            });

            cropAndSaveBtn.addEventListener('click', function () {
                if (cropperInstance) {
                    cropperInstance.getCroppedCanvas({ width: 300, height: 300 }).toBlob((blob) => {
                        let file = new File([blob], "cropped_profile.jpg", { type: "image/jpeg", lastModified: new Date().getTime() });
                        let container = new DataTransfer();
                        container.items.add(file);
                        uploadInput.files = container.files;
                        displayImage.src = URL.createObjectURL(blob);
                        removeInput.value = '0';
                        removeBtn.classList.remove('d-none');
                        cropModal.hide();
                    }, 'image/jpeg');
                }
            });

            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    displayImage.src = defaultAvatar;
                    removeInput.value = '1';
                    uploadInput.value = '';
                    this.classList.add('d-none');
                });
            }
        })();
    </script>
@endpush
