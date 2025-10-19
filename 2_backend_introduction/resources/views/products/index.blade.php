@extends('products.layout')

@section('content')
<div class="row mt-5 justify-content-between">
    <div class="col-md-auto col-sm-12">
		<h2>Laravel 8 CRUD Example from scratch - ItSolutionStuff.com</h2>
    </div>
	<div class="col-md-auto col-sm-12">
		<a class="btn btn-success" href="{{ route('products.create') }}">Create New Product</a>
	</div>
</div>

<table class="table table-striped table-bordered mt-3">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Details</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($products as $product)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $product->name }}</td>
        <td>{{ $product->detail }}</td>
        <td>
            <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                <a class="btn btn-sm btn-info" href="{{ route('products.show',$product->id) }}">Show</a>
                <a class="btn btn-sm btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
                
                @csrf
                @method('DELETE')
                
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{!! $products->links() !!}
@endsection
