@extends("layouts.admin")
@section('content')
<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <h3 class="text-center my-1">Tutorial Laravel 11 untuk Pemula</h3>
                <hr>
            </div>
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body">
                    <a href="{{ route('product.create') }}" class="mt-2 btn btn-md btn btn-dark mb-3">ADD PRODUCT</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">NO</th>
                                <th scope="col">IMAGE</th>
                                <th scope="col">TITLE</th>
                                <th scope="col">PRICE</th>
                                <th scope="col">STOCK</th>
                                <th scope="col" style="width: 20%">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('/storage/products/'.$product->image) }}" class="rounded" style="width: 150px">
                                    </td>
                                    <td>{{ $product->title }}</td>
                                    <td>{{ "Rp " . number_format($product->price,2,',','.') }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td class="text-center">
                                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('product.destroy',$product->id) }}" method="POST">
                                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-dark">SHOW</a>
                                            <a href="{{ route('product.edit',$product->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">HAPUS</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <div class="alert alert-danger">
                                    Data Products belum Tersedia.
                                </div>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection