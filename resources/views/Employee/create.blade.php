@extends('layout.master')

@section('content')
   
     <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Employee</h4>
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
                <div class="button-items d-flex justify-content-end">
                    <a href="{{ route('employee.create') }}" type="button"
                        class="btn btn-info waves-effect waves-light">New</a>
                    <a href="{{ route('employee.index') }}" type="button"
                        class="btn btn-success waves-effect waves-light">List</a>
                </div>
            </div>
            <div class="card">
                <form action="{{ route('employee.store') }}" method="POST" autocomplete="off"
                    enctype="multipart/form-data" files="true">
                    @csrf
                    <div class="card-body ">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="name" class="col-form-label">Name</label> <br>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">
                               <div>
                                @if ($errors->has('name'))
                                <span class="text-danger">{{ "Add Your Name" }}</span>
                                @endif
                               </div>     
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="col-form-label">Email</label> <br>
                                <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control">
                               <div>
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ "Add Your Email" }}</span>
                                @endif
                                </div> 
                            </div>
                        </div>

                         <div class="form-group row">
                            <div class="col-md-6">
                                <label for="mobile" class="col-form-label">Mobile</label> <br>
                                <input type="number" name="mobile" id="mobile" value="{{ old('mobile') }}" class="form-control">
                               <div>
                                @if ($errors->has('mobile'))
                                <span class="text-danger">{{ "Add your Mobile" }}</span>
                                @endif
                                </div> 
                            </div>

                            
                                <div class="col-md-6">
                                <label for="address" class="col-form-label">Address</label> <br>
                                <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-control">
                               <div>
                                @if ($errors->has('address'))
                                <span class="text-danger">{{ "Add your Address" }}</span>
                                @endif
                                </div> 
                           
                            </div>    

                        </div>


                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="col-form-label">Project Thumbnail </label>
                                <div id="thumbnail" class="row"></div>
                                <div>
                                    @if ($errors->has('thumbnail'))
                                    <span class="text-danger">{{ $errors->first('thumbnail') }}</span>
                                @endif
                                </div>
                                <small class="small">Dimention: 1200*800</small>
                            </div>

                            <div class="col-md-6">
                                <label class="col-form-label">Project Images</label>
                                <div id="" class="row"></div>
                                <input type="file" name="multi_img[]" id="multi_img" multiple="" class="form-control">
                                 <div class="row" id="preview_img"></div>
                                <div>
                                    @if ($errors->has('image'))
                                    <span class="text-danger">{{ $errors->first('image') }}</span>
                                @endif
                                </div>
                                <small class="small">Dimention: 1200*800</small>
                            </div>
                        </div>

                    <div class="form-group row">
                        <div class="col-md-1">
                            <div class="button-items float-right">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script>
        $(document).ready(function() {
            let i = 1;
            $(".add-field-btn").on('click', function() {
                $('.new-field').append(`<div class="input-group mb-1 file-field add-input-${i}">
                                    <input type="file" class="form-control" name="file[${i}]">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="file_status[${i}]" aria-label="Checkbox for following text input">
                                        </div>
                                        <button type="button" class="btn btn-danger" onclick="removeInputFile(${i})"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                </div>`);
                i += 1;
            });
        });

        function removeInputFile(number) {
            $(`.add-input-${number}`).remove();
        }

          $(document).ready(function () {
        $('#multi_img').on('change', function () {
            if(window.File && window.FileReader && window.FileList && window.Blob)
            {
                var data = $(this)[0].files;
                $.each(data, function (index, file) { 
                     if(/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)){
                         var fRead = new FileReader();
                         fRead.onload = (function(file){
                             return function(e){
                                 var img = $('<img/>').addClass('thumb').attr('src', e.target.result).width(80).height(80);
                                 $('#preview_img').append(img);
                             }
                         })(file)
                         fRead.readAsDataURL(file);
                     }
                });
            } 
            else{
                alert("Your browser doesn't support file API")
            }
            
        });
    });  


        $("#thumbnail").spartanMultiImagePicker({
            fieldName: 'thumbnail',
            maxCount: 1,
            rowHeight: '150px',
            groupClassName: 'col-md-6 col-sm-6 col-xs-6',
            maxFileSize: '',
            dropFileLabel: "Drop Here",
            allowedExt: 'png|jpg|jpeg|webp',
            onExtensionErr: function(index, file) {
                console.log(index, file, 'extension err');
                alert('Please only input png or jpg type file')
            },
            onSizeErr: function(index, file) {
                console.log(index, file, 'file size too big');
                alert('File size too big');
            }
        });

    </script>

@endsection