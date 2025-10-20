# MODUL PRAKTIKUM 2
## WEBSITE WORKS: API LARAVEL WITH REACT.JS

---

## 1. Install Laravel Project

Pertama, buka Terminal dan jalankan perintah berikut untuk membuat proyek Laravel baru:

```bash
composer create-project --prefer-dist laravel/laravel crud-react-laravel
```

Atau, jika Anda telah menginstal Penginstal Laravel sebagai ketergantungan komposer global:

```bash
laravel new crud-react-laravel
```

---

## 2. Konfigurasikan Detail Basis Data

Setelah instalasi, buka direktori root proyek, buka file `.env`, dan atur detail database sebagai berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<DATABASE NAME>
DB_USERNAME=<DATABASE USERNAME>
DB_PASSWORD=<DATABASE PASSWORD>
```

---

## 3. Buat Migrasi, Model, dan Pengendali

Buat model Product, migrasi, dan pengontrol. Jalankan perintah berikut:

```bash
php artisan make:model Category -mcr
```

> **Catatan:** Argumen `-mcr` akan membuat Model, Migrasi, dan Pengontrol dalam Perintah Tunggal.

### Modifikasi File Migrasi

Buka file migrasi produk dari `database/migrations` dan ganti kode di fungsi `up()`:

```php
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('title');
        $table->text('description');
        $table->text('image');
        $table->timestamps();
    });
}
```

### Jalankan Migrasi Database

```bash
php artisan migrate
```

### Update Model Product

Buka model `Product.php` dari `app/Models` dan perbarui kodenya:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model 
{
    use HasFactory;
    
    protected $fillable = ['title', 'description', 'image'];
}
```

### Update ProductController

Buka `ProductController.php` dan tambahkan kode pada fungsi index, store, show, update, dan delete:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::select('id','title','description','image')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image'
        ]);

        try {
            $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('product/image', $request->image, $imageName);
            Product::create($request->post() + ['image' => $imageName]);
            
            return response()->json([
                'message' => 'Product Created Successfully!!'
            ]);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something goes wrong while creating a product!!'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable'
        ]);

        try {
            $product->fill($request->post())->update();
            
            if($request->hasFile('image')) {
                // remove old image
                if($product->image) {
                    $exists = Storage::disk('public')->exists("product/image/{$product->image}");
                    if($exists) {
                        Storage::disk('public')->delete("product/image/{$product->image}");
                    }
                }
                
                $imageName = Str::random().'.'.$request->image->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('product/image', $request->image, $imageName);
                $product->image = $imageName;
                $product->save();
            }
            
            return response()->json([
                'message' => 'Product Updated Successfully!!'
            ]);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something goes wrong while updating a product!!'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            if($product->image) {
                $exists = Storage::disk('public')->exists("product/image/{$product->image}");
                if($exists) {
                    Storage::disk('public')->delete("product/image/{$product->image}");
                }
            }
            
            $product->delete();
            
            return response()->json([
                'message' => 'Product Deleted Successfully!!'
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message' => 'Something goes wrong while deleting a product!!'
            ]);
        }
    }
}
```

---

## 4. Tentukan Rute di api.php

Buka folder `routes` dan buka file `api.php`, kemudian tambahkan rute berikut:

```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

### Buat Symbolic Link untuk Storage

Sebelum memulai aplikasi, jalankan perintah berikut untuk mengakses semua gambar yang diunggah:

```bash
php artisan storage:link
```

> **Catatan:** Disk `public` ditujukan untuk file yang akan dapat diakses publik. Secara default, `public` disk menggunakan driver lokal dan menyimpan file-file ini dalam format `storage/app/public`. Agar dapat diakses dari web, Anda harus membuat tautan simbolik dari `public/storage` ke `storage/app/public`.

### Jalankan Aplikasi Laravel

```bash
php artisan serve
```

Anda akan melihat bahwa API Anda tersedia untuk digunakan dengan Postman atau klien REST lain yang Anda inginkan.

---

## 5. Membangun Frontend Aplikasi React CRUD

Mari kita mulai membangun frontend dengan React, salah satu perpustakaan frontend JavaScript paling populer yang digunakan saat ini.

### Install Create React App

Di folder terpisah, jalankan perintah berikut:

```bash
npm install -g create-react-app
create-react-app crud-react
cd crud-react
```

### Install Dependencies

```bash
npm install axios react-bootstrap bootstrap
npm install react-router-dom sweetalert2 --save
```

### Import Bootstrap di App.js

Buka `src/App.js` dan impor file inti bootstrap ke bagian atas kode:

```javascript
import 'bootstrap/dist/css/bootstrap.css';
```

---

## 6. Membuat Komponen React

Buka folder `src` dan buat struktur folder berikut:
- `src/components/product/`

