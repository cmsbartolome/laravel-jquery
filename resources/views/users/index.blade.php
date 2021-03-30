@extends('layouts.front.app')
@section('stylesheets')
    <link href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
@endsection
@section('content')
<div class="container">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-10">
                <h3><b>Datatables User List</b></h3>
            </div>
            <div class="col-md-2">
                <button data-toggle="modal"  class="btn btn-sm btn-success mb-3 float-right callModal" data-action="create"><span class="fa fa-plus"></span> Add</button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div id="datatable-error"></div>
                    <table class="table table-hover table-sm" id="user-list-table" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade "  tabindex="-1" id="user-modal" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Modal</h5>
                    <button type="button" class="close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                </div>
                <div class="modal-body">
                    <form  role="form" id="user-form" >
                        @csrf
                        <input type="hidden" name="eid" id="eid" value="">
                        <div class="form-group">
                            <label for="" class="control-label">Fullname:</label>
                            <input type="text" name="name" id="name" class="form-control form-control-sm" required />
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Email:</label>
                            <input type="email" name="email" id="email" class="form-control form-control-sm" maxlength="200" required />
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Password:</label>
                            <input id="password" class="form-control form-control-sm" type="password"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"  name="password" autocomplete="new-password" minlength="8">
                            <div id="message" style="border-radius: 20px; width: auto; border: 1px solid #eee;">
                                <div style="padding: 20px;">
                                    <p>Strong Password guidelines</p>
                                    <p id="letter" class="invalid" style="margin-top: 0px; font-size: 14px">A <b>Lowercase letter</b></p>
                                    <p id="capital" class="invalid" style="margin-top: 0px; font-size: 14px">A <b>Uppercase letter</b></p>
                                    <p id="number" class="invalid" style="margin-top: 0px; font-size: 14px">A <b>Number</b></p>
                                    <p id="length" class="invalid" style="margin-top: 0px; font-size: 14px">Minimum of <b>8 characters</b></p>
                                    <p id="special" class="invalid" style="margin-top: 0px; font-size: 14px">Atleast <b>1 special character</b></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Retype password:</label>
                            <input type="password" id="password-confirm" name="password_confirmation" id="password_confirmation" class="form-control form-control-sm"  />
                            <span id="msg" class="invalid"><b>Confirm password do not match</b></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button  form="user-form" id="user-btn" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
{{--    <noscript>Please enable your javascript in the browser</noscript>--}}
    <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/password_validator.js"></script>
    <script>
        function validateName(e){
            var t=document.getElementById(e),a=/[^a-zA-Z ]/gi;t.value.search(a)>-1&&(t.value=t.value.replace(a,""))
        }

        function validatePhoneNum(e){
            var t=document.getElementById(e),a=/[^0-9]/gi;t.value.search(a)>-1&&(t.value=t.value.replace(a,""))
        }

        $(document).on("click", ".callModal", function (e) {
            e.preventDefault();

            switch ($(this).data('action')) {
                case"create":
                              $('.modal-title').html('New user');
                              $('#user-form')[0].reset();
                              $('#eid').hide();
                              $('#password').attr('required', true);
                              $('#password-confirm').attr('required', true);
                              break;
                case"update":
                            $('.modal-title').html('Edit user');
                            $('#eid').show();
                            $('#password').attr('required', false);
                            $('#password-confirm').attr('required', false);
                            let id = $(this).attr("id");

                            $.post("/show-user", {"id":id})
                                .done(function (response) {
                                    let data = JSON.parse(response);
                                    $('#name').val(data.name);
                                    $('#password').val();
                                    $('#password-confirm').val();
                                    $('#email').val(data.email);
                                    $('#eid').val(id);
                                }).fail(function(result) {
                                    Swal.fire({
                                        type: 'error',
                                        title: 'Error',
                                        text: result.responseText,
                                    });
                                });

                            break;
                default:
                    return alert('Invalid action type');
            }

            $('#user-modal').modal('show');
        })

        $('#user-list-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "/user-list",
                type: "POST",
                error: function (data) {  // error handling
                    $('#datatable-error').html(JSON.stringify(data));
                }
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'created_at', name: 'created_at'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ]
        });

        $('#user-form').on('submit', function (e) {
            e.preventDefault();
            $('#user-btn').prop('disabled',  true);
            $("#user-btn").html('<span class="spinner-border spinner-border-sm text-light"></span>');

            let url = ($('#eid').val()) ? "/update-user" : "/store-user";

                $.post(url, $(this).serialize())
                    .done(function(result) {
                        Swal.fire({
                                    type: result.type,
                                    title: result.type,
                                    text: result.message,
                                    timer: 3000
                        });
                        ($('#eid').val()) ? $('#user-list-table').DataTable().ajax.reload(null, false) : $('#user-list-table').DataTable().ajax.reload();
                        $('#user-form')[0].reset();
                        $('#user-modal').modal('hide');
                        $("#user-btn").html('Save');
                        $('#user-btn').prop('disabled',  false);
                        $('#message').hide();
                    })
                    .fail(function(result) {
                        Swal.fire({
                            type: 'error',
                            title: 'Error',
                            text: result.responseText,
                        });
                        $("#user-btn").html('Save');
                        $("#user-btn").prop('disabled',  false);
                    });

        });

        $(document).on('click', '.delete', function(){
            var id = $(this).attr("id");

            Swal.fire({
                title: 'Are you sure to delete this record?',
                text: "continue?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Continue!'
            }).then((willDelete) => {
                    if (willDelete.value) {
                        $.post("/delete-user", {"id":id})
                            .done(function(result) {
                                Swal.fire({
                                    type: result.type,
                                    title: result.type,
                                    text: result.message,
                                    timer: 3000
                                });
                                $('#user-list-table').DataTable().ajax.reload(null, false);
                            })
                            .fail(function(result) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Error',
                                    text: result.responseText,
                                    // timer: 3000
                                });
                            });
                    } else {
                        swal("Message", "Action has been cancel", "info", {button: "Close"})
                    }
                });
        });
    </script>

@endsection