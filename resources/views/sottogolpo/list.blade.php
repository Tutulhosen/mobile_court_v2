@extends('layout.app')
@yield('style')
 
@section('content')

<div class="container">
    <div class="row">
     <div class="col-lg-12">
        
        <div class="card card-custom">
            <div class="card-header smx">
                <h2 class="card-title ">খবর খবরের এর তালিকা</h2>
            </div>
            <div class="card-body p-15 cpv">
              <table class="table table-bordered table-striped m-0">
                <thead>
                    <tr>
                        <th class="wide-85">খবর</th>
                        <th class="wide-15">কার্যক্রম</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        
                        
                        <td class="text-left">
                            <dl class="m-0 p-5">
                                <dt class="text-primary">ঢাকা কারাগারে প্রতিশ্রুত প্রকল্প বাস্তবায়নের দাবি ঢাকা সমিতির <p class="small text-muted">তারিখ: 2016-09-04</p></dt>
                                <dd>রোববার জাতীয় প্রেস ক্লাবে এক সংবাদ সম্মেলনে প্রধানমন্ত্রীর প্রতিশ্রুতি অনুযায়ী দ্রুত কাজ শুরুর দাবি জানিয়ে তারা বলেন, ঢাকার মানুষ ২০১৮ সালের মধ্যে এসব কাজের দৃশ্যমান অগ্রগতি দেখতে চায়।

                                    দুইশ বছরের পুরনো ঢাকা কেন্দ্রীয় কারাগার গত ২৯ জুলাই কেরানীগঞ্জের রাজেন্দ্রপুরে সরে যায়।

                                    কর্তৃপক্ষ ১৮ একরের ওপর পুরনো কারাগারটিকে বিনোদনকেন্দ্রে রূপান্তরের পরিকল্পনা নিয়েছে, যেখানে থাকবে পার্ক, জাদুঘর, কনভেনশন সেন্টার, উন্মুক্ত নাট্যমঞ্চ ছাড়াও বিনোদনের নানা ব্যবস্থা। ঐতিহাসিক মূল্য আছে এমন ভবন সংরক্ষণের চিন্তাভাবনাও রয়েছে।

                                    তবে পুরনো কারাগারের স্থানে ছাত্রবাস নির্মাণের দাবি রয়েছে জগন্নাথ বিশ্ববিদ্যালয়ের শিক্ষার্থীদের।</dd>
                            </dl>
                        </td>
                        
                        <td>
                            <a href="/news/edit/7" class="btn btn-primary btn-block"><i class="fa fa-pencil"></i> দেখুন</a>                    <a href="/news/delete/7" class="btn btn-danger btn-block"><i class="fa fa-times"></i> বাতিল</a>                </td>
                    </tr>
                    </tbody>
              </table>
               
            </div>
 
           
        </div><!-- panel -->
        
     </div>

    </div>
</div>


@endsection

@section('scripts')

@endsection