<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Articles/Edit
        </h2>
        <a href="{{route('articles.index')}}" class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">back</a>
    </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                                                       <!-- Form to Create a Permission -->
                    <form action="{{ route('articles.update',$article->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label  class="text-lg font-medium">Title:</label>
                           <div class="my-3">
                            <input value="{{old('title',$article->title)}}" type="title" name="title" id="title" class="border-gray-300 shadow-sm w1/2 rounded-lg " placeholder="Enter Title Name">
                           </div>
                            @error('title')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label  class="text-lg font-medium">Content:</label>
                           <div class="my-3">
                        <textarea name="text" id="text" class="border-gray-300 shadow-sm w1/2 rounded-lg " placeholder="Content">{{old('title',$article->text)}}</textarea>
                           </div>
                        </div>

                        <div class="mb-4">
                            <label  class="text-lg font-medium">Author:</label>
                           <div class="my-3">
                            <input value="{{old('author',$article->author)}}" type="author" name="author" id="author" class="border-gray-300 shadow-sm w1/2 rounded-lg " placeholder="Enter Author Name">
                           </div>
                            @error('author')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="">
                          <button class="bg-slate-700 text-sm rounded-md px-5 py-3 text-white">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

