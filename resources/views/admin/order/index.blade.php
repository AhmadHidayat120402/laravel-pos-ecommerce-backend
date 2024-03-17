@extends('layouts.main')
@section('content')
    {{-- <div class="d-flex justify-content-between mt-1 p-3">
        <button class="btn btn-primary mt-3" type="button" data-bs-toggle="modal" data-bs-target="#addNew">
            <i class="mdi mdi-library-plus"></i>
            tambah order</button>
    </div> --}}
    <div class="table mt-2 p-3 table-responsive">
        <table class="table table-bordered table-striped" id="table-blog">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Transaction Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Shipping Name</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->total_cost }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->shipping_service }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="" class="btn btn-warning" style="color: white;" data-bs-toggle="modal"
                                    data-bs-target="#edit{{ $item->id }}">
                                    <i class="mdi mdi-table-edit"></i> Edit</a>

                                <form action="/admin/order/{{ $item->id }}" method="post" class="delete-form m-0">
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
                                        Order</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="/admin/order/{{ $item->id }}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="created_at" class="mb-1">Transaction Date</label>
                                            <input type="date" class="form-control" id="created_at" name="created_at"
                                                value="{{ $item->created_at }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="total_cost" class="mb-1">Total</label>
                                            <input type="number" class="form-control" id="total_cost" name="total_cost"
                                                value="{{ $item->total_cost }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="mb-1">Status Order</label>
                                            <select class="form-select" aria-label="Default select example" id="status"
                                                name="status">
                                                <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="paid" {{ $item->status == 'paid' ? 'selected' : '' }}>
                                                    Paid</option>
                                                <option value="on_delivery"
                                                    {{ $item->status == 'on_delivery' ? 'selected' : '' }}>
                                                    On Delivery</option>
                                                <option value="delivered"
                                                    {{ $item->status == 'delivered' ? 'selected' : '' }}>
                                                    Delivered</option>
                                                <option value="expired" {{ $item->status == 'expired' ? 'selected' : '' }}>
                                                    Expired</option>
                                                <option value="cancelled"
                                                    {{ $item->status == 'cancelled' ? 'selected' : '' }}>
                                                    Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipping_resi" class="mb-1">No Resi</label>
                                            <input type="text" class="form-control" id="shipping_resi"
                                                name="shipping_resi" value="{{ $item->shipping_resi }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="shipping_service" class="mb-1">Shipping Name</label>
                                            <input type="text" class="form-control" id="shipping_service"
                                                name="shipping_service" value="{{ $item->shipping_service }}" disabled>
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
                                    this.parentNode.action = '/admin/order/' + id;
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
{{-- modal add new Order --}}
<div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="mdi mdi-library-plus"></i> Tambah
                    Order
                    Baru
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/admin/saveOrder" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="created_at" class="mb-1">Transaction Date</label>
                        <input type="date" class="form-control" id="created_at" name="created_at"
                            placeholder="input tanggal order ">
                    </div>
                    <div class="mb-3">
                        <label for="total_cost" class="mb-1">Total</label>
                        <input type="number" class="form-control" id="total_cost" name="total_cost" required
                            placeholder="input total order">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="mb-1">Status Order</label>
                        <select class="form-select" aria-label="Default select example" id="status"
                            name="status">
                            <option value="">-- Pilih Status Order --</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="on_delivery">on_delivery</option>
                            <option value="delivered">delivered</option>
                            <option value="expired">expired</option>
                            <option value="cancelled">cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="created_at" class="mb-1">Shipping Name</label>
                        <input type="text" class="form-control" id="created_at" name="created_at"
                            placeholder="input tanggal order ">
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
