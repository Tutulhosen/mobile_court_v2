@extends('layout.app')

@section('content')

<div class="row">

<div class="col-md-12">
 
</div>
    <div class="col-2"></div>
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <h1 style="text-align: center">অধিক্ষেত্র নির্ধারণ করুন</h1>
                <form action="{{ route('jurisdiction.store') }}" method="POST" id="case_mapping_form">
                    @csrf
                    @if (globaluserInfo()->role_id != 25 && globaluserInfo()->role_id !=26)
                        <div class="magistrate_list">
                            <div class="form-group">
                                <label for="">ইউজার নির্বাচন করুনঃ</label>
                                <select name="user_select" id="user_select" class="form-control">
                                    <option value="">--নির্বাচন করুন--</option>
                                    @foreach ($all_user as $item)
                                        <option value="{{$item->username}}">{{$item->name}}, {{roleName($item->role_id)->role_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                   
                    <div class="upzill_list">
                        <table class="table table-hover mb-6 font-size-h6">
                            <thead class="thead-light">
                                <tr>
                                    <!-- <th scope="col" width="30">#</th> -->
                                    <th scope="col">
                                        সিলেক্ট করুণ
                                    </th>
                                    <th scope="col">উপজেলার নাম</th>
        
                                </tr>
                            </thead>
                            @if (globaluserInfo()->role_id != 25 && globaluserInfo()->role_id !=26)
                            <tbody>
                                @foreach ($upzillas as $upzilla)
                                    <tr>
                                        <td>
                                            <div class="checkbox-inline">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="upzilla_mapping[]"
                                                        value="{{ $upzilla->id }}"
                                                        class="check_upzilla" />
                                                    <span></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>{{ $upzilla->upazila_name_bn }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                            @else
                                <tbody>
                                    @foreach ($upzillas as $upzilla)
                                        <tr>
                                            <td>
                                                <div class="checkbox-inline">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="upzilla_mapping[]"
                                                            value="{{ $upzilla->id }}"
                                                            class="check_upzilla"
                                                            @if(in_array($upzilla->id, $selected_upazilas)) checked @endif />
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td> {{ $upzilla->upazila_name_bn }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endif
                            
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="button" id="submitBtn" class="btn btn-primary">নিশ্চিত করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-2"></div>
</div>


@endsection

@section('scripts')
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

</script>
<script>
    $(document).ready(function() {
        $('#submitBtn').on('click', function(e) {
            e.preventDefault();

            var formData = $('#case_mapping_form').serialize(); 

            $.ajax({
                url: "{{ route('jurisdiction.store') }}", 
                type: 'POST',
                data: formData,
                success: function(response) {

                    if (response.success) {
                        toastr.success('Data saved successfully!', 'Success');
                    } else {
                        toastr.error('Select A User.', 'Error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    toastr.error('An error occurred. Please try again.', 'Error');
                }
            });
        });
       
        $('#user_select').on('change', function(){
            let username = $(this).val();

            $.ajax({
                url: "{{ route('check.user.permission') }}", 
                type: 'GET',
                data: { 'username': username },
                success: function(response) {
                    // Uncheck all checkboxes initially
                    $('.check_upzilla').prop('checked', false);

                    // If there are upazilas in upa_id_arr, check the corresponding checkboxes
                    if (response.upa_id_arr.length > 0) {
                        response.upa_id_arr.forEach(function(id) {
                            $('input[value="'+id+'"].check_upzilla').prop('checked', true);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                    toastr.error('An error occurred while fetching the user data.', 'Error');
                }
            });
        });

    });
</script>

    
@endsection