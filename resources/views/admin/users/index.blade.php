<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All System Users') }}
            </h2>
            <!-- Link to create a new user -->
            <a href="{{ route('admin.users.create') }}" style="background:#4F46E5; color:white; padding:8px 15px; border-radius:4px; text-decoration:none; font-size:14px;">
                + Add New User
            </a>
        </div>
    </x-slot>

    <div class="py-12" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        
        <!-- Table Layout -->
        <table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
            <thead>
                <tr style="background: #F3F4F6; border-bottom: 2px solid #E5E7EB; text-align: left;">
                    <th style="padding: 12px 15px;">Name</th>
                    <th style="padding: 12px 15px;">Email Address</th>
                    <th style="padding: 12px 15px;">Assigned Role</th>
                    <th style="padding: 12px 15px;">Created Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #E5E7EB;">
                        <td style="padding: 12px 15px; font-weight: 500;">{{ $user->name }}</td>
                        <td style="padding: 12px 15px; color: #4B5563;">{{ $user->email }}</td>
                        <td style="padding: 12px 15px;">
                            <!-- Loop through Spatie roles (even if they only have one) -->
                            @forelse($user->roles as $role)
                                <span style="background: #EEF2FF; color: #4F46E5; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span style="color: #9CA3AF; font-size: 13px; font-style: italic;">No Role</span>
                            @endforelse
                        </td>
                        <td style="padding: 12px 15px; color: #9CA3AF; font-size: 14px;">
                            {{ $user->created_at->format('Y-m-d') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 20px; text-align: center; color: #6B7280;">
                            No system users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-app-layout>
