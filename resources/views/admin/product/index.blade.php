@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between mt-1 p-3">
        <button class="btn btn-primary mt-3" type="button" data-bs-toggle="modal" data-bs-target="#addNew">
            <i class="mdi mdi-library-plus"></i>
            tambah produk baru</button>
    </div>
    <div class="table mt-2 p-3 table-responsive">
        <table class="table table-bordered table-striped" id="table-blog">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th>Foto</th>
                    <th>Tersedia</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->stock }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td><img src="{{ url('storage/' . $item->image) }}"
                                style="width: 50px; height: 50px; object-fit: cover;" alt="gambar" class="rounded-circle">
                        </td>
                        <td>
                            @if ($item->is_available == 1)
                                Ada
                            @else
                                Habis
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="" class="btn btn-warning" style="color: white;" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $item->id }}">
                                    <i class="mdi mdi-table-edit"></i> Edit</a>

                                <form action="/admin/product/{{ $item->id }}" method="post" class="delete-form m-0">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button class="btn btn-danger delete-buttons" style="box-sizing: 0" type="submit">
                                        <i class="mdi mdi-delete"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <!-- edit Modal -->
                    <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog bg-white">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel"> <i class="mdi mdi-table-edit"></i>
                                        Edit
                                        Product</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="/admin/product/{{ $item->id }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="name" class="mb-1">Nama</label>
                                            <input type="text" class="form-control" id="name" name="nama"
                                                value="{{ $item->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="price" class="mb-1">Harga</label>
                                            <input type="number" class="form-control" id="price" name="price"
                                                value="{{ $item->price }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="stock" class="mb-1">Stok</label>
                                            <input type="number" class="form-control" id="stock" name="stock"
                                                value="{{ $item->stock }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="image" class="mb-1">Foto</label>
                                            <input type="file" class="form-control" id="image" name="image"
                                                value="{{ $item->image }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="category" class="mb-1">Kategori</label>
                                            <select class="form-select" aria-label="Default select example" id="category"
                                                name="category">
                                                <option value="2" {{ $item->category->id == 2 ? 'selected' : '' }}>
                                                    Alat Pertanian</option>
                                                <option value="3" {{ $item->category->id == 3 ? 'selected' : '' }}>
                                                    Pestisida</option>
                                                <option value="4" {{ $item->category->id == 4 ? 'selected' : '' }}>
                                                    Pupuk</option>
                                                <option value="5" {{ $item->category->id == 5 ? 'selected' : '' }}>
                                                    Benih</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="is_available" class="mb-1">Status Produk</label>
                                            <select class="form-select" aria-label="Default select example"
                                                id="is_available" name="is_available">
                                                <option value="1" {{ $item->is_available == '1' ? 'selected' : '' }}>
                                                    Ada</option>
                                                <option value="2" {{ $item->is_available == '2' ? 'selected' : '' }}>
                                                    Habis</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="mb-1">Deskripsi</label>
                                            <textarea name="description" id="description" cols="30" rows="3" class="form-control">{{ $item->description }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
                <script>
                    const deleteButtons = document.querySelectorAll('.delete-buttons');

                    deleteButtons.forEach(button => {
                        button.addEventListener('click', function(event) {
                            event.preventDefault();

                            const id = this.parentNode.querySelector('input[name="id"]').value;

                            Swal.fire({
                                title: 'Hapus Data?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Hapus',
                                cancelButtonText: 'Batal',
                                showCloseButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                customClass: {
                                    container: 'my-swal'
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.parentNode.action = '/admin/product/' + id;
                                    this.parentNode.submit();
                                }
                            });
                        });
                    });
                </script>
            </tbody>
        </table>
    </div>
@endsection
{{-- modal add new product --}}
<div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="mdi mdi-library-plus"></i> Tambah
                    Produk
                    Baru
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/saveProduct" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="mb-1">Nama</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="input nama produk ">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="mb-1">Harga</label>
                        <input type="number" class="form-control" id="price" name="price" required
                            placeholder="input harga">
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="mb-1">Stok</label>
                        <input type="number" class="form-control" id="stock" name="stock" required
                            placeholder="input stok produk" maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="mb-1">Foto</label>
                        <input type="file" class="form-control" id="image" name="image" required
                            placeholder="input foto product">
                    </div>
                    <div class="mb-3">
                        <label for="is_available" class="mb-1">Status Produk</label>
                        <select class="form-select" aria-label="Default select example" id="is_available"
                            name="is_available">
                            <option value="">-- Pilih Status Produk --</option>
                            <option value="1">Ada</option>
                            <option value="2">Habis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="mb-3">
                        <label for="roles" class="mb-1">Kategori</label>
                        <select class="form-select" aria-label="Default select example">
                            <option value="1">Benih</option>
                            <option value="2">Pupuk</option>
                            <option value="3">Pestisida</option>
                            <option value="4">Alat Pertanian</option>
                        </select>
                    </div> --}}
                    <div class="mb-3">
                        <label for="description" class="mb-1">Deskripsi</label>
                        <textarea name="description" id="description" cols="30" rows="3" class="form-control"
                            placeholder="Input Deskripsi"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        $(document).ready(function() {
            $('#table-blog').DataTable();
        });
    </script>
@endpush
