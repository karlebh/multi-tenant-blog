<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post Details') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showCommentBox: true }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Post Header -->
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $post->title }}</h1>

                        <div class="flex items-center text-sm text-gray-400 mb-4">
                            <span>Posted by {{ $post->user->name }} on {{ $post->created_at->format('F j, Y') }}</span>
                        </div>

                        <!-- Post Image (if available) -->
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                class="w-full h-64 object-cover rounded-lg mb-4">
                        @endif
                    </div>

                    <!-- Post Content -->
                    <div class="prose lg:prose-xl text-gray-700">
                        <p>{{ $post->content }}</p>


                    </div>

                    <div class="my-5 space-x-5">
                        <a href="{{ route('posts.edit', [$tenant, $post]) }}" class="text-blue-600 underline">edit
                            post</a>

                    </div>

                    @if ($post->files)
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            @foreach ($post->files as $file)
                                <div>
                                    <img src="{{ asset('storage/' . $file) }}" alt="Uploaded file"
                                        class="w-full rounded shadow">
                                </div>
                            @endforeach
                        </div>
                    @endif


                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-800">Comments</h3>

                        @forelse ($post->comments as $comment)
                            <div class="mt-4 p-4 border border-gray-200 rounded-lg shadow-sm">
                                <div class="flex items-center">
                                    {{-- <span class="font-semibold text-gray-700">{{ $comment->user->name }}</span> --}}
                                    <span
                                        class="ml-2 text-sm text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-2 text-gray-700">{{ $comment->content }}</p>
                            </div>
                        @empty
                            <p class="mt-4 text-gray-500">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>

                    <div class="mt-8">
                        <div class="space-x-10">
                            <!-- Button to Toggle Comment Box -->
                            <button @click="showCommentBox = ! showCommentBox"
                                class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                Hide/Show Comments
                            </button>

                            <!-- Back to Blog Home Link -->

                            <a href="{{ route('blogs.index', $tenant) }}"
                                class="text-indigo-600 hover:text-indigo-900 font-semibold">Back to Blog</a>
                        </div>


                        <!-- Comment Box (Hidden/Visible based on Alpine.js state) -->
                        <div x-show="showCommentBox" class="mt-4 border-t pt-4">
                            <form method="POST" action="{{ route('comments.store', $post) }}">
                                @csrf

                                <input type="hidden" value="{{ $post->id }}" name="post_id">
                                <input type="hidden" value="{{ auth()->id() ?? null }}" name="user_id">

                                @guest
                                    <div>
                                        <x-input-label for="name" :value="__('Name')" />
                                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                            :value="old('name')" required autofocus />
                                        <x-input-error :messages="$errors->get('name')" class="my-2" />
                                    </div>
                                @endguest

                                <div class="mt-5">

                                    <x-input-label for="content" :value="__('Your Comment')" />
                                    <textarea id="content" class="block mt-1 w-full" name="content" required placeholder="Write your comment here..."></textarea>
                                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button>
                                        {{ __('Post Comment') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>
