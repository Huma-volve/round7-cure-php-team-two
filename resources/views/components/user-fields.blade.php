@props([
    'user' => null,
    'isUpdate' => false
])

<div class="row">
    <div class="mb-3 col-md-6">
        <label class="form-label">Full Name</label>
        <input class="form-control" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required />
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label">E-mail</label>
        <input class="form-control" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required />
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label">Phone</label>
        <input class="form-control" type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}" />
    </div>

    {{-- 👇👇👇 (هنا الـ Slot) 👇👇👇 --}}
    {{-- أي حقول زيادة هتحطها، هتظهر هنا --}}
    {{ $slot }}
    {{-- 👆👆👆 (انتهى الـ Slot) 👆👆👆 --}}


    <div class="mb-3 col-md-6">
        <label class="form-label">New Password</label>
        <input class="form-control" type="password" name="password" {{ $isUpdate ? '' : 'required' }} />
        @if($isUpdate)
            <small class="text-muted">Leave blank to keep the current password.</small>
        @endif
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label">Confirm Password</label>
        <input class="form-control" type="password" name="password_confirmation" {{ $isUpdate ? '' : 'required' }} />
    </div>
</div>
