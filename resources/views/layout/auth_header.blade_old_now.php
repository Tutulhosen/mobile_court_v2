<style type="text/css">
    .notification {
        position: absolute;
        top: 0;
        right: 40px;
    }

    /*-- Custom Dropdown submenu CSS --*/

    .dropdown-menu .dropdown-submenu {
        position: relative;
    }

    .dropdown-menu .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
        border-radius: .25rem;
        display: none;
        /* Hide by default */
    }

    .dropdown-menu .dropdown-submenu:hover .dropdown-menu {
        display: block;
        /* Show on hover */
    }
</style>

<div id="kt_header" class="header header-fixed">
    <!--begin::Container-->
    {{-- @if (citizen_auth_menu()) --}}
    <div class="container align-items-stretch justify-content-between">

        <!--begin::Topbar-->
        <div class="topbar_wrapper">
            <div class="topbar">

                @auth
                    <div class="dropdown">
                        <!--begin::Toggle-->
                        <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click" data-toggle="tooltip"
                            data-placement="right" title data-original-title="" aria-haspopup="true">
                            <a href="{{ url('home_redirct') }}" class="navi-link  ">
                                <div class="btn-dropdown mr-2 pulse pulse-primary" style="padding-left: 0 !important;">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">

                                        <i class="fa fa-home"></i>
                                        <!-- <p class="navi-text">ড্যাশবোর্ড</p> -->
                                    </span>
                                    <span class="pulse-ring"></span>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <!--begin::Toggle-->
                        <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click" data-toggle="tooltip"
                            data-placement="right" title data-original-title="" aria-haspopup="true">
                            <a href="{{ url('dashboard') }}" class="navi-link  ">
                                <div class="btn-dropdown mr-2 pulse pulse-primary" style="padding-left: 0 !important;">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->

                                        <!-- <i class="fa fa-home"></i> -->
                                        <p class="navi-text">ড্যাশবোর্ড</p>
                                    </span>
                                    <span class="pulse-ring"></span>
                                </div>
                            </a>
                        </div>
                    </div>
                    @if (globalUserInfo()->role_id == 26)
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click" data-toggle="tooltip"
                                data-placement="right" title data-original-title="" aria-haspopup="true">
                                <a href="{{ route('court.openclose') }}  " class="navi-link  ">
                                    <div class="btn-dropdown mr-2 pulse pulse-primary" style="padding-left: 0 !important;">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-landmark"></i>
                                            <p class="navi-text">কর্মসূচি প্রণয়ন</p>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (globalUserInfo()->role_id == 26)
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                                <div
                                    class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <p class="navi-text">কোর্ট পরিচালনা </p>
                                    </span>
                                </div>
                            </div>
                            <!--begin::Dropdown-->
                            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                                <!--begin::Nav-->
                                <ul class="navi navi-hover py-4">
                                    <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href="{{ route('prosecution.suomotucourt') }}" class="navi-link  ">
                                            <!-- <span class="symbol2 symbol-20 mr-3">
                                                      <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x"><i class="fas fa-gavel"></i></span>
                                                    </span> -->
                                            <span class="navi-text">স্বপ্রণোদিত কোর্ট</span>
                                        </a>
                                    </li>

                                    <li class="navi-item">
                                        <a href="{{ route('prosecution.incompletecase') }} " class="navi-link  ">
                                            <!-- <span class="symbol2 symbol-20 mr-3">
                                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                      <i class="fas fa-gavel"></i>
                                                      
                                                    </span>
                                                    </span> -->
                                            <span class="navi-text">অসম্পূর্ণ মামলা(স্বপ্রণোদিত)</span>
                                        </a>
                                    </li>

                                    <li class="navi-item dropdown-submenu">
                                        <a href="#" class="navi-link dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <!-- <span class="symbol2 symbol-20 mr-3">
                                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                            <i class="fas fa-gavel"></i>
                                                        </span>
                                                    </span> -->
                                            <span class="navi-text">আসামি ছাড়া মামলা</span>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('suomotucourtWithoutCriminal') }}">স্বপ্রোণদিত কোর্ট</a>
                                            <a class="dropdown-item"
                                                href="{{ route('incompletecaseWithoutCriminal') }}">অসম্পূর্ণ মামলা
                                                (স্বপ্রোণদিত)
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('searchProsecutionWithoutCriminal') }}">প্রসিকিউশনের
                                                তালিকা</a>
                                        </div>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('prosecution.searchProsecution') }}" class="navi-link  ">
                                            <!-- <span class="symbol2 symbol-20 mr-3">
                                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                           
                                                        </span>
                                                    </span> -->
                                            <span class="navi-text">প্রসিকিউশনের তালিকা</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('prosecution.searchComplain') }} " class="navi-link  ">
                                            <!-- <span class="symbol2 symbol-20 mr-3">
                                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    
                                                        </span>
                                                    </span> -->
                                            <span class="navi-text">অসম্পূর্ণ মামলা(প্রসিকিউশনসহ)</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('searchCase') }} " class="navi-link  ">
                                            <!-- <span class="symbol2 symbol-20 mr-3">
                                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                          
                                                        </span>
                                                    </span> -->
                                            <span class="navi-text">আদেশ সংযোজন</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('showForms.mc') }}" class="navi-link  ">
                                            <!-- <span class="symbol2 symbol-20 mr-3">
                                                     
                                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                        <i class="fas fa-gavel"></i>
                                                          
                                                        </span>
                                                    </span> -->
                                            <span class="navi-text">মামলার তথ্য</span>
                                        </a>
                                    </li>
                                    <!--end::Item-->
                                </ul>
                                <!--end::Nav-->
                            </div>
                            <!--end::Dropdown-->
                        </div>
                    @endif

                    @if (globalUserInfo()->role_id == 26 || globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38)
                        <div class="dropdown">
                            <!--begin::Toggle-->

                            <div class="topbar-item" data-offset="10px,0px"
                                <?php if(globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38){ ?>data-menu-toggle="click"<?php }else{?>
                                data-toggle="dropdown"<?php }?> data-toggle="tooltip" data-placement="right"
                                title="" data-original-title="" aria-haspopup="true">
                                <a href="{{ route('citizen_complain.showCitizenComplain') }}" class="navi-link  ">
                                    <div class="btn-dropdown mr-2 pulse pulse-primary"
                                        style="padding-left: 0 !important;">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->

                                            <!-- <i class="fa fa-home"></i> -->
                                            <p class="navi-text"> অপরাধের তথ্য</p>

                                        </span>
                                        <span class="pulse-ring"></span>
                                    </div>
                                </a>
                            </div>
                            <!--begin::Dropdown-->

                            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                                <!--begin::Nav-->
                                <ul class="navi navi-hover py-4">
                                    <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href=" {{ route('magistrate.newattachmentrequisition') }}"
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">অভিযোগ সংযুক্তিকরণ </span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('magistrate.complainVarification') }}" class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">অভিযোগ প্রতিপাদন </span>
                                        </a>
                                    </li>
                                    <!--end::Item-->
                                </ul>
                                <!--end::Nav-->
                            </div>

                            <!--end::Dropdown-->
                        </div>
                    @endif
                    @if (globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38)
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click"
                                data-toggle="tooltip" data-placement="right" title data-original-title=""
                                aria-haspopup="true">
                                <a href="{{ route('citizen_complain.showRequisition') }}" class="navi-link  ">
                                    <div class="btn-dropdown mr-2 pulse pulse-primary"
                                        style="padding-left: 0 !important;">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->

                                            <!-- <i class="fa fa-home"></i> -->
                                            <p class="navi-text">গ্রহণকৃত অপরাধের তথ্য</p>
                                        </span>
                                        <span class="pulse-ring"></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (globalUserInfo()->role_id == 26 || globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38)
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" <?php if(globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38){ ?> data-menu-toggle="click" <?php }else{?>
                                data-toggle="dropdown"<?php }?> data-offset="10px,0px" title="">
                                <a href="{{ route('adm.caseTracker') }}" class="navi-link  ">
                                    <div class=" btn-clean btn-dropdown mr-2 ">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">

                                            <p class="navi-text"> অনুসন্ধান</p>
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <!--begin::Dropdown-->
                            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                                <!--begin::Nav-->
                                <ul class="navi navi-hover py-4">
                                    <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href="{{ route('magistrate.caseTracker') }} " class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">মামলা / অভিযোগ অনুসন্ধান</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('magistrate.criminalTracker') }} " class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">আসামি অনুসন্ধান</span>
                                        </a>
                                    </li>
                                    <!--end::Item-->
                                </ul>
                                <!--end::Nav-->
                            </div>
                            <!--end::Dropdown-->
                        </div>
                    @endif





                    @if (globalUserInfo()->role_id == 25)
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                                <div
                                    class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">

                                        <p class="navi-text"> অভিযোগ দায়ের</p>
                                    </span>
                                </div>
                            </div>
                            <!--begin::Dropdown-->
                            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                                <!--begin::Nav-->
                                <ul class="navi navi-hover py-4">
                                    <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href="{{ route('newProsecution') }} " class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text"> অভিযোগ দায়ের</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('incompleteProsecution') }} " class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">অসম্পূর্ণ অভিযোগ</span>
                                        </a>
                                    </li>

                                    <li class="navi-item">
                                        <a href="{{ route('newProsecutionWithoutCriminal') }} " class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">অভিযোগ দায়ের (আসামি ছাড়া)</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('incompleteProsecutionWithoutCriminal') }} "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">অসম্পূর্ণ অভিযোগ (আসামি ছাড়া)</span>
                                        </a>
                                    </li>
                                    <!--end::Item-->
                                </ul>
                                <!--end::Nav-->
                            </div>
                            <!--end::Dropdown-->
                        </div>
                </div>
                <!--begin::Dropdown-->
                <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                    <!--begin::Nav-->
                    <ul class="navi navi-hover py-4">
                        <!--begin::Item-->
                        @if (globalUserInfo()->role_id == 26 ||
                                globalUserInfo()->role_id == 25 ||
                                globalUserInfo()->role_id == 37 ||
                                globalUserInfo()->role_id == 38)
                            <li class="navi-item">
                                <a href="{{ route('registerlist') }}" class="navi-link  ">
                                    <span class="symbol2 symbol-20 mr-3">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-gavel"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text">রেজিস্টার</span>
                                </a>
                            </li>
                        @endif
                        @if (globalUserInfo()->role_id == 26)
                            <li class="navi-item">
                                <a href="{{ route('news.createnews') }}" class="navi-link  ">
                                    <span class="symbol2 symbol-20 mr-3">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-gavel"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text">খবর আপলোড</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href=" {{ route('news.newslist') }}" class="navi-link  ">
                                    <span class="symbol2 symbol-20 mr-3">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-gavel"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text">খবর তালিকা</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="{{ route('sottogolpo.create') }}" class="navi-link  ">
                                    <span class="symbol2 symbol-20 mr-3">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-gavel"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text">গল্প আপলোড </span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="{{ route('sottogolpo.list') }}" class="navi-link  ">
                                    <span class="symbol2 symbol-20 mr-3">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-gavel"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text">গল্পের তালিকা</span>
                                </a>
                            </li>
                        @endif
                        @if (globalUserInfo()->role_id == 26 || globalUserInfo()->role_id == 38 || globalUserInfo()->role_id == 25)
                            <li class="navi-item">
                                <a href="{{ route('jurisdiction.determination') }}" class="navi-link  ">
                                    <span class="symbol2 symbol-20 mr-3">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-gavel"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text">অধিক্ষেত্র নির্ধারণ</span>
                                </a>
                            </li>
                        @endif
                        @if (globalUserInfo()->role_id == 26)
                            <li class="navi-item">
                                <a href="{{ route('mc.removedCase') }}" class="navi-link  ">
                                    <span class="symbol2 symbol-20 mr-3">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-gavel"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text"> বাতিলকৃত মামলা </span>
                                </a>
                            </li>
                        @endif
                        @if (globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38)
                            <li class="navi-item">
                                <a href="{{ route('deletecaseview') }}" class="navi-link  ">
                                    <span class="symbol2 symbol-20 mr-3">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                            <i class="fas fa-gavel"></i>
                                            <!--end::Svg Icon-->
                                        </span>
                                    </span>
                                    <span class="navi-text"> মামলা বাতিল </span>
                                </a>
                            </li>
                        @endif
                        <!--end::Item-->
                    </ul>
                    <!--end::Nav-->
                </div>
                <!--end::Dropdown-->
            </div>
            @endif

            <!-- <i class="fa fa-home"></i> -->
            <p class="navi-text">জব্দতালিকা </p>
            </span>
            <span class="pulse-ring"></span>
        </div>
        </a>
    </div>
    </div>
    @endif
    @if (globalUserInfo()->role_id == 25)
        <div class="dropdown">
            <!--begin::Toggle-->
            <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click" data-toggle="tooltip"
                data-placement="right" title data-original-title="" aria-haspopup="true">
                <a href="{{ route('showProsecutionList') }}" class="navi-link  ">
                    <div class="btn-dropdown mr-2 pulse pulse-primary" style="padding-left: 0 !important;">
                        <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->

                            <!-- <i class="fa fa-home"></i> -->
                            <p class="navi-text">দায়েরকৃত অভিযোগ </p>
                        </span>
                        <span class="pulse-ring"></span>
                    </div>
                </a>
            </div>
        </div>
    @endif
    @if (globalUserInfo()->role_id == 26 ||
            globalUserInfo()->role_id == 25 ||
            globalUserInfo()->role_id == 37 ||
            globalUserInfo()->role_id == 38)
        <div class="dropdown">
            <!--begin::Toggle-->
            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                <div
                    class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">

                        <p class="navi-text"> দাপ্তরিক</p>
                    </span>
                </div>
            </div>
            <!--begin::Dropdown-->
            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                <!--begin::Nav-->
                <ul class="navi navi-hover py-4">
                    <!--begin::Item-->
                    @if (globalUserInfo()->role_id == 26 ||
                            globalUserInfo()->role_id == 25 ||
                            globalUserInfo()->role_id == 37 ||
                            globalUserInfo()->role_id == 38)
                        <li class="navi-item">
                            <a href="{{ route('registerlist') }}" class="navi-link  ">
                                <span class="symbol2 symbol-20 mr-3">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text">রেজিস্টার</span>
                            </a>
                        </li>
                    @endif
                    @if (globalUserInfo()->role_id == 26)
                        <li class="navi-item">
                            <a href="{{ route('news.createnews') }}" class="navi-link  ">
                                <span class="symbol2 symbol-20 mr-3">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text">খবর আপলোড</span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href=" {{ route('news.newslist') }}" class="navi-link  ">
                                <span class="symbol2 symbol-20 mr-3">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text">খবর তালিকা</span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="{{ route('sottogolpo.create') }}" class="navi-link  ">
                                <span class="symbol2 symbol-20 mr-3">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text">গল্প আপলোড </span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="{{ route('sottogolpo.list') }}" class="navi-link  ">
                                <span class="symbol2 symbol-20 mr-3">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text">গল্পের তালিকা</span>
                            </a>
                        </li>
                    @endif
                    @if (globalUserInfo()->role_id == 26 || globalUserInfo()->role_id == 25)
                        <li class="navi-item">
                            <a href=" " class="navi-link  ">
                                <span class="symbol2 symbol-20 mr-3">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text">কর্মস্থল (অধিক্ষেত্র) পরিবর্তন</span>
                            </a>
                        </li>
                    @endif
                    @if (globalUserInfo()->role_id == 26)
                        <li class="navi-item">
                            <a href="{{ route('mc.removedCase') }}" class="navi-link  ">
                                <span class="symbol2 symbol-20 mr-3">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text"> বাতিলকৃত মামলা </span>
                            </a>
                        </li>
                    @endif
                    @if (globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38)
                        <li class="navi-item">
                            <a href="{{ route('deletecaseview') }}" class="navi-link  ">
                                <span class="symbol2 symbol-20 mr-3">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <i class="fas fa-gavel"></i>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text"> মামলা বাতিল </span>
                            </a>
                        </li>
                    @endif
                    <!--end::Item-->
                </ul>
                <!--end::Nav-->
            </div>
            <!--end::Dropdown-->
        </div>
    @endif

    @if (globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38)
        <div class="dropdown">
            <!--begin::Toggle-->
            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                <div
                    class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">

                        <p class="navi-text"> প্রতিবেদন</p>
                    </span>
                </div>
            </div>
            <!--begin::Dropdown-->
            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                <!--begin::Nav-->
                <ul class="navi navi-hover py-4">
                    <!--begin::Item-->

                    <li class="navi-item">
                        <a href="{{ route('m.report') }}" class="navi-link  ">

                            <span class="navi-text">প্রতিবেদন</span>
                        </a>
                    </li>


                    <li class="navi-item">
                        <a href="{{ route('m.approvemonth') }}" class="navi-link  ">

                            <span class="navi-text">প্রতিবেদন অনুমোদন</span>
                        </a>
                    </li>

                    <!-- acjm profile -->
                    @if (globalUserInfo()->role_id == 27)
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                                <div
                                    class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                        <p class="navi-text"> প্রতিবেদন</p>
                                    </span>
                                </div>
                            </div>
                            <!--begin::Dropdown-->
                            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                                <!--begin::Nav-->
                                <ul class="navi navi-hover py-4">
                                    <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href="{{ route('m.mobilecourtreport') }}" class="navi-link  ">
                                            <span class="navi-text">মোবাইল কোর্টের মাসিক প্রতিবেদন</span>
                                        </a>
                                    </li>


                                    <li class="navi-item">
                                        <a href="{{ route('m.appealcasereport') }}" class="navi-link  ">
                                            <span class="navi-text">মোবাইল কোর্টের আপিল মামলার তথ্য</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('m.admcasereport') }}" class="navi-link  ">
                                            <span class="navi-text">ADM কোর্টের মামলার তথ্য</span>
                                        </a>
                                    </li>

                                    <li class="navi-item">
                                        <a href="{{ route('m.emcasereport') }}" class="navi-link  ">
                                            <span class="navi-text">EM কোর্টের মামলার তথ্য</span>
                                        </a>
                                    </li>


                                    <li class="navi-item">
                                        <a href="{{ route('m.courtvisitreport') }}" class="navi-link  ">
                                            <span class="navi-text">এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালত পরিদর্শন</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('m.caserecordreport') }}" class="navi-link  ">
                                            <span class="navi-text">মোবাইল কোর্ট কেস রেকর্ড পর্যালোচনা</span>
                                        </a>
                                    </li>




                                    <!--end::Item-->
                                </ul>
                                <!--end::Nav-->
                            </div>
                            <!--end::Dropdown-->
                        </div>
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                                <div
                                    class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">

                                        <p class="navi-text"> প্রতিবেদন সংশোধন</p>
                                    </span>
                                </div>
                            </div>
                            <!--begin::Dropdown-->
                            <!-- <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                          
                                 <ul class="navi navi-hover py-4">
                            
                                            <li class="navi-item">
                                                <a href="{{ route('m.report') }}"
                                                    class="navi-link  ">
                                                    <span class="symbol2 symbol-20 mr-3">
                                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                               
                                                        </span>
                                                    </span>
                                                    <span class="navi-text">প্রতিবেদন</span>
                                                </a>
                                            </li>
         
                                         
                                            <li class="navi-item">
                                                <a href="{{ route('news.createnews') }}"
                                                    class="navi-link  ">
                                                    <span class="symbol2 symbol-20 mr-3">
                                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                      
                                                        </span>
                                                    </span>
                                                    <span class="navi-text">প্রতিবেদন অনুমোদন</span>
                                                </a>
                                            </li>
                                              
                                        
                                           
                                          
                                    
                                       
                                      
                                <!--end::Item-->
                </ul>
                <!--end::Nav-->
            </div>
            <!--end::Dropdown-->
        </div>
        <div class="dropdown">
            <!--begin::Toggle-->
            <a href="{{ route('m.reportCorrectionList') }}" class="navi-link  ">
                <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click" data-toggle="tooltip"
                    data-placement="right" title data-original-title="" aria-haspopup="true">
                    <div class=" btn-clean btn-dropdown mr-2 ">
                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">

                            </ul>

                    </div> -->

                </div>
    @endif


    <div class="topbar-item mt-3">
        <div class="btn  -mobile w-auto btn-clean d-flex align-items-center btn-sm px-2" id="kt_quick_user_toggle"
            style="margin: -12px">
            <span class="text-muted font-size-base d-none d-md-inline mr-1">
                @if (Auth::user()->profile_pic != null)
                    @if (Auth::user()->doptor_user_flag == 1)
                        <img src="{{ url('/') }}/uploads/profile/{{ Auth::user()->profile_pic }}">
                    @else
                        <img src="{{ url('/') }}/uploads/profile/{{ Auth::user()->profile_pic }}">
                    @endif
                @else
                    <img src="{{ url('/') }}/uploads/profile/default.jpg">
                @endif

            </span>
            <span class="text-dark font-size-base d-none d-md-inline mr-3 text-left">
                <i style="float: right; padding-left: 20px; padding-top: 12px;" class="fas fa-chevron-down"></i>
                <b>

                    {{ auth()->user()->name }}

                </b>

                <br>{{ roleName(globalUserInfo()->role_id)->role_name }}
            </span>

        </div>
    </div>
    </a>

    <!--begin::Dropdown-->
    <!-- <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                      
                             <ul class="navi navi-hover py-4">
                        
                                        <li class="navi-item">
                                            <a href="{{ route('m.report') }}"
                                                class="navi-link  ">
                                                <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                <i class="fas fa-gavel"></i>
                                           
                                                    </span>
                                                </span>
                                                <span class="navi-text">প্রতিবেদন</span>
                                            </a>
                                        </li>
     
                                     
                                        <li class="navi-item">
                                            <a href="{{ route('news.createnews') }}"
                                                class="navi-link  ">
                                                <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                <i class="fas fa-gavel"></i>
                                                  
                                                    </span>
                                                </span>
                                                <span class="navi-text">প্রতিবেদন অনুমোদন</span>
                                            </a>
                                        </li>
                                          
                                    
                                       
                                      
                            
                            </ul>
                  
                        </div> -->

    </div>
    @endif


    <div class="topbar-item mt-3">
        <div class="btn  -mobile w-auto btn-clean d-flex align-items-center btn-sm px-2" id="kt_quick_user_toggle"
            style="margin: -12px">
            <span class="text-muted font-size-base d-none d-md-inline mr-1">
                @if (Auth::user()->profile_pic != null)
                    @if (Auth::user()->doptor_user_flag == 1)
                        <img src="{{ url('/') }}/uploads/profile/{{ Auth::user()->profile_pic }}">
                    @else
                        <img src="{{ url('/') }}/uploads/profile/{{ Auth::user()->profile_pic }}">
                    @endif
                @else
                    <img src="{{ url('/') }}/uploads/profile/default.jpg">
                @endif

            </span>
            <span class="text-dark font-size-base d-none d-md-inline mr-3 text-left">
                <i style="float: right; padding-left: 20px; padding-top: 12px;" class="fas fa-chevron-down"></i>
                <b>

                    {{ auth()->user()->name }}

                </b>

                <br>{{ roleName(globalUserInfo()->role_id)->role_name }}
            </span>

        </div>
    </div>
