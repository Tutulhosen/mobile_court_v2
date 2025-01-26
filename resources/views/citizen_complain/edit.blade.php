@extends('layout.app')
@section('content')
{{-- Display Flash Messages --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Form Start --}}
<form action="{{ route('citizen_complain.save') }}" method="POST">
    @csrf

    <table width="100%" class="outter">
        <tr>
            <td>
                <table class="text" border="0" cellpadding="4" cellspacing="3" width="100%">
                    <tr>
                        <td colspan="2" class="formHeading">বিস্তারিত অভিযোগ</td>
                    </tr>
                    <tr height="10px">
                        <td colspan="2"></td>
                    </tr>

                    <tr>
                        <td align="left" bgcolor="#E0F0E8" width="32%">নাম (বাংলা)</td>
                        <td align="left" bgcolor="#E0F0E8" width="32%">নাম (ইংরেজি)</td>
                    </tr>

                    <tr>
                        <td align="left">
                            <input type="text" name="name_bng" class="input" value="{{ old('name_bng') }}">
                        </td>
                        <td align="left">
                            <input type="text" name="name_eng" class="input" value="">
                        </td>
                    </tr>

                    <tr>
                        <td align="left" bgcolor="#E0F0E8" width="32%">Complain Of Details</td>
                        <td align="left" bgcolor="#E0F0E8" width="32%">Complain Of Date</td>
                    </tr>

                    <tr>
                        <td align="left">
                            <textarea name="complain_details" cols="50" rows="4" class="input">{{ old('complain_details') }}</textarea>
                        </td>
                        <td align="left">
                            <input type="text" name="complain_date" class="input" value="{{ old('complain_date') }}">
                        </td>
                    </tr>

                    <tr>
                        <td align="left" bgcolor="#E0F0E8" width="32%">Location</td>
                        <td align="left" bgcolor="#E0F0E8" width="32%">District</td>
                    </tr>

                    <tr>
                        <td align="left">
                            <input type="text" name="location" class="input" value="{{ old('location') }}">
                        </td>
                        <td align="left">
                            <input type="text" name="district_id" class="input" value="{{ old('district_id') }}">
                        </td>
                    </tr>

                    <tr>
                        <td align="left" bgcolor="#E0F0E8" width="32%">Feedback</td>
                        <td align="left" bgcolor="#E0F0E8" width="32%">Complain Of Status</td>
                    </tr>

                    <tr>
                        <td align="left">
                            <textarea name="feedback" cols="50" rows="4" class="input">{{ old('feedback') }}</textarea>
                        </td>
                        <td align="left">
                            <select name="complain_status" class="input">
                                <option value="1">ভবিষ্যতে  আমলে নেয়া হবে</option>
                                <option value="2">পরিত্যাক্ত</option>
                                <option value="3">আদালত গঠন করা হউক</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="hidden" name="id" value="{{ old('id') }}">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td align="right"><a href="{{ route('home.index') }}">প্রথম পাতা</a></td>
                        <td align="right"><a href="{{ route('complain.new') }}">আদালত গঠন</a></td>
                        <td align="middle">
                            <button type="submit">সংরক্ষণ</button>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
@endsection
