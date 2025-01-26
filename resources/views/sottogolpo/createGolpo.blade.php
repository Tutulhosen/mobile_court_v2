@extends('layout.app')
@yield('style')
 
@section('content')
<style>
    .form-group .control-label{
        background-color: #4c81db;
        width: 100%;
        padding-left: 5px;
    }

    .control-label {
     color: #fff !important;
    display: block;
    padding-left: 5px;
    font-weight: normal;
    }
</style>
<div class="container">
    <div class="row">
     <div class="col-lg-12">
        
        <div class="card card-custom">
            <div class="card-header smx">
                <h2 class="card-title ">গল্প</h2>
            </div>
            <div class="card-body p-15 cpv">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="control-label">গল্পের নাম <span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" class="input form-control" required="true">                </div>
                        <!-- form-group -->
                    </div>
                </div>
                <!-- row -->
                <div class="row" id="content">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="control-label">বিস্তারিত <span class="text-danger">*</span></label>
                                <textarea class="ckeditor form-control" name="wysiwyg-editor"></textarea>
                             
                            </div>
                        </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label class="control-label">গল্পের মূলবিষয়(স্থানের নাম , ঘটনা ) <span class="text-danger">*</span></label>
                            <input type="text" id="keyword" name="keyword" class="input form-control" required="true">                
                        </div>
                        <!-- form-group -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                       <div class="form-group">
                            <label class="control-label">আইন <span class="text-danger">*</span></label>
                            <select class="form-control">         
                                <option value="">বাছাই করুন...</option>
                                <option value="8">ধুমপান ও তামাকজাত দ্রব্য ব্যবহার (নিয়ন্ত্রণ) আইন, ২০০৫</option>
                                <option value="10">বিশুদ্ধ খাদ্য অধ্যাদেশ, ১৯৫৯</option>
                                <option value="11">ইট প্রস্তুত ও ভাটা স্থাপন (নিয়ন্ত্রণ) আইন, ২০১৩</option>
                                <option value="12">মোটরযান অধ্যাদেশ, ১৯৮৩</option>
                                <option value="13">পাবলিক পরীক্ষাসমূহ (অপরাধ) আইন, ১৯৮০</option>
                                <option value="15">দন্ডবিধি, ১৮৬০</option>
                                <option value="16">মেডিকেল প্র্যাকটিস এবং বেসরকারী ক্লিনিক ও ল্যাবরেটরী (নিয়ন্ত্রণ) অধ্যাদেশ, ১৯৮২</option>
                                <option value="17">ড্রাগ (নিয়ন্ত্রণ) অধ্যাদেশ, ১৯৮২</option>
                                <option value="18">এসিড নিয়ন্ত্রণ আইন, ২০০২</option>
                                <option value="19">ভোক্তা-অধিকার সংরক্ষণ আইন, ২০০৯</option>
                                <option value="20">বাংলাদেশ হোটেল ও রেস্টুরেন্ট অধ্যাদেশ, ১৯৮২</option>
                                <option value="23">ছাপাখানা এবং প্রকাশনা (ঘোষণা ও নিবন্ধন) অধ্যাদেশ, ১৯৭৩</option>
                                <option value="24">বাংলাদেশ জাতীয় সঙ্গীত , পতাকা প্রতীক অধ্যাদেশ, ১৯৭২ </option>
                                <option value="25">বাংলাদেশ পরিবেশ সংরক্ষণ আইন, ১৯৯৫</option>
                                <option value="26">বংগীয়  প্রকাশ্য জুয়া আইন-১৮৬৭</option>
                                <option value="27">মৎস্য রক্ষা ও সংরক্ষণ আইন, ১৯৫০</option>
                                <option value="28">পশু জবাই ও মাংসের মান নিয়ন্ত্রণ আইন, ২০১১</option>
                                <option value="29">নোট বই নিষিদ্ধকরণ আইন-১৯৮০</option>
                                <option value="30">বাংলাদেশ স্টান্ডার্ডস ও টেস্টিং ইনস্টিটিউশন অধ্যাদেশ-১৯৮৫ ও বিএসটিআই (এমেন্ডমেন্ট) এ্যাক্ট, ২০০৩</option>
                                <option value="32">সার (ব্যবস্থাপনা) আইন, ২০০৬</option>
                                <option value="33">দি স্ট্যান্ডার্ডস অব ওয়েটস এন্ড মেজার্স অর্ডিন্যান্স, ১৯৮২</option>
                                <option value="34">মাছ এবং মাছ জাত দ্রব্য (পরিদর্শন এবং মান নিয়ন্ত্রণ) অধ্যাদেশ,১৯৮৩</option>
                                <option value="35">দেওয়াল লিখন ও পোস্টার লাগানো (নিয়ন্ত্রণ) আইন, ২০১২</option>
                                <option value="36">খেয়া আইন, ১৮৮৫</option>
                                <option value="37">বালু মহালও মাটি ব্যবস্থাপনা আইন, ২০১০</option>
                                <option value="38">বাল্য বিবাহ নিরোধ আইন, ১৯২৯</option>
                                <option value="39">মাদক দ্রব্য নিয়ন্ত্রণ আইন, ১৯৯০</option>
                                <option value="40">অত্যাবশ্যকীয় পণ্য নিয়ন্ত্রণ আইন, ১৯৫৬</option>
                                <option value="42">উপজেলা পরিষদ আইন, ১৯৯৮</option>
                                <option value="43">স্থানীয় সরকার (পৌরসভা ) আইন, ২০০৯</option>
                                <option value="44">স্থানীয় সরকার (সিটি কর্পোরেশন) আইন, ২০০৯</option>
                                <option value="45">কিশোর ধূমপান আইন,১৯১৯</option>
                                <option value="46">টাউট আইন,১৮৭৯</option>
                                <option value="47">সরকারি এবং স্থানীয় কর্তৃপক্ষীয় ভূমি ও ইমারত (দখল পুনরুদ্ধার) আদেশ,১৯৭০</option>
                                <option value="48">ইমারত নির্মাণ আইন, ১৯৫২</option>
                                <option value="49">স্থানীয় সরকার (ইউনিয়ন পরিষদ) আইন, ২০০৯</option>
                                <option value="50">অগ্নি প্রতিরোধ ও নির্বাপণ আইন, ২০০৩</option>
                                <option value="51">মহানগরী, বিভাগীয় শহর ও জেলা শহরের পৌর এলাকাসহ দেশের সকল পৌর এলাকার খেলার মাঠ, উন্মুক্ত স্থান, উদ্যান এবং প্রাকৃতিক জলাধার সংরক্ষণ আইন, ২০০০</option>
                                <option value="52">রাষ্ট্রীয় জলসীমা এবং সামুদ্রিক অঞ্চল আইন, ১৯৭৪</option>
                                <option value="53">মানবদেহে অঙ্গ-প্রত্যঙ্গ সংযোজন আইন-১৯৯৯</option>
                                <option value="54">জন্ম ও মৃত্যু নিবন্ধন আইন, ২০০৪</option>
                                <option value="55">সিনেমাটোগ্রাফ আইন, ১৯১৮</option>
                                <option value="56">বন্দর রক্ষা (বিশেষ ব্যবস্থা)আইন-১৯৪৮</option>
                                <option value="57">সরাই আইন ১৮৬৭</option>
                                <option value="58">করাত-কল (লাইসেন্স) বিধিমালা, ২০১২</option>
                                <option value="59">আয়োডিন অভাবজনিত রোগ প্রতিরোধ আইন, ১৯৮৯</option>
                                <option value="60">কেবল টেলিভিশন নেটওয়ার্ক পরিচালনা আইন, ২০০৬</option>
                                <option value="61">রিয়েল এস্টেট উন্নয়ন ও ব্যবস্থাপনা আইন, ২০১০</option>
                                <option value="62">বাংলাদেশ এ্যাক্রেডিটেশন আইন, ২০০৬ </option>
                                <option value="64">ঔষধ(ড্রাগ) আইন, ১৯৪০</option>
                                <option value="65">চলচ্চিত্রের সেন্সরসীপ আইন, ১৯৬৩</option>
                                <option value="66">পণ্যে পাটজাত মোড়কের বাধ্যতামূলক ব্যবহার আইন, ২০১০</option>
                                <option value="67">অভ্যন্তরীণ নৌ-চলাচল অধ্যাদেশ, ১৯৭৬</option>
                                <option value="68">পাইলটেজ অধ্যাদেশ, ১৯৬৯</option>
                                <option value="69">নিরাপদ রক্ত পরিসঞ্চালন আইন, ২০০২</option>
                                <option value="70">দুর্যোগ ব্যবস্থাপনা আইন, ২০১২</option>
                                <option value="71">গণপ্রতিনিধিত্ব আদেশ, ১৯৭২</option>
                                <option value="72">বীজ অধ্যাদেশ, ১৯৭৭</option>
                                <option value="73">কৃষিপণ্য বাজার নিয়ন্ত্রণ আইন, ১৯৬৪</option>
                                <option value="74">বন্যপ্রাণী সংরক্ষণ ও নিরাপত্তা আইন, ২০১২</option>
                                <option value="75">বাংলাদেশ শ্রম আইন, ২০০৬</option>
                                <option value="76">কীটনাশক অধ্যাদেশ, ১৯৭১</option>
                                <option value="77">বন আইন, ১৯২৭</option>
                                <option value="78">বাংলাদেশ গ্যাস আইন, ২০১০</option>
                                <option value="79">পেট্রোলিয়াম আইন, ১৯৩৪</option>
                                <option value="80">বেসামরিক বিমান অধ্যাদেশ, ১৯৬০</option>
                                <option value="82">মাতৃদুগ্ধ বিকল্প, শিশু খাদ্য, বাণিজ্যিকভাবে প্রস্তুতকৃত শিশুর বাড়তি খাদ্য ও উহা ব্যবহারের সরঞ্জামাদি (বিপণন নিয়ন্ত্রণ) আইন, ২০১৩</option>
                                <option value="83">অস্থাবর সম্পত্তি হুকুমদখল আইন, ১৯৮৮</option>
                                <option value="84">বৈদেশিক কর্মসস্থান ও অভিবাসী আইন, ২০১৩</option>
                                <option value="86">রেলওয়ে আইন, ১৮৯০</option>
                                <option value="87">পাট অধ্যাদেশ, ১৯৬২</option>
                                <option value="88">বিদ্যুৎ আইন, ১৯১০</option>
                                <option value="89">বাংলাদেশে বাণিজ্যিক নৌ চলাচল অধ্যাদেশ, ১৯৮৩</option>
                                <option value="90">পানি সরবরাহ ও পয়ঃনিষ্কাশন কর্তৃপক্ষ আইন, ১৯৯৬</option>
                                <option value="91">বাংলাদেশ হোমিওপ্যাথিক প্র্যাকটিশনার অধ্যাদেশ, ১৯৮৩</option>
                                <option value="92">কন্ট্রোল অব এন্ট্রি এ্যাক্ট, ১৯৫২</option>
                                <option value="93">ক্রিমিনাল ল’ (শিল্প এলাকা) এ্যামেন্ডমেন্ট এ্যাক্ট, ১৯৪২</option>
                                <option value="94">বঙ্গীয় ভবঘুরে আইন, ১৯৪৩</option>
                                <option value="95">বঙ্গীয় সর্বসাধারণের চিত্তবিনোদন স্থান আইন, ১৯৩৩</option>
                                <option value="97">বাংলাদেশ ইউনানী এবং আয়ুর্বেদিক প্র্যাকটিশনারস অধ্যাদেশ, ১৯৮৩</option>
                                <option value="98">পশু নির্যাতন আইন, ১৯২০</option>
                                <option value="99">মৎস্য হ্যাচারি আইন, ২০১০</option>
                                <option value="100">বন্দর আইন, ১৯০৮</option>
                                <option value="101">বিষ আইন, ১৯১৯</option>
                                <option value="102">পাসপোর্ট আইন, ১৯২০</option>
                                <option value="103">ক্যান্টনমেন্ট আইন, ১৯২৪</option>
                                <option value="104">মৎস্যখাদ্য ও পশুখাদ্য আইন, ২০১০</option>
                                <option value="105">মেডিকেল ও ডেন্টাল কাউন্সিল আইন, ২০১০</option>
                                <option value="106">চট্টগ্রাম বন্দর কর্তৃপক্ষ অধ্যাদেশ, ১৯৭৬</option>
                                <option value="107">বিমান-নিরাপত্তা বিরোধী অপরাধ দমন আইন, ১৯৯৭</option>
                                <option value="108">মংলা বন্দর কর্তৃপক্ষ অধ্যাদেশ, ১৯৭৬</option>
                                <option value="109">নিরাপদ খাদ্য আইন, ২০১৩</option>
                                <option value="110">সিটি কর্পোরেশন নির্বাচন (ইলেকট্রনিক ভোটিং মেশিন) বিধিমালা, ২০১০  </option>
                                <option value="111">স্থানীয় সরকার(সিটি কর্পোরেশন) নির্বাচন বিধিমালা, ২০১০</option>
                                <option value="115">পৌরসভা (নির্বাচন আচরণ) বিধিমালা, ২০১৫</option>
                                <option value="117">বাংলাদেশ হোটেল ও রেস্তোঁরা আইন, ২০১৪</option>
                                <option value="118">ইউনিয়ন পরিষদ (নির্বাচন আচারণ) বিধিমালা ২০১৬</option>
                                <option value="119">হাইওয়ে আইন, ১৯২৫</option>
                                <option value="122">ভোজ্যতেলে ভিটামিন ‘এ’ সমৃদ্ধকরণ আইন, ২০১৩</option>
                                <option value="128">জেলা পরিষদ (নির্বাচন আচরণ) বিধিমালা, ২০১৬</option>
                                <option value="132">শব্দদূষণ (নিয়ন্ত্রণ) বিধিমালা, ২০০৬</option>
                                <option value="135">বাংলাদেশ ট্রাভেল এজেন্সি (নিবন্ধন ও নিয়ন্ত্রণ) আইন, ২০১৩</option>
                                <option value="138">খনি ও খনিজ সম্পদ (নিয়ন্ত্রণ ও উন্নয়ন) আইন, ১৯৯২</option>
                                <option value="140">উপজেলা পরিষদ (নির্বাচন আচরণ) বিধিমালা ২০১৬</option>
                                <option value="143">সিটি কর্পোরেশন (নির্বাচন আচরণ) বিধিমালা, ২০১৬</option>
                                <option value="144">রাজনৈতিক দল ও প্রার্থীর আচরণ বিধিমালা, ২০০৮</option>
                                <option value="147">বাল্যবিবাহ নিরোধ আইন, ২০১৭</option>
                            </select> 
                        </div> 
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label class="control-label">অপরাধের ধরন <span class="text-danger">*</span></label>
                            <select class="form-control">         
                            <option value="">বাছাই করুন...</option>
                            <option value="1">অন্যান্য</option>
                            <option value="2">মাদক</option>
                            <option value="3">পরিবেশ</option>
                            <option value="4">মৎস্য</option>
                            <option value="5">ইভটিজিং</option>
                            <option value="6">বাল্য বিবাহ</option>
                            <option value="7">দ্রব্য মূল্য </option>
                            <option value="8">ভূমি ও জলাশয়</option>
                            <option value="9">পরিবহন ও যোগাযোগ</option>
                            <option value="10">ঔষধ ও চিকিৎসা</option>
                            <option value="11">শিক্ষা সংক্রান্ত </option>
                            <option value="12">নিম্নমানের খাদ্য ও প্রসাধনী </option>
                            <option value="13">বীজ ও সার</option>
                            <option value="14">নির্বাচন</option>
                            <option value="15">স্বাস্থ্য সংক্রান্ত</option>
                            <option value="18">লাইসেন্স সংক্রান্ত</option>
                            <option value="19">ভেজাল</option>
                            <option value="20">দূষিত খাদ্য</option>
                            <option value="21">অগ্নি প্রতিরোধ </option>
                            <option value="22">বিদ্যুৎ সংক্রান্ত</option>
                            <option value="25">পাটজাত দ্রব্য</option>
                            <option value="26">মেয়াদ উত্তীর্ণ পণ্য</option>
                            <option value="29">ওজনে কম</option>
                            <option value="32">প্যাকেটজাত পণ্য</option>
                            <option value="35">এসিড-সংক্রান্ত</option>
                            <option value="38">ধুমপান</option>
                            <option value="41">দুষিত রক্ত</option>
                            <option value="44">ভোজ্যতেল</option>
                            <option value="47"> জুয়া</option>
                            <option value="50">আয়োডিন বিষয়ক</option>
                            <option value="53">ইট প্রস্তুত ও ভাটা স্থাপন</option>
                            </select> 
                        </div> 
                    </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group">
                        <label class="control-label">প্রচ্ছদ ছবি  সংযুক্ত করুন  (সর্বোচ্চ ফাইল সাইজ
                        1MB) <span class="text-danger">*</span></label>
                        <div id="actions" class="row fileupload-buttonbar">
                           <div class="row">
                               <div class=" ml-10" style="margin-left:10px">
                                   <!-- The fileinput-button span is used to style the file input field as button -->
                                   <span class="btn btn-success fileinput-button dz-clickable">
                                     +
                                       <span>ছবি আপলোড</span>
                                    </span>

                               </div>
                               <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 d-none">
                                   <!-- The global file processing state -->
                                   <span class="fileupload-process">
										<div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success wide-0" data-dz-uploadprogress=""></div>
                                        </div>
									</span>
                               </div>
                           </div>
                    </div>
                    </div>
                  </div>
                </div>
 
                <div class="panel-footer">
                           
                    <div class="pull-right float-right">
                        <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-ok"></i> সংরক্ষণ</button>
                    </div>
                </div>
            </div><!-- panel -->
        
     </div>

    </div>
</div>


@endsection

@section('scripts')
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
        
    });
</script>
@endsection