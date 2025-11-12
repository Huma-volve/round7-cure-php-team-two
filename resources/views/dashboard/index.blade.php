{{-- resources/views/dashboard.blade.php --}}
<script >
    localStorage.setItem('has_account', 'true');
</script>
@extends('dashboard.layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="row gy-6">
        {{-- content from template cards etc. --}}
        <div class="col-12">
            <h3>Welcome to Dashboard</h3>
        </div>
    </div>
@endsection
