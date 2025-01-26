<form action="/prosecution/createProsecution" id="suomotcourtform" name="suomotcourtform" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <h4  class="well well-sm mt-10 text-center" style="background-color:#085F00;color:#fff;padding: 10px;">স্থান ও সময় </h4>
                        </div><!-- form-group -->
                        <div class="row" id="withProsecutor">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><span style="color:#FF0000">*</span>ঘটনার তারিখ</label>
                                    <div class="input-group">
                                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                        <input class="suodate input required form-control hasDatepicker" name="suodate" id="suodate" value="" type="text">
                                    </div>
                                </div>    
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"><span style="color:#FF0000">*</span>ঘটনার সময়</label>
                                    <div class="input-group mb15">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                        <div class="bootstrap-timepicker"><input name="time" id="suo_timepickersuomoto" type="text" class="suo_timepickersuomoto input form-control required"><div class="bootstrap-timepicker-widget dropdown-menu"><table><tbody><tr><td><a href="#" data-action="incrementHour"><i class="glyphicon glyphicon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="incrementMinute"><i class="glyphicon glyphicon-chevron-up"></i></a></td></tr><tr><td><input type="text" name="hour" class="form-control bootstrap-timepicker-hour" maxlength="2"></td> <td class="separator">:</td><td><input type="text" name="minute" class="form-control bootstrap-timepicker-minute" maxlength="2"></td> </tr><tr><td><a href="#" data-action="decrementHour"><i class="glyphicon glyphicon-chevron-down"></i></a></td><td class="separator"></td><td><a href="#" data-action="decrementMinute"><i class="glyphicon glyphicon-chevron-down"></i></a></td></tr></tbody></table></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="withOutProsecutor">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><span style="color:#FF0000">*</span>ঘটনার তারিখ</label>
                                        <div class="input-group">
                                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                            <input onchange="complaintForm.setCaseNumberAccordingToCaseDate();" class="suodate input required form-control hasDatepicker" name="suodate" id="suodate" value="" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><span style="color:#FF0000">*</span>ঘটনার সময়</label>
                                        <div class="input-group mb15">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            <div class="bootstrap-timepicker"><input name="time" id="suo_timepickersuomoto" type="text" class="suo_timepickersuomoto input form-control required"><div class="bootstrap-timepicker-widget dropdown-menu"><table><tbody><tr><td><a href="#" data-action="incrementHour"><i class="glyphicon glyphicon-chevron-up"></i></a></td><td class="separator">&nbsp;</td><td><a href="#" data-action="incrementMinute"><i class="glyphicon glyphicon-chevron-up"></i></a></td></tr><tr><td><input type="text" name="hour" class="form-control bootstrap-timepicker-hour" maxlength="2"></td> <td class="separator">:</td><td><input type="text" name="minute" class="form-control bootstrap-timepicker-minute" maxlength="2"></td> </tr><tr><td><a href="#" data-action="decrementHour"><i class="glyphicon glyphicon-chevron-down"></i></a></td><td class="separator"></td><td><a href="#" data-action="decrementMinute"><i class="glyphicon glyphicon-chevron-down"></i></a></td></tr></tbody></table></div></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label"><span style="color:#FF0000">*</span> মামলা নম্বর</label>
                                        <input type="text" readonly="" id="case_no" name="case_no" class="input form-control required" required="true">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label style="font-size: medium" class="control-label">মামলা নম্বরের সিরিয়াল (প্রযোজ্য হলে)</label>
                                        <input placeholder="ম্যনুয়াল মামলার সিরিয়াল নম্বর" title="" onchange="complaintForm.setCaseNumberValue();" type="text" id="case_no_sr" name="case_no_sr" class="input form-control" data-original-title="(xxxx.xx.xxxxx.সিরিয়াল নম্বর.xx) ম্যনুয়াল মামলার সিরিয়াল নম্বরটি দিন। এটি চার সংখ্যার হতে হবে। প্রয়োজনে নম্বরের শুরুতে শূন্য দিন।">
                                    </div>
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"><span style="color:#FF0000">*</span>বিভাগ</label>
                                    <select class="required selectDropdown form-control" name="division" id="ddlDivision999">
                                        <option value="">বাছাই করুন...</option>
                                        <option value="10">বরিশাল</option>
                                        <option value="20">চট্টগ্রাম</option>
                                        <option value="30">ঢাকা</option>
                                        <option value="40">খুলনা</option>
                                        <option value="50">রাজশাহী</option>
                                        <option value="55">রংপুর</option>
                                        <option value="60">সিলেট</option>
                                        <option value="80">ময়মনসিংহ</option>
                                    </select>
                                </div>
                                <!-- form-group -->
                            </div>
                            <!-- col-sm-6 -->

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label"><span style="color:#FF0000">*</span> জেলা</label>
                                         <select class="required selectDropdown form-control" id="ddlZilla999" name="zilla"  >
                                          <option value="">বাছাই করুন...</option>
                                        </select>
                                      
                                </div>
                                <!-- form-group -->
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div id="locationTypeTable_1" class="radiorequired">
                                        <label class="btn btn-default active">
                                            <input class="optLocationType999" type="radio" name="locationtype" id="upazillatype_1" value="UPAZILLA" required="true" ctrlid="999" checked="checked">উপজেলা
                                        </label>
                                        <label class="btn btn-default active">
                                            <input class="optLocationType999" type="radio" name="locationtype" id="citytype_1" value="CITYCORPORATION" ctrlid="999"> সিটি কর্পোরেশন
                                        </label>
                                        <label class="btn btn-default active">
                                            <input class="optLocationType999" type="radio" name="locationtype" id="metrotype_1" value="METROPOLITAN" ctrlid="999">মেট্রোপলিটন
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="upoziladiv_1">
                                <div class="col-sm-3">
                                    <div class="form-group">

                                        <select class="required selectDropdown select2-hidden-accessible" name="upazilla" id="ddlUpazilla999" required="true" tabindex="-1" aria-hidden="true" ctrlid="999"><option value="">বাছাই করুন...</option></select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 225.896px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-ddlUpazilla999-container"><span class="select2-selection__rendered" id="select2-ddlUpazilla999-container" title="বাছাই করুন...">বাছাই করুন...</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <select name="GeoThanas" id="ddlThana999" required="true" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true" ctrlid="999"><option value="">বাছাই করুন...</option></select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100px; display: none;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-ddlThana999-container"><span class="select2-selection__rendered" id="select2-ddlThana999-container" title="বাছাই করুন...">বাছাই করুন...</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label" ><span style="color:#FF0000">*</span>ঘটনাস্থল</label>

                                </div>
                                <!-- form-group -->
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">

                                    <input type="text" id="location" name="location" class="input form-control required" required="true">
                                </div>
                                <!-- form-group -->
                            </div>
                        </div>

                        <div class="form-group">
                            <h4  class="well well-sm text-center " style="background-color:#085F00;color:#fff;padding: 10px;">লঙ্ঘিত আইন ও ধারা </h4>
                        </div>
                        <div class="form-group criminal_laws" id="c-lawdiv_1">
                            <div class="form-group">
                                <label class="col-sm-2"><span style="color:#FF0000">*</span>আইন</label>
                                <div class="col-sm-10">
                                    <select class="required selectDropdown form-control" required="" name="brokenLaws[1][law_id]" id="ddlLaw1"  ><option value="">বাছাই করুন...</option><option value="50">অগ্নি প্রতিরোধ ও নির্বাপণ আইন, ২০০৩</option><option value="40">অত্যাবশ্যকীয় পণ্য নিয়ন্ত্রণ আইন, ১৯৫৬</option><option value="67">অভ্যন্তরীণ নৌ-চলাচল অধ্যাদেশ, ১৯৭৬</option><option value="83">অস্থাবর সম্পত্তি হুকুমদখল আইন, ১৯৮৮</option><option value="59">আয়োডিন অভাবজনিত রোগ প্রতিরোধ আইন, ১৯৮৯</option><option value="118">ইউনিয়ন পরিষদ (নির্বাচন আচারণ) বিধিমালা ২০১৬</option><option value="11">ইট প্রস্তুত ও ভাটা স্থাপন (নিয়ন্ত্রণ) আইন, ২০১৩</option><option value="48">ইমারত নির্মাণ আইন, ১৯৫২</option>
                                    <option value="140">উপজেলা পরিষদ (নির্বাচন আচরণ) বিধিমালা ২০১৬</option>
                                    <option value="42">উপজেলা পরিষদ আইন, ১৯৯৮</option>
                                    <option value="18">এসিড নিয়ন্ত্রণ আইন, ২০০২</option>
                                    <option value="64">ঔষধ(ড্রাগ) আইন, ১৯৪০</option>
                                    <option value="92">কন্ট্রোল অব এন্ট্রি এ্যাক্ট, ১৯৫২</option>
                                    <option value="58">করাত-কল (লাইসেন্স) বিধিমালা, ২০১২</option>
                                    <option value="45">কিশোর ধূমপান আইন,১৯১৯</option>
                                    <option value="76">কীটনাশক অধ্যাদেশ, ১৯৭১</option>
                                    <option value="73">কৃষিপণ্য বাজার নিয়ন্ত্রণ আইন, ১৯৬৪</option>
                                    <option value="60">কেবল টেলিভিশন নেটওয়ার্ক পরিচালনা আইন, ২০০৬</option>
                                    <option value="103">ক্যান্টনমেন্ট আইন, ১৯২৪</option>
                                    <option value="93">ক্রিমিনাল ল’ (শিল্প এলাকা) এ্যামেন্ডমেন্ট এ্যাক্ট, ১৯৪২</option>
                                    <option value="138">খনি ও খনিজ সম্পদ (নিয়ন্ত্রণ ও উন্নয়ন) আইন, ১৯৯২</option>
                                    <option value="36">খেয়া আইন, ১৮৮৫</option>
                                    <option value="71">গণপ্রতিনিধিত্ব আদেশ, ১৯৭২</option>
                                    <option value="106">চট্টগ্রাম বন্দর কর্তৃপক্ষ অধ্যাদেশ, ১৯৭৬</option>
                                    <option value="65">চলচ্চিত্রের সেন্সরসীপ আইন, ১৯৬৩</option>
                                    <option value="23">ছাপাখানা এবং প্রকাশনা (ঘোষণা ও নিবন্ধন) অধ্যাদেশ, ১৯৭৩</option>
                                    <option value="54">জন্ম ও মৃত্যু নিবন্ধন আইন, ২০০৪</option>
                                    <option value="128">জেলা পরিষদ (নির্বাচন আচরণ) বিধিমালা, ২০১৬</option>
                                    <option value="46">টাউট আইন,১৮৭৯</option><option value="17">ড্রাগ (নিয়ন্ত্রণ) অধ্যাদেশ, ১৯৮২</option>
                                    <option value="15">দন্ডবিধি, ১৮৬০</option>
                                    <option value="33">দি স্ট্যান্ডার্ডস অব ওয়েটস এন্ড মেজার্স অর্ডিন্যান্স, ১৯৮২</option>
                                    <option value="70">দুর্যোগ ব্যবস্থাপনা আইন, ২০১২</option>
                                    <option value="35">দেওয়াল লিখন ও পোস্টার লাগানো (নিয়ন্ত্রণ) আইন, ২০১২</option>
                                    <option value="8">ধুমপান ও তামাকজাত দ্রব্য ব্যবহার (নিয়ন্ত্রণ) আইন, ২০০৫</option>
                                    <option value="109">নিরাপদ খাদ্য আইন, ২০১৩</option>
                                    <option value="69">নিরাপদ রক্ত পরিসঞ্চালন আইন, ২০০২</option>
                                    <option value="29">নোট বই নিষিদ্ধকরণ আইন-১৯৮০</option>
                                    <option value="66">পণ্যে পাটজাত মোড়কের বাধ্যতামূলক ব্যবহার আইন, ২০১০</option>
                                    <option value="28">পশু জবাই ও মাংসের মান নিয়ন্ত্রণ আইন, ২০১১</option>
                                    <option value="98">পশু নির্যাতন আইন, ১৯২০</option>
                                    <option value="68">পাইলটেজ অধ্যাদেশ, ১৯৬৯</option>
                                    <option value="87">পাট অধ্যাদেশ, ১৯৬২</option>
                                    <option value="90">পানি সরবরাহ ও পয়ঃনিষ্কাশন কর্তৃপক্ষ আইন, ১৯৯৬</option>
                                    <option value="13">পাবলিক পরীক্ষাসমূহ (অপরাধ) আইন, ১৯৮০</option>
                                    <option value="102">পাসপোর্ট আইন, ১৯২০</option>
                                    <option value="79">পেট্রোলিয়াম আইন, ১৯৩৪</option>
                                    <option value="115">পৌরসভা (নির্বাচন আচরণ) বিধিমালা, ২০১৫</option>
                                    <option value="26">বংগীয়  প্রকাশ্য জুয়া আইন-১৮৬৭</option>
                                    <option value="94">বঙ্গীয় ভবঘুরে আইন, ১৯৪৩</option>
                                    <option value="95">বঙ্গীয় সর্বসাধারণের চিত্তবিনোদন স্থান আইন, ১৯৩৩</option>
                                    <option value="77">বন আইন, ১৯২৭</option><option value="100">বন্দর আইন, ১৯০৮</option>
                                    <option value="56">বন্দর রক্ষা (বিশেষ ব্যবস্থা)আইন-১৯৪৮</option>
                                    <option value="74">বন্যপ্রাণী সংরক্ষণ ও নিরাপত্তা আইন, ২০১২</option>
                                    <option value="97">বাংলাদেশ ইউনানী এবং আয়ুর্বেদিক প্র্যাকটিশনারস অধ্যাদেশ, ১৯৮৩</option>
                                    <option value="62">বাংলাদেশ এ্যাক্রেডিটেশন আইন, ২০০৬ </option>
                                    <option value="78">বাংলাদেশ গ্যাস আইন, ২০১০</option>
                                    <option value="24">বাংলাদেশ জাতীয় সঙ্গীত , পতাকা প্রতীক অধ্যাদেশ, ১৯৭২ </option>
                                    <option value="135">বাংলাদেশ ট্রাভেল এজেন্সি (নিবন্ধন ও নিয়ন্ত্রণ) আইন, ২০১৩</option>
                                    <option value="25">বাংলাদেশ পরিবেশ সংরক্ষণ আইন, ১৯৯৫</option>
                                    <option value="75">বাংলাদেশ শ্রম আইন, ২০০৬</option>
                                    <option value="30">বাংলাদেশ স্টান্ডার্ডস ও টেস্টিং ইনস্টিটিউশন অধ্যাদেশ-১৯৮৫ ও বিএসটিআই (এমেন্ডমেন্ট) এ্যাক্ট, ২০০৩</option><option value="20">বাংলাদেশ হোটেল ও রেস্টুরেন্ট অধ্যাদেশ, ১৯৮২</option><option value="117">বাংলাদেশ হোটেল ও রেস্তোঁরা আইন, ২০১৪</option><option value="91">বাংলাদেশ হোমিওপ্যাথিক প্র্যাকটিশনার অধ্যাদেশ, ১৯৮৩</option><option value="89">বাংলাদেশে বাণিজ্যিক নৌ চলাচল অধ্যাদেশ, ১৯৮৩</option><option value="37">বালু মহালও মাটি ব্যবস্থাপনা আইন, ২০১০</option><option value="147">বাল্যবিবাহ নিরোধ আইন, ২০১৭</option><option value="88">বিদ্যুৎ আইন, ১৯১০</option><option value="107">বিমান-নিরাপত্তা বিরোধী অপরাধ দমন আইন, ১৯৯৭</option><option value="10">বিশুদ্ধ খাদ্য অধ্যাদেশ, ১৯৫৯</option><option value="101">বিষ আইন, ১৯১৯</option><option value="72">বীজ অধ্যাদেশ, ১৯৭৭</option><option value="80">বেসামরিক বিমান অধ্যাদেশ, ১৯৬০</option><option value="84">বৈদেশিক কর্মসস্থান ও অভিবাসী আইন, ২০১৩</option><option value="19">ভোক্তা-অধিকার সংরক্ষণ আইন, ২০০৯</option><option value="122">ভোজ্যতেলে ভিটামিন ‘এ’ সমৃদ্ধকরণ আইন, ২০১৩</option><option value="108">মংলা বন্দর কর্তৃপক্ষ অধ্যাদেশ, ১৯৭৬</option><option value="51">মহানগরী, বিভাগীয় শহর ও জেলা শহরের পৌর এলাকাসহ দেশের সকল পৌর এলাকার খেলার মাঠ, উন্মুক্ত স্থান, উদ্যান এবং প্রাকৃতিক জলাধার সংরক্ষণ আইন, ২০০০</option><option value="34">মাছ এবং মাছ জাত দ্রব্য (পরিদর্শন এবং মান নিয়ন্ত্রণ) অধ্যাদেশ,১৯৮৩</option><option value="82">মাতৃদুগ্ধ বিকল্প, শিশু খাদ্য, বাণিজ্যিকভাবে প্রস্তুতকৃত শিশুর বাড়তি খাদ্য ও উহা ব্যবহারের সরঞ্জামাদি (বিপণন নিয়ন্ত্রণ) আইন, ২০১৩</option><option value="39">মাদক দ্রব্য নিয়ন্ত্রণ আইন, ১৯৯০</option><option value="53">মানবদেহে অঙ্গ-প্রত্যঙ্গ সংযোজন আইন-১৯৯৯</option><option value="105">মেডিকেল ও ডেন্টাল কাউন্সিল আইন, ২০১০</option><option value="16">মেডিকেল প্র্যাকটিস এবং বেসরকারী ক্লিনিক ও ল্যাবরেটরী (নিয়ন্ত্রণ) অধ্যাদেশ, ১৯৮২</option><option value="12">মোটরযান অধ্যাদেশ, ১৯৮৩</option><option value="27">মৎস্য রক্ষা ও সংরক্ষণ আইন, ১৯৫০</option><option value="99">মৎস্য হ্যাচারি আইন, ২০১০</option><option value="104">মৎস্যখাদ্য ও পশুখাদ্য আইন, ২০১০</option><option value="144">রাজনৈতিক দল ও প্রার্থীর আচরণ বিধিমালা, ২০০৮</option><option value="52">রাষ্ট্রীয় জলসীমা এবং সামুদ্রিক অঞ্চল আইন, ১৯৭৪</option><option value="61">রিয়েল এস্টেট উন্নয়ন ও ব্যবস্থাপনা আইন, ২০১০</option><option value="86">রেলওয়ে আইন, ১৮৯০</option><option value="132">শব্দদূষণ (নিয়ন্ত্রণ) বিধিমালা, ২০০৬</option><option value="47">সরকারি এবং স্থানীয় কর্তৃপক্ষীয় ভূমি ও ইমারত (দখল পুনরুদ্ধার) আদেশ,১৯৭০</option><option value="57">সরাই আইন ১৮৬৭</option><option value="32">সার (ব্যবস্থাপনা) আইন, ২০০৬</option><option value="143">সিটি কর্পোরেশন (নির্বাচন আচরণ) বিধিমালা, ২০১৬</option><option value="110">সিটি কর্পোরেশন নির্বাচন (ইলেকট্রনিক ভোটিং মেশিন) বিধিমালা, ২০১০  </option><option value="55">সিনেমাটোগ্রাফ আইন, ১৯১৮</option><option value="49">স্থানীয় সরকার (ইউনিয়ন পরিষদ) আইন, ২০০৯</option><option value="43">স্থানীয় সরকার (পৌরসভা ) আইন, ২০০৯</option><option value="44">স্থানীয় সরকার (সিটি কর্পোরেশন) আইন, ২০০৯</option><option value="111">স্থানীয় সরকার(সিটি কর্পোরেশন) নির্বাচন বিধিমালা, ২০১০</option><option value="119">হাইওয়ে আইন, ১৯২৫</option></select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><span style="color:#FF0000">*</span>ধারা</label>
                                <div class="col-sm-10">
                                    <select class="required selectDropdown form-control" required="" name="brokenLaws[1][section_id]" id="ddlSection1"  >
                                        <option value="">বাছাই করুন...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><span style="color:#FF0000"></span>শাস্তির ধারা</label>
                                <div class="col-sm-10">
                                    <textarea id="txtPunishmentDesc1" name="brokenLaws[1][section_description]" class="input form-control" cols="50" rows="1" readonly="readonly"></textarea>
                                </div>
                            </div><!-- form-group -->
                            <div class="form-group">
                                <label class="col-sm-2"><span style="color:#FF0000">*</span>অপরাধের বিবরণ </label>
                                <div class="col-sm-10">
                                    <textarea id="txtCrimeDesc1" name="brokenLaws[1][crime_description]" class="input form-control required" cols="50" rows="4" required="true"></textarea>
                                </div>
                            </div><!-- form-group -->

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button type="button" class="btn btn-small btn-primary" id="c_a_button_1" name="c-L" onclick="complaintForm.addNewLaw(true);">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>আরেকটি</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2"><span style="color:#FF0000">*</span>ঘটনাটি </label>
                            <div class="col-sm-10">
                                <input class="incidentType" type="radio" name="occurrence_type" value="1" checked=""> সংঘটিত
                                <input class="incidentType" type="radio" name="occurrence_type" value="2"> উৎঘাটিত
                            </div>
                        </div><!-- form-group -->

                        <div class="form-group">
                            <h4 class="well well-sm " style="background-color:#085F00;color:#fff;padding: 10px;">অপরাধের ধরন</h4>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2"><span style="color:#FF0000">*</span>অপরাধের ধরন ১ </label>
                            <div class="col-sm-3">
                                <select id="case_type1" name="case_type1" class="input required selectDropdown form-control" usedummy="1" required="1" tabindex="-1" aria-hidden="true">
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
                            <label class="col-sm-2"><span style="color:#FF0000"></span>অপরাধের ধরন ২</label>
                            <div class="col-sm-3">
                                <select id="case_type2" name="case_type2" class="input selectDropdown  form-control"  >
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
                        </div><!-- form-group -->

                        <div class="form-group">
                            <label class="col-sm-2">সূত্র </label>
                            <div class="col-sm-10">
                                <input type="text" id="hints" name="hints" class="input form-control">
                            </div>
                        </div><!-- form-group -->

                        <div class="form-group">
                            <h4  class="well well-sm " style="background-color:#085F00;color:#fff;padding: 10px;">সংযুক্তি</h4>
                            <label class="control-label">অভিযোগ গঠন ফরমের স্ক্যানকৃত ছবি সংযুক্ত করুন (যদি থাকে)</label>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="panel panel-danger-alt">
                                    <div class="panel-body   p-5 photoContainer">
                                        <button type="button" class="btn btn-success multifileupload">
                                            <span>+</span>
                                        </button>
                                        <hr>
                                        <div class="panel panel-danger-alt">
                                            <div class="docs-toggles"></div>
                                            <div class="docs-galley photoView">


                                            </div>
                                            <div class="docs-buttons" role="group"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="chargeFameAttachemntLable"></div>
                        <div class="panel panel-danger-alt">
                            <div class="form-group">
                                <div class="panel panel-danger-alt">
                                        <div class="row" id="chargeFameAttachedFile">
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="pull-right float-right">
                                <button class="btn btn-success mr5" type="button" onclick="complaintForm.save()"><i class="glyphicon glyphicon-ok"></i> সংরক্ষণ
                                </button>
                            </div>
                        </div>
                    </form>