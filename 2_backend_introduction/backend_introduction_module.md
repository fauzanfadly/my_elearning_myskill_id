# MODUL PRAKTIKUM 1
## BACKEND ROLE (FULL STACK LARAVEL)

**MySkill.id - Raihan Nismara**

Dalam tutorial ini, Anda akan mempelajari operasi CRUD yang sangat mendasar dengan Laravel versi 8. Saya akan menunjukkan kepada Anda langkah demi langkah dari awal, jadi saya akan lebih memahami jika Anda baru mengenal Laravel.

---

## Langkah 1: Instal Laravel 8

Pertama-tama kita perlu mendapatkan aplikasi versi Laravel 8 baru menggunakan perintah di bawah ini. Jadi buka terminal atau command prompt Anda dan jalankan perintah di bawah ini:

```bash
composer create-project --prefer-dist laravel/laravel blog
```

---

## Langkah 2: Konfigurasi Basis Data

Pada langkah kedua, kita akan membuat konfigurasi database misalnya nama database, nama pengguna, kata sandi dll untuk aplikasi CRUD Laravel 8. Jadi mari kita buka file `.env` dan isi semua detail seperti di bawah ini:

**.env**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=root
```

---

## Langkah 3: Buat Migrasi

Kita akan membuat aplikasi CRUD untuk produk. Jadi kita harus membuat migrasi untuk tabel "products" menggunakan perintah artisan Laravel 8, jadi jalankan perintah di bawah ini terlebih dahulu:

```bash
php artisan make:migration create_products_table --create=products
```

Setelah perintah ini Anda akan menemukan satu file di jalur berikut `database/migrations` dan Anda harus memasukkan kode di bawah ini ke file migrasi Anda untuk membuat tabel products.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('detail');
            $table->timestamps();
        });
    }

    /**
     * Membalikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
```

Sekarang Anda harus menjalankan migrasi ini dengan perintah berikut:

```bash
php artisan migrate
```

---

## Langkah 4: Tambahkan Rute Resource

Di sini, kita perlu menambahkan rute resource untuk aplikasi produk CRUD. Jadi buka file `routes/web.php` Anda dan tambahkan rute berikut.

**routes/web.php**

```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

---

## Langkah 5: Tambahkan Controller dan Model

Pada langkah ini, sekarang kita harus membuat controller baru sebagai ProductController. Jadi jalankan perintah di bawah ini dan buat controller baru. Di bawah perintah untuk membuat resource controller.

```bash
php artisan make:controller ProductController --resource --model=Product
```

Setelah perintah di bawah ini Anda akan menemukan file baru di jalur ini `app/Http/Controllers/ProductController.php`.

Di controller ini akan membuat tujuh metode secara default seperti metode di bawah ini:

1. index()
2. create()
3. store()
4. show()
5. edit()
6. update()
7. destroy()

Jadi, mari salin kode di bawah ini dan letakkan di file ProductController.php.

**app/Http/Controllers/ProductController.php**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
        
        return view('products.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Tampilkan formulir untuk membuat resource baru.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Simpan resource yang baru dibuat di penyimpanan.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
        
        Product::create($request->all());
        
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Menampilkan resource yang ditentukan.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Tampilkan formulir untuk mengedit resource yang ditentukan.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Perbarui resource yang ditentukan dalam penyimpanan.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
        
        $product->update($request->all());
        
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Hapus resource tertentu dari penyimpanan.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
```

Oke, jadi setelah menjalankan perintah di bawah ini Anda akan menemukan `app/Models/Product.php` dan meletakkan konten di bawah ini di file Product.php:

**app/Models/Product.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'detail'
    ];
}
```

---

## Langkah 6: Tambahkan File Blade

Pada langkah terakhir, kita harus membuat file blade saja. Jadi terutama kita harus membuat file tata letak dan kemudian membuat folder "products" baru kemudian membuat file blade dari aplikasi CRUD. Jadi akhirnya Anda harus membuat file blade berikut:

1. layout.blade.php
2. index.blade.php
3. create.blade.php
4. edit.blade.php
5. show.blade.php

Jadi mari kita buat file berikut dan letakkan kode di bawah ini.

### resources/views/products/layout.blade.php

```html
<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8 CRUD Application - ItSolutionStuff.com</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
```

### resources/views/products/index.blade.php

```blade
@extends('products.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Laravel 8 CRUD Example from scratch - ItSolutionStuff.com</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('products.create') }}">Create New Product</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered">
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
                <a class="btn btn-info" href="{{ route('products.show',$product->id) }}">Show</a>
                <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
                
                @csrf
                @method('DELETE')
                
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{!! $products->links() !!}
@endsection
```

### resources/views/products/create.blade.php

```blade
@extends('products.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Product</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('products.index') }}">Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" class="form-control" placeholder="Name">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Detail:</strong>
                <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail"></textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
@endsection
```

### resources/views/products/edit.blade.php

```blade
@extends('products.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Product</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('products.index') }}">Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.update',$product->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Name">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Detail:</strong>
                <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail">{{ $product->detail }}</textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>
@endsection
```

### resources/views/products/show.blade.php

```blade
@extends('products.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Show Product</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('products.index') }}">Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $product->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Details:</strong>
            {{ $product->detail }}
        </div>
    </div>
</div>
@endsection
```

---

## Menjalankan Aplikasi

Sekarang kita siap menjalankan contoh aplikasi CRUD kita dengan Laravel 8 jadi jalankan perintah di bawah ini untuk menjalankan cepat:

```bash
php artisan serve
```

Sekarang Anda dapat membuka URL di bawah ini di browser Anda:

```
http://localhost:8000/products
```

---

## Tampilan Aplikasi

Anda akan melihat tata letak seperti di bawah ini:

- **Halaman Daftar**: Menampilkan semua produk dengan tombol Show, Edit, dan Delete
- **Tambah Halaman**: Form untuk menambahkan produk baru
- **Edit Halaman**: Form untuk mengedit produk yang sudah ada