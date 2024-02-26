@extends('layouts.main')
@section('content')
    <div class="d-flex justify-content-between mt-1 p-3">
        <button class="btn btn-primary mt-3" type="button" data-bs-toggle="modal" data-bs-target="#addNew">
            <i class="mdi mdi-library-plus"></i>
            tambah user baru</button>
    </div>
    <div class="table mt-2 p-3 table-responsive">
        <table class="table table-bordered table-striped" id="table-blog">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Roles</th>
                    <th>Foto</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->roles }}</td>
                        <td><img src="{{ url('storage/' . $item->foto) }}"
                                style="width: 50px; height: 50px; object-fit: cover;" alt="gambar" class="rounded-circle">
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="" class="btn btn-warning" style="color: white;" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $item->id }}">
                                    <i class="mdi mdi-table-edit"></i> Edit</a>

                                <form action="/admin/user/{{ $item->id }}" method="post" class="delete-form m-0">
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
                                        User</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="/admin/user/{{ $item->id }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="name" class="mb-1">Nama</label>
                                            <input type="text" class="form-control" id="name" name="nama"
                                                value="{{ $item->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="mb-1">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ $item->email }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="mb-1">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                value="{{ $item->password }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="mb-1">Phone</label>
                                            <input type="number" class="form-control" id="phone" name="phone"
                                                value="{{ $item->phone }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="foto" class="mb-1">Foto</label>
                                            <input type="file" class="form-control" id="foto" name="foto"
                                                value="{{ $item->foto }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="roles" class="mb-1">Roles</label>
                                            <input type="text" class="form-control" id="roles" name="roles"
                                                value="{{ $item->roles }}">
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
                                    this.parentNode.action = '/admin/user/' + id;
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
{{-- modal add new blog --}}
<div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="mdi mdi-library-plus"></i> Tambah User
                    Baru
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/saveUser" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="mb-1">Nama</label>
                        <input type="text" class="form-control" id="nama" name="name"
                            placeholder="input nama ">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="mb-1">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required
                            placeholder="input email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="mb-1">Password</label>
                        <input type="text" class="form-control" id="password" name="password" required
                            placeholder="input email" maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="mb-1">Phone</label>
                        <input type="number" class="form-control" id="phone" name="phone" required
                            placeholder="input phone">
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="mb-1">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" required
                            placeholder="input foto">
                    </div>
                    <div class="mb-3">
                        <label for="roles" class="mb-1">Roles</label>
                        <select class="form-select" aria-label="Default select example">
                            <option selected value="member">Member</option>
                            <option value="owner">Owner</option>
                            <option value="kasir">Kasir</option>
                        </select>
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
