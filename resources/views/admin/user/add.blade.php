@extends('admin.layout.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="max-w-xl w-full p-6 bg-white rounded-md shadow-md mx-auto">

    <h2 class="text-2xl font-semibold text-center mb-6">User Registration</h2>

    <form method="POST" action="{{ route('admin.user.store') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required autofocus>
            @error('name') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
            @error('email') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input id="password" type="password" name="password" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required autocomplete="new-password">
            @error('password') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="border rounded-md py-2 px-3 w-full focus:outline-none focus:border-blue-500" required>
            @error('phone') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- NEW: Departments multi-select --}}
        <div class="mb-2">
            <div class="flex items-center justify-between">
                <label class="block text-gray-700 text-sm font-bold mb-2">Assign Departments</label>
                <div class="space-x-2">
                    <button type="button" id="btnSelAll" class="text-blue-600 text-xs underline">Select all</button>
                    <button type="button" id="btnClearAll" class="text-gray-600 text-xs underline">Clear</button>
                </div>
            </div>

            @error('departments') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            @error('departments.*') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror

            <div class="grid grid-cols-2 gap-2 border rounded-md p-3">
                @php $oldDepts = collect(old('departments', []))->map(fn($v)=>(int)$v)->all(); @endphp
                @foreach($departments as $dept)
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="departments[]"
                               value="{{ $dept->id }}"
                               class="h-4 w-4"
                               {{ in_array($dept->id, $oldDepts, true) ? 'checked' : '' }}>
                        <span>{{ $dept->name }}</span>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-1">You can also change departments later from “Edit Privilege”.</p>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800">
                Register
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const boxes = Array.from(document.querySelectorAll('input[name="departments[]"]'));
    const btnSelAll = document.getElementById('btnSelAll');
    const btnClear  = document.getElementById('btnClearAll');
    if (btnSelAll) btnSelAll.addEventListener('click', ()=> boxes.forEach(b=> b.checked=true));
    if (btnClear)  btnClear.addEventListener('click', ()=> boxes.forEach(b=> b.checked=false));
});
</script>
@endsection
