<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="post" action="{{ route('blogs.update', [$tenant, $blog]) }}"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="mt-4">
                            <x-input-label for="name" :value="__('name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="$blog->name" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('description')" />
                            <textarea id="description" name="description" rows="5"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200">
                                {{ $blog->description }}
                            </textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        @if ($blog->files)
                            <div class="my-4">
                                <x-input-label for="files" :value="__('Files')" />
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    @foreach ($blog->files as $file)
                                        <div>
                                            <img src="{{ asset('storage/' . $file) }}" alt="Uploaded file"
                                                class="w-full rounded shadow">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif


                        <!-- File Upload -->
                        <div class="mt-4">
                            <x-input-label for="files" :value="__('Upload File')" />
                            <input id="files" class="block mt-1 w-full" type="file" name="files[]" />
                            <x-input-error :messages="$errors->get('files')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Update Blogs Details') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
