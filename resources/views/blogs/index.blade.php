<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($posts as $post)
                            <div
                                class="bg-white shadow-md rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition duration-300">
                                <h2 class="text-xl font-semibold text-gray-800 mb-2">
                                    {{ $post->title }}
                                </h2>

                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ $post->content }}
                                </p>

                                <div class="text-sm text-gray-400">
                                    Posted on {{ $post->created_at->format('F j, Y') }}
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500 py-10">
                                No posts yet. <a href="{{ route('posts.create', $tenant) }}"
                                    class="hover:underline text-blue-500 font-semibold">
                                    Create post </a>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