Buat file-file berikut di folder `product`:
- `create.component.js`
- `edit.component.js`
- `list.component.js`

### Create Component (create.component.js)

```javascript
import React, { useState } from "react";
import Form from 'react-bootstrap/Form'
import Button from 'react-bootstrap/Button'
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import axios from 'axios'
import Swal from 'sweetalert2';
import { useNavigate } from 'react-router-dom'

export default function CreateProduct() {
    const navigate = useNavigate();
    const [title, setTitle] = useState("")
    const [description, setDescription] = useState("")
    const [image, setImage] = useState()
    const [validationError, setValidationError] = useState({})

    const changeHandler = (event) => {
        setImage(event.target.files[0]);
    };

    const createProduct = async (e) => {
        e.preventDefault();
        const formData = new FormData()
        formData.append('title', title)
        formData.append('description', description)
        formData.append('image', image)

        await axios.post(`http://localhost:8000/api/products`, formData).then(({data})=>{
            Swal.fire({
                icon: "success",
                text: data.message
            })
            navigate("/")
        }).catch(({response})=>{
            if(response.status === 422) {
                setValidationError(response.data.errors)
            } else {
                Swal.fire({
                    text: response.data.message,
                    icon: "error"
                })
            }
        })
    }

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-12 col-sm-12 col-md-6">
                    <div className="card">
                        <div className="card-body">
                            <h4 className="card-title">Create Product</h4>
                            <hr />
                            <div className="form-wrapper">
                                {
                                    Object.keys(validationError).length > 0 && (
                                        <div className="row">
                                            <div className="col-12">
                                                <div className="alert alert-danger">
                                                    <ul className="mb-0">
                                                        {
                                                            Object.entries(validationError).map(([key, value])=>(
                                                                <li key={key}>{value}</li>
                                                            ))
                                                        }
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    )
                                }
                                <Form onSubmit={createProduct}>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="Name">
                                                <Form.Label>Title</Form.Label>
                                                <Form.Control type="text" value={title} onChange={(event)=>{
                                                    setTitle(event.target.value)
                                                }}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row className="my-3">
                                        <Col>
                                            <Form.Group controlId="Description">
                                                <Form.Label>Description</Form.Label>
                                                <Form.Control as="textarea" rows={3} value={description} onChange={(event)=>{
                                                    setDescription(event.target.value)
                                                }}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="Image" className="mb-3">
                                                <Form.Label>Image</Form.Label>
                                                <Form.Control type="file" onChange={changeHandler} />
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Button variant="primary" className="mt-2" size="lg" block="block" type="submit">
                                        Save
                                    </Button>
                                </Form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
```

### Edit Component (edit.component.js)

```javascript
import React, { useEffect, useState } from "react";
import Form from 'react-bootstrap/Form'
import Button from 'react-bootstrap/Button';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import { useNavigate, useParams } from 'react-router-dom'
import axios from 'axios';
import Swal from 'sweetalert2';

