@extends('layout.app')

@section('content')
    <script src="{{ asset('js/prosecution/extOrderSheet.js') }}" type="text/javascript"></script>

    <script>
        function createConfession() {
            $("#confirmModal_conf").modal('show');
        }
    </script>
    <?php
    $roleID = globalUserInfo()->role_id;
    ?>
    <div class="container" style="border: 1px solid rgb(178, 192, 174); box-shadow: 0 0px 2px 0 rgba(85, 185, 45, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom">

                    <div class="panel panel-default">
                        <div class="card-header p-10 smx">
                            <h2 class="card-title" style="margin: 0%">অনুসন্ধান</h2>
                        </div>

                        <div class="card-body cpv p-15">
                            <form method="get" action="{{ route('showForms.mc') }}">

                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="radio-inline d-flex align-items-center my-2">
                                                <input class="radio-input" type="radio" name="searchCriteria"
                                                    value="is_case" onclick="caseInformation.clearDateMagistrate()" />
                                                <span class="ml-2">মামলার নম্বর</span>
                                            </div>
                                            <!-- <label class="control-label">মামলার নম্বর</label> -->
                                            <input type="text" id="case_no" class="form-control" name="case_no" />
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <div class="radio-inline d-flex align-items-center my-2">
                                                <input class="radio-input" type="radio" name="searchCriteria"
                                                    value="is_date" onclick="caseInformation.clearCaseMagistrate()" />
                                                <span class="ml-2">তারিখ</span>
                                            </div>
                                            <!-- <label class="control-label">তারিখ</label> -->
                                            <div class="row">
                                                <div class="col-sm-6 m-bottom-15">
                                                    <input class="input form-control" name="start_date" id="start_date"
                                                        type="text" placeholder="প্রথম তারিখ" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <input class="input form-control" name="end_date" id="end_date"
                                                        type="text" placeholder="শেষ তারিখ" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($roleID == 37 || $roleID == 38)
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <div class="radio-inline d-flex align-items-center my-2">
                                                    <input class="radio-input" type="radio" name="searchCriteria"
                                                        value="is_magistrate" onclick="caseInformation.clearCaseDate()" />
                                                    <span class="ml-2">এক্সিকিউটিভ ম্যাজিস্ট্রেট অনুযায়ী</span>
                                                </div>
                                                <select name="magistrate_id" class="form-control">
                                                    <option value="">বাছাই করুন...</option>
                                                    @foreach ($magistrates as $magistrate)
                                                        <option value="{{ $magistrate->id }}">{{ $magistrate->name_eng }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="pull-right float-right">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="glyphicon glyphicon-ok"></i> অনুসন্ধান
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-header smx">
                            <h2 class="panel-title"> মামলার তালিকা <span style="color:#FF0000">{{ $searchCriteria }}</span>
                            </h2>
                        </div>

                        <div class="card-body caseList">

                            <table class="table table-bordered m-0" align="center">
                                <thead style="background: green; color:white">
                                    <tr>
                                        <th> মামলাসমূহ </th>
                                        <th>কার্যক্রম</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($prosecutions as $case)
                                        <tr>
                                            <td class="p-10">
                                                <ul class="list-inline">
                                                    <li><b class="font-color">মামলা নম্বর</b>: {{ $case->case_no }}</li>
                                                    <li><b class="font-color">তারিখ</b>: {{ $case->prosecution_date }}</li>
                                                    <li><b class="font-color">ঘটনাস্থল</b>: {{ $case->location }}</li>
                                                    @if ($case->is_suomotu == '0')
                                                        <li><b class="font-color">প্রসিকিউটর</b>:
                                                            {{ $case->prosecutor_name }}</li>
                                                    @else
                                                        <li><b class="font-color">আদালতের নাম</b>:
                                                            {{ $case->prosecutor_name }}</li>
                                                    @endif
                                                </ul>
                                                <hr>
                                                <b class="font-color">অভিযোগ</b>: @if ($case->hasCriminal == 0)
                                                    <b>( আসামি ছাড়া )</b>
                                                @endif
                                                @php
                                                    $subjectArray = json_decode($case->subject, true);
                                                    if (json_last_error() === JSON_ERROR_NONE) {
                                                        foreach ($subjectArray as $subject) {
                                                            echo $subject . '<br/>';
                                                        }
                                                    } else {
                                                        echo $case->subject;
                                                    }
                                                @endphp
                                            </td>
                                            <td width="12%">
                                                <a href="{{ route('proceutions.printForms', ['id' => $case->id]) }}"
                                                    class="btn btn-block btn-warning">নথি প্রিন্ট</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center">
                                {!! $prosecutions->links('pagination::bootstrap-4') !!}
                            </div>


                        </div>
                    </div>

                    <div class="modal fade" id="confirmModal_conf" tabindex="-1" role="dialog"
                        aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h3>আসামির জবানবন্দি নাই ।</h3>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">বন্ধ করুন</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="messageModal">
        {{-- @include('partials.message') --}}
    </div>


@endsection

@section('scripts')
    <script>
        function showSubject(value) {
            alert(value);
        }
        $('#start_date').datepicker({
            dateFormat: 'yy/mm/dd'
        });
        $('#end_date').datepicker({
            dateFormat: 'yy/mm/dd'
        });
    </script>
    <script src="{{ asset('mobile_court/javascripts/source/register/DataTables-1.10.15/media/js/jquery.dataTables.js') }}">
    </script>
    <script src="{{ asset('mobile_court/js/select2.min.js') }}"></script>
    <script src="{{ asset('mobile_court/javascripts/source/prosecution/searchCaseInformation.js') }}"></script>
@endsection

@section('styles')
    <link href="{{ asset('mobile_court/cssmc/jquery-ui-1.11.0.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile_court/cssmc/select2.css') }}" rel="stylesheet">
@endsection
