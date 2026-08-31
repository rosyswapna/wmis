<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New User Account') }}
        </h2>
    </x-slot>

    <div class="py-12" style="max-width: 500px; margin: 0 auto; padding: 20px;">
        
        <!-- Success Alert Message -->
        @if (session('status'))
            <div style="color: green; margin-bottom: 20px; font-weight: bold;">
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors Link -->
        @if ($errors->any())
            <div style="color: red; margin-bottom: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <!-- Full Name -->
            <div style="margin-bottom: 15px;">
                <label style="display:block;">User Full Name:</label>
                <input type="text" name="name" value="{{ old('name') }}" required style="width:100%; padding:8px; border:1px solid #ccc;">
            </div>

            <!-- Email Address -->
            <div style="margin-bottom: 15px;">
                <label style="display:block;">Email Address:</label>
                <input type="email" name="email" value="{{ old('email') }}" required style="width:100%; padding:8px; border:1px solid #ccc;">
            </div>

            <!-- Select Role Dropdown -->
            <div style="margin-bottom: 15px;">
                <label style="display:block;">Assign System Role:</label>
                <select name="role" required style="width:100%; padding:8px; border:1px solid #ccc;">
                    <option value="">-- Choose a Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucwords($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Password -->
            <div style="margin-bottom: 15px;">
                <label style="display:block;">Temporary Password:</label>
                <input type="password" name="password" required style="width:100%; padding:8px; border:1px solid #ccc;">
            </div>

            <!-- Confirm Password -->
            <div style="margin-bottom: 20px;">
                <label style="display:block;">Confirm Password:</label>
                <input type="password" name="password_confirmation" required style="width:100%; padding:8px; border:1px solid #ccc;">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" style="background:#4F46E5; color:white; padding:10px 20px; border:none; border-radius:4px; cursor:pointer;">
                    Save User Account
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
