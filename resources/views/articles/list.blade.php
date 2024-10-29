<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Articles') }}
            </h2>
            <a href="{{ route('articles.create') }}" class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Create</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <x-message />

            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">#</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Title</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Content</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Author</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Created</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if ($articles->isNotEmpty())
                        @foreach ($articles as $article)
                            <tr>
                                <td class="px-6 py-4">{{ $article->id }}</td>
                                <td class="px-6 py-4">{{ $article->title }}</td>
                                <td class="px-6 py-4">{{ $article->text }}</td>
                                <td class="px-6 py-4">{{ $article->author }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($article->created_at)->format('d M, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">

                                        @can('Edit Article')
                                        <a href="{{ route('articles.edit', $article->id) }}" class="bg-slate-700 text-sm rounded-md px-3 py-2 hover:bg-slate-600">Edit</a>
                                        @endcan

                                      @can('Delete Article')
                                        <form action="{{ route('articles.destroy', $article->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-sm rounded-md px-3 py-2 hover:bg-red-500" onclick="return confirm('Are you sure you want to delete this Article?')">
                                                Delete
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Not found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{ $articles->links() }}

        </div>
    </div>
</x-app-layout>
