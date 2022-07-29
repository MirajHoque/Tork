@extends('layout.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Employee List</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Employee</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
                <div class="py-1">
                    <span id="multielement" class="text-danger"></span>
                    <div class="button-items d-flex justify-content-end">
                        <a href="{{ route('employee.create') }}" type="button"
                            class="btn btn-info waves-effect waves-light">New</a>
                    </div>
                </div>
                <div class="py-1">
                    <div class="button-items d-flex justify-content-start">
                        <button type="button" class="btn btn-danger" onclick="deleteMultiple()">Delete Selted</button>
                    </div>
                </div>
               

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach ($employees as $key => $element)
                                    <tr>
                                        <td scope="row">
                                            <input type="checkbox" class="m-4 text-center" name="empcheckbox" id="empcheckbox"
                                            value="{{ $element->id }}">
                                        </td>
                                        <td><img src="{{ asset($element->thumbnail_image) }}" alt="" class="img-thumbnail"
                                                style="max-height: 100px"></td>
                                        <td>{{ $element->name }}</td>
                                        <td>{{ $element->mobile }}</td>
                                        <td>{{ $element->email }}</td>

                                        <td>
                                            <div class="btn-group btn-group-sm mt-2" role="group"
                                                aria-label="Basic example">
                                               
                                                    <a class="pr-3" href="{{ route('employee.edit', $element->id) }}"
                                                        class="btn btn-warning btn-sm" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                               

                                                    {!! Form::open(['route' => ['employee.destroy', $element->id], 'method'=>'DELETE']) !!}
                                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                                    {!! Form::close() !!}

                                            </div>
                                        </td>
                                    </tr>
                                 @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    {{-- modal --}}
    <div class="col-sm-6 col-md-4 col-xl-3">
        <div class="modal fade " id="dataModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="get_data">

                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')

    <script>

        //sweetalert2
       var alertMsg = Swal.mixin({
            toast: true,
            position: 'top-end',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          })

          
       $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function deleteMultiple(){
            let ids = [];
            $("input:checkbox[name=empcheckbox]:checked").each(function(){
                ids.push($(this).val());
            });

            $.ajax({
                type: "post",
                url: "delete/multiple-employee",
                data: {ids: ids},
                dataType: "json",
                success: function (res) {
                    if(res.status === 200){
                        alertMsg.fire({
                      'title': 'Successfully Deleted'
                    });
                    window.location = res.redirect_url;
                    }
                    else{
                        $("#multielement").text(res.msg);
                    }
                }
            });
        }
       
    </script>
@endsection
