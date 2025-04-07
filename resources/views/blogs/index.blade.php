<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse ($posts as $post)
                            <div
                                class="bg-white shadow-lg rounded-lg p-6 border border-gray-200 hover:shadow-xl transition duration-300">
                                <div class="relative">
                                    <!-- Image Section (Optional) -->
                                    @if ($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                            class="w-full h-56 object-cover rounded-t-lg mb-4">
                                    @endif
                                    <h2 class="text-2xl font-semibold text-gray-800 mb-2 hover:text-indigo-600">
                                        <a href="{{ route('posts.show', [$tenant, $post]) }}">{{ $post->title }}</a>
                                    </h2>
                                </div>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-4">
                                    {{ $post->content }}
                                </p>

                                <div class="flex items-center justify-between text-sm text-gray-400">
                                    <span>Posted on {{ $post->created_at->format('F j, Y') }}</span>
                                    <a href="{{ route('posts.show', [$tenant, $post]) }}"
                                        class="text-indigo-600 hover:text-indigo-900 font-semibold">Read more</a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500 py-10">
                                No posts yet.
                                <a href="{{ route('posts.create', $tenant) }}"
                                    class="hover:underline text-blue-500 font-semibold underline">Create a post</a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
