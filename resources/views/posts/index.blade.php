@extends('posts.layouts')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Laravel 9 CRUD Operation Step By Step - Techsolutionstuff</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('posts.create') }}"> Create New Post</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<div class="mt-1 mb-4">
    <div class="relative max-w-xs">
        <form action="{{ route('posts.search') }}" method="GET">
            <label for="search" class="sr-only">
                Search
            </label>
            <input type="text" name="search"
                class="block w-full p-3 pl-10 text-sm border-gray-200 rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                placeholder="Search..." value="{{ request('search') }}" />
                <div class="relative inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px; height:20px;" class="w-5 h-5 text-gray-400 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
        </form>
    </div>

</div>


<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Image</th>
        <th>Details</th>
        <th>Role</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($posts as $post)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $post->name }}</td>
        <td><img src="{{ asset('storage/'.$post->image) }}" style="height: 50px;width:50px;"></td>
        <td>{{ $post->description }}</td>
        <td>{{ $post->pub_view }}</td>

        <td>
            <form action="{{ route('posts.destroy', ['post' => $post->id, 'search' => request('search')]) }}" method="POST">

                <a class="btn btn-info" href="{{ route('posts.show',$post->id) }}">Show</a>

                <a class="btn btn-primary" href="{{ route('posts.edit',$post->id) }}">Edit</a>

                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{!! $posts->links() !!}


@endsection
