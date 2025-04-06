<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse ($users as $user)
                        <div class=" p-4 flex
                             justify-between items-center">
                            <div>
                                <p class="text-lg font-bold text-gray-800">{{ $user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                <p class="text-sm font-medium text-blue-600">
                                    {{ $user->is_approved ? 'Approved' : 'Not Approved' }}</p>
                            </div>
                            <div>
                                @if (!$user->is_approved)
                                    <form action="{{ route('admin.approve', $user) }}" method="POST" class="inline">
                                        @csrf

                                        <button type="submit" style="background-color: #22c55e"
                                            class="px-4 py-2 rounded-lg text-white">Approve
                                            User</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.revoke', $user) }}" method="POST" class="inline">
                                        @csrf

                                        <button type="submit" style="background-color: #ef4444"
                                            class="px-4 py-2 rounded-lg text-white">Revoke Approval</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-600">No user yet.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