export default function EditUser() {
    const navigate = useNavigate();
    const { id } = useParams()
    const [title, setTitle] = useState("")
    const [description, setDescription] = useState("")
    const [image, setImage] = useState(null)
    const [validationError, setValidationError] = useState({})

    useEffect(()=>{
        fetchProduct()
    },[])

    const fetchProduct = async () => {
        await axios.get(`http://localhost:8000/api/products/${id}`).then(({data})=>{
            const { title, description } = data.product
            setTitle(title)
            setDescription(description)
        }).catch(({response:{data}})=>{
            Swal.fire({
                text: data.message,
                icon: "error"
            })
        })
    }

    const changeHandler = (event) => {
        setImage(event.target.files[0]);
    };

    const updateProduct = async (e) => {
        e.preventDefault();
        const formData = new FormData()
        formData.append('_method', 'PATCH');
        formData.append('title', title)
        formData.append('description', description)
        if(image !== null) {
            formData.append('image', image)
        }

        await axios.post(`http://localhost:8000/api/products/${id}`, formData).then(({data})=>{
            Swal.fire({
                icon: "success",
                text: data.message
            })
            navigate("/")
        }).catch(({response})=>{
            if(response.status === 422) {
                setValidationError(response.data.errors)
            } else {
                Swal.fire({
                    text: response.data.message,
                    icon: "error"
                })
            }
        })
    }

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-12 col-sm-12 col-md-6">
                    <div className="card">
                        <div className="card-body">
                            <h4 className="card-title">Update Product</h4>
                            <hr />
                            <div className="form-wrapper">
                                {
                                    Object.keys(validationError).length > 0 && (
                                        <div className="row">
                                            <div className="col-12">
                                                <div className="alert alert-danger">
                                                    <ul className="mb-0">
                                                        {
                                                            Object.entries(validationError).map(([key, value])=>(
                                                                <li key={key}>{value}</li>
                                                            ))
                                                        }
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    )
                                }
                                <Form onSubmit={updateProduct}>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="Name">
                                                <Form.Label>Title</Form.Label>
                                                <Form.Control type="text" value={title} onChange={(event)=>{
                                                    setTitle(event.target.value)
                                                }}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row className="my-3">
                                        <Col>
                                            <Form.Group controlId="Description">
                                                <Form.Label>Description</Form.Label>
                                                <Form.Control as="textarea" rows={3} value={description} onChange={(event)=>{
                                                    setDescription(event.target.value)
                                                }}/>
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Row>
                                        <Col>
                                            <Form.Group controlId="Image" className="mb-3">
                                                <Form.Label>Image</Form.Label>
                                                <Form.Control type="file" onChange={changeHandler} />
                                            </Form.Group>
                                        </Col>
                                    </Row>
                                    <Button variant="primary" className="mt-2" size="lg" block="block" type="submit">
                                        Update
                                    </Button>
                                </Form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
```

### List Component (list.component.js)

```javascript
import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Button from 'react-bootstrap/Button'
import axios from 'axios';
import Swal from 'sweetalert2'

export default function List() {
    const [products, setProducts] = useState([])

    useEffect(()=>{
        fetchProducts()
    },[])

    const fetchProducts = async () => {
        await axios.get(`http://localhost:8000/api/products`).then(({data})=>{
            setProducts(data)
        })
    }

    const deleteProduct = async (id) => {
        const isConfirm = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            return result.isConfirmed
        });

        if(!isConfirm) {
            return;
        }

        await axios.delete(`http://localhost:8000/api/products/${id}`).then(({data})=>{
            Swal.fire({
                icon: "success",
                text: data.message
            })
            fetchProducts()
        }).catch(({response:{data}})=>{
            Swal.fire({
                text: data.message,
                icon: "error"
            })
        })
    }

    return (
        <div className="container">
            <div className="row">
                <div className='col-12'>
                    <Link className='btn btn-primary mb-2 float-end' to={"/product/create"}>
                        Create Product
                    </Link>
                </div>
                <div className="col-12">
                    <div className="card card-body">
                        <div className="table-responsive">
                            <table className="table table-bordered mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {
                                        products.length > 0 && (
                                            products.map((row, key)=>(
                                                <tr key={key}>
                                                    <td>{row.title}</td>
                                                    <td>{row.description}</td>
                                                    <td>
                                                        <img width="50px" src={`http://localhost:8000/storage/product/image/${row.image}`} />
                                                    </td>
                                                    <td>
                                                        <Link to={`/product/edit/${row.id}`} className='btn btn-success me-2'>
                                                            Edit
                                                        </Link>
                                                        <Button variant="danger" onClick={()=>deleteProduct(row.id)}>
                                                            Delete
                                                        </Button>
                                                    </td>
                                                </tr>
                                            ))
                                        )
                                    }
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
```

---

## 7. Apa itu React Router?

React Router adalah pustaka perutean standar untuk React. React Router menjaga UI Anda tetap sinkron dengan URL. URL ini memiliki API sederhana dengan fitur-fitur canggih seperti pemuatan kode lambat, pencocokan rute dinamis, dan penanganan transisi lokasi.

### Menggunakan React Router

Buka file `App.js` di direktori `src` dan modifikasi sebagai berikut:

```javascript
import * as React from "react";
import Navbar from "react-bootstrap/Navbar";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import "bootstrap/dist/css/bootstrap.css";
import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import EditProduct from "./components/product/edit.component";
import ProductList from "./components/product/list.component";
import CreateProduct from "./components/product/create.component";

function App() {
    return (
        <Router>
            <Navbar bg="primary">
                <Container>
                    <Link to={"/"} className="navbar-brand text-white">
                        Basic Crud App
                    </Link>
                </Container>
            </Navbar>
            <Container className="mt-5">
                <Row>
                    <Col md={12}>
                        <Routes>
                            <Route path="/product/create" element={<CreateProduct />} />
                            <Route path="/product/edit/:id" element={<EditProduct />} />
                            <Route exact path='/' element={<ProductList />} />
                        </Routes>
                    </Col>
                </Row>
            </Container>
        </Router>
    );
}

export default App;
```

---

## 8. Menjalankan Aplikasi

Terakhir, saatnya menjalankan Aplikasi React CRUD kita.

```bash
npm run start
```

Aplikasi akan berjalan dan Anda dapat melihat pratinjau aplikasi di browser.

---

## Referensi

- Backend Github Repository
- Frontend Github Repository

---

**MySkill.id**  
*Raihan Nismara*