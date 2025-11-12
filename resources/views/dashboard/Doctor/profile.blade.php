@extends('dashboard.layouts.dashboard')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <style>
        /* نفس الستايل بتاعك */
        .image-container { position: relative; cursor: pointer; width: 120px; height: 120px; overflow: hidden; border-radius: 50%; transition: 0.3s; border: 3px solid #f1f1f1; }
        .image-container:hover { opacity: 0.9; }
        .image-container img { width: 100%; height: 100%; object-fit: cover; }
        .overlay-icon { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; }
        .image-container:hover .overlay-icon { opacity: 1; }
        .overlay-icon i { color: white; font-size: 2rem; }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                @if(session('success')) <div class="alert alert-success mx-4">{{ session('success') }}</div> @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="d-flex flex-column align-items-center mb-4">
                            <div class="image-container mb-3" onclick="document.getElementById('upload').click()">
                                @if($doctor->profile_photo)
                                    <img src="{{ asset('storage/images/users/' . $doctor->profile_photo) }}" id="displayImage" />
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $doctor->name }}&background=random" id="displayImage" />
                                @endif
                                <div class="overlay-icon"><i class="bx bx-camera"></i></div>
                            </div>

                            <p class="text-muted small">Click image to change</p>

                            {{-- 👇 ده الانبوت المهم: سميناه profile_image --}}
                            <input type="file" name="profile_image" id="upload" hidden accept="image/png, image/jpeg" />

                            {{-- زرار الحذف --}}
                            <button type="button" class="btn btn-sm btn-outline-danger {{ $doctor->profile_photo ? '' : 'd-none' }}" id="removeAvatarBtn">
                                Remove Photo
                            </button>
                            <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                        </div>

                        {{-- باقي الفورم زي ما هو --}}
                        <div class="row">
                            <div class="mb-3 col-md-6"> <label class="form-label">Full Name</label> <input class="form-control" type="text" name="name" value="{{ old('name', $doctor->name) }}" required /> </div>
                            <div class="mb-3 col-md-6"> <label class="form-label">E-mail</label> <input class="form-control" type="email" name="email" value="{{ old('email', $doctor->email) }}" required /> </div>
                            <div class="mb-3 col-md-6"> <label class="form-label">New Password</label> <input class="form-control" type="password" name="password" /> </div>
                            <div class="mb-3 col-md-6"> <label class="form-label">Confirm Password</label> <input class="form-control" type="password" name="password_confirmation" /> </div>
                        </div>
                        <div class="mt-2"> <button type="submit" class="btn btn-primary me-2">Save changes</button> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crop Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div style="max-height: 400px; width: 100%; overflow: hidden; background: #333;">
                        <img id="imageToCrop" style="max-width: 100%; display: block;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="cropAndSave">Crop & Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let cropper;
            const uploadInput = document.getElementById('upload');
            const imageToCrop = document.getElementById('imageToCrop');
            const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
            const displayImage = document.getElementById('displayImage');
            const removeBtn = document.getElementById('removeAvatarBtn');
            const removeInput = document.getElementById('removeImageInput');

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

            document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
                cropper = new Cropper(imageToCrop, {
                    aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 1,
                });
            });

            document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
                if (cropper) { cropper.destroy(); cropper = null; }
                // ملحوظة: هنا مش بنفضي الـ value عشان احنا لسة هنملاها بالملف المقصوص
            });

            // 👇👇👇 هنا السحر كله 👇👇👇
            document.getElementById('cropAndSave').addEventListener('click', function () {
                if (cropper) {
                    // 1. حول القص لـ Canvas عادي
                    cropper.getCroppedCanvas({ width: 300, height: 300 }).toBlob((blob) => {

                        // 2. إنشاء ملف جديد من الـ Blob ده
                        let file = new File([blob], "cropped_profile.jpg", { type: "image/jpeg", lastModified: new Date().getTime() });

                        // 3. استخدم DataTransfer عشان تحط الملف جوه الـ Input
                        let container = new DataTransfer();
                        container.items.add(file);
                        uploadInput.files = container.files;

                        // 4. عرض الصورة في الصفحة
                        displayImage.src = URL.createObjectURL(blob);

                        // 5. تظبيط زراير الحذف
                        removeInput.value = '0';
                        removeBtn.classList.remove('d-none');

                        cropModal.hide();
                    }, 'image/jpeg');
                }
            });

            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    displayImage.src = "https://ui-avatars.com/api/?name={{ $doctor->name }}&background=random";
                    removeInput.value = '1';
                    uploadInput.value = ''; // فضي الانبوت
                    this.classList.add('d-none');
                });
            }
        });
    </script>
@endsection
