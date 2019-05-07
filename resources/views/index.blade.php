@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <table id="datatable" class="table table-bordered">
                        <thead class="thead-inverse">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>CV</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="candidate-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table>
                        <tr>
                            <th>Nama</th>
                            <td id="name"></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td id="gender"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="email"></td>
                        </tr>
                        <tr>
                            <th>No. Telepon</th>
                            <td id="phone"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/dataTables.bootstrap4.min.js" defer></script>
<script>
    $(function() {
        var tbl = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("datatable") }}',
            columns: [
                { data: 'id', name: 'id', visible: false, orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'cv_link', name: 'cv_link' },
                { data: 'status', name: 'status', className: 'text-center' },
                { data: 'actions', name: 'actions', className: 'text-center', orderable: false, searchable: false }
            ]
        });

        $('#datatable').on('click', '.link-show', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: "{{ url('/') }}/" + id,
                dataType: 'json',
            }).done(function(result) {
                $('#name').html(result.name);
                $('#gender').html(result.gender);
                $('#email').html(result.email);
                $('#phone').html(result.phone);

                $('#candidate-modal').modal('show');
            });
        });

        $('#datatable').on('click', '.link-approve', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: "{{ url('/') }}/" + id + "/approve",
                dataType: 'json',
            }).done(function(result) {
                setTimeout(function() {
                    tbl.draw();
                }, 1000);
            });
        });

        $('#datatable').on('click', '.link-reject', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: "{{ url('/') }}/" + id + "/reject",
                dataType: 'json',
            }).done(function(result) {
                setTimeout(function() {
                    tbl.draw();
                }, 1000);
            });
        });

    });
</script>
@endsection