@else
    <div class="tpbar_text_menu topbar-item mr-2">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
            <a href="" class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">ধারা
                ভিত্তিক অভিযোগের ধরণ</a>
        </div>
    </div>
    <div class="tpbar_text_menu topbar-item mr-2">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
            <a href="" class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">প্রসেস
                ম্যাপ</a>
        </div>
    </div>
    <div class="tpbar_text_menu tpbar_text_mlast topbar-item mr-8">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
            <a href="" class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">আইন ও
                বিধি</a>
        </div>
    </div>
    <div class="topbar-item">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="topbar_social_icon">
            <a href="" class="social-svg-icon svg-icon-primary svg-icon-2x">
                <svg style="color: rgb(109, 91, 220);" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">Copyright
                    2022 Fonticons, Inc. --><path
                        d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
                        fill="#6d5bdc"></path></svg>
            </a>
        </div>
    </div>

    <div class="topbar-item">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="topbar_social_icon">
            <a href="" class="social-svg-icon svg-icon-primary svg-icon-2x">
                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                    <path
                        d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"
                        fill="#6c5adc"></path>
                </svg>
            </a>
        </div>
    </div>

    <div class="topbar-item mr-8">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="topbar_social_icon">
            <a href="" class="social-svg-icon svg-icon-primary svg-icon-2x">
                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                    <path
                        d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"
                        fill="#6c5adc"></path>
                </svg>
            </a>
        </div>
    </div>




    <div class="topbar-item">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
                    <path
                        d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"
                        fill="#6c5adc"></path>
                </svg>
                <!--end::Svg Icon-->
            </span><b> Online Course</b>
            <!-- <input type="button" id="loginID" class="btn btn-info" value="{{ __('লগইন') }}"
                                                                            data-toggle="modal" data-target="#exampleModalLong"> -->
        </div>
    </div>
    <div class="topbar-item">
        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path
                            d="M12,22 C6.4771525,22 2,17.5228475 2,12 C2,6.4771525 6.4771525,2 12,2 C17.5228475,2 22,6.4771525 22,12 C22,17.5228475 17.5228475,22 12,22 Z M11.613922,13.2130341 C11.1688026,13.6581534 10.4887934,13.7685037 9.92575695,13.4869855 C9.36272054,13.2054673 8.68271128,13.3158176 8.23759191,13.760937 L6.72658218,15.2719467 C6.67169475,15.3268342 6.63034033,15.393747 6.60579393,15.4673862 C6.51847004,15.7293579 6.66005003,16.0125179 6.92202169,16.0998418 L8.27584113,16.5511149 C9.57592638,16.9844767 11.009274,16.6461092 11.9783003,15.6770829 L15.9775173,11.6778659 C16.867756,10.7876271 17.0884566,9.42760861 16.5254202,8.3015358 L15.8928491,7.03639343 C15.8688153,6.98832598 15.8371895,6.9444475 15.7991889,6.90644684 C15.6039267,6.71118469 15.2873442,6.71118469 15.0920821,6.90644684 L13.4995401,8.49898884 C13.0544207,8.94410821 12.9440704,9.62411747 13.2255886,10.1871539 C13.5071068,10.7501903 13.3967565,11.4301996 12.9516371,11.8753189 L11.613922,13.2130341 Z"
                            fill="#000000" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span><b>333</b>
            <!-- <a href="{{ url('/citizenRegister') }}" type="button" class="btn btn-info"
                                                                            value="">{{ __('নাগরিক নিবন্ধন') }}</a> -->
        </div>
    </div>
@endauth
<!--end::User-->
</div>
</div>
<!--end::Topbar-->
</div>
{{-- @else
     @include('mobile_first_registration.non_verified_account_header')
    @endif --}}

<!--end::Container-->
</div>
