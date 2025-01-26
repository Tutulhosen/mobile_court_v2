@extends('layout.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading smx">
        <h2 class="panel-title">প্রসিকিউশনের তালিকা ( আসামি ছাড়া )</h2>
    </div>
    <div class="panel-body cpv">
        @if($prosecutions->count())
            <table class="table table-bordered table-striped table-info mb30" align="center">
                <thead>
                <tr>
                    <th>নম্বর</th>
                    <th>প্রসিকিউটর</th>
                    <th>অভিযোগ</th>
                    <th>আদালতের তারিখ</th>
                    <th>ঘটনার তারিখ</th>
                    <th>ঘটনাস্থল</th>
                    <th>কার্যক্রম</th>
                </tr>
                </thead>
                <tbody>
                @foreach($prosecutions as $prosecution)
                    <tr>
                        <td>{{ $prosecution->case_no }}</td>
                        <td>{{ $prosecution->prosecutor_name }}</td>
                        
                        <td>
                            @php
                                $subject = json_decode($prosecution->subject, true);
                                if (json_last_error() !== JSON_ERROR_NONE) {
                                    $subject = [$prosecution->subject];
                                }
                            @endphp
                            @foreach($subject as $sub)
                                {{ $sub }}<br>
                            @endforeach
                        </td>

                        <td>{{ $prosecution->courtdate }}</td>
                        <td>{{ $prosecution->date }}</td>
                        <td>{{ $prosecution->location }}</td>

                        @if($prosecution->status == 2)
                            <td width="12%">কোর্টবন্ধ।</td>
                        @else
                            <td width="12%">
                                <a href="{{ route('prosecution.newComplain', $prosecution->id) }}" class="btn btn-warning">অভিযোগ গঠন</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
              
            </table>

            <div class="d-flex justify-content-center">
                {!! $prosecutions->links('pagination::bootstrap-4') !!}

            </div>


        @else
            <p>No citizen complaints are recorded.</p>
        @endif
    </div><!-- panel-body -->
</div><!-- panel -->
@endsection
