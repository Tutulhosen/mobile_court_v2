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

    .svg-icon svg {
        width: 14px;
        height: 14px;
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
                            data-placement="right" title data-original-="" aria-haspopup="true">
                            <a href="{{ url('dashboard') }}" class="navi-link  ">
                                <div class="btn-dropdown mr-2 pulse pulse-primary" style="padding-left: 0 !important;">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                                        <!--begin::Svg Icon | pathassets:/media/svg/icons/Code/Compiling.svg-->

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
                                                (স্বপ্রোণদিত)</a>
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
                                <a href="{{ route('citizen_complain.showCitizenComplain') }}" class="navi-link">
                                    <div class="btn-dropdown mr-2 pulse pulse-primary"
                                        style="padding-left: 0 !important;">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                                            <!-- SVG Icon -->
                                            <svg style="transform: scale(0.6);" width="66" height="88" viewBox="0 0 66 88" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M38.5 23.375V0H4.125C1.83906 0 0 1.83906 0 4.125V83.875C0 86.1609 1.83906 88 4.125 88H61.875C64.1609 88 66 86.1609 66 83.875V27.5H42.625C40.3563 27.5 38.5 25.6438 38.5 23.375ZM49.5 63.9375C49.5 65.0719 48.5719 66 47.4375 66H18.5625C17.4281 66 16.5 65.0719 16.5 63.9375V62.5625C16.5 61.4281 17.4281 60.5 18.5625 60.5H47.4375C48.5719 60.5 49.5 61.4281 49.5 62.5625V63.9375ZM49.5 52.9375C49.5 54.0719 48.5719 55 47.4375 55H18.5625C17.4281 55 16.5 54.0719 16.5 52.9375V51.5625C16.5 50.4281 17.4281 49.5 18.5625 49.5H47.4375C48.5719 49.5 49.5 50.4281 49.5 51.5625V52.9375ZM49.5 40.5625V41.9375C49.5 43.0719 48.5719 44 47.4375 44H18.5625C17.4281 44 16.5 43.0719 16.5 41.9375V40.5625C16.5 39.4281 17.4281 38.5 18.5625 38.5H47.4375C48.5719 38.5 49.5 39.4281 49.5 40.5625ZM66 20.9516V22H44V0H45.0484C46.1484 0 47.1969 0.429688 47.9703 1.20312L64.7969 18.0469C65.5703 18.8203 66 19.8688 66 20.9516Z" fill="#068747"/>
                                            </svg>
                                        
                                            <!-- Text -->
                                            <p class="navi-text" style="margin: 0;"> অপরাধের তথ্য</p>
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
                                            <svg style="display: inline-block; transform: scale(0.6);" width="78" height="78" viewBox="0 0 78 78" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M25.5312 59.125C27.7576 59.125 29.5625 57.3201 29.5625 55.0938C29.5625 52.8674 27.7576 51.0625 25.5312 51.0625C23.3049 51.0625 21.5 52.8674 21.5 55.0938C21.5 57.3201 23.3049 59.125 25.5312 59.125Z" fill="#068747"/>
                                                <path d="M46.2344 48.375L48.3653 46.2441L55.8705 53.7493L53.7396 55.8802L46.2344 48.375Z" fill="#068747"/>
                                                <path d="M59.6719 34.9395L61.8028 32.8085L69.308 40.3138L67.1771 42.4447L59.6719 34.9395Z" fill="#068747"/>
                                                <path d="M34.9375 55.0938C34.9375 49.9069 30.7168 45.6875 25.5312 45.6875C20.3457 45.6875 16.125 49.9069 16.125 55.0938C16.125 60.2806 20.3457 64.5 25.5312 64.5C30.7168 64.5 34.9375 60.2806 34.9375 55.0938ZM18.8125 55.0938C18.8125 51.389 21.8265 48.375 25.5312 48.375C29.236 48.375 32.25 51.389 32.25 55.0938C32.25 58.7985 29.236 61.8125 25.5312 61.8125C21.8265 61.8125 18.8125 58.7985 18.8125 55.0938Z" fill="#068747"/>
                                                <path d="M4.03125 0C1.80869 0 0 1.80869 0 4.03125V13.4375H8.0625V4.03125C8.0625 1.80869 6.25381 0 4.03125 0Z" fill="#068747"/>
                                                <path d="M21.4988 73.9062C21.4988 75.418 20.9976 76.8141 20.1523 77.9375H63.155C65.3776 77.9375 67.1863 76.1288 67.1863 73.9062V72.5625H21.4988V73.9062Z" fill="#068747"/>
                                                <path d="M61.3477 49.3848L62.8069 47.9255L77.3652 62.4838L75.9059 63.943L61.3477 49.3848Z" fill="#068747"/>
                                                <path d="M51.6055 45.6875L59.1107 38.1823L63.9283 42.9999L56.4231 50.5051L51.6055 45.6875Z" fill="#068747"/>
                                                <path d="M61.8123 69.875V53.6344L59.4607 51.2829L58.3373 52.4062L58.7311 52.8C59.2565 53.3254 59.2565 54.1746 58.7311 54.7L54.6998 58.7313C54.4378 58.9933 54.0938 59.125 53.7498 59.125C53.4058 59.125 53.0618 58.9933 52.7998 58.7313L43.3935 49.325C42.8681 48.7996 42.8681 47.9504 43.3935 47.425L47.4248 43.3937C47.9502 42.8683 48.7994 42.8683 49.3248 43.3937L49.7185 43.7874L57.2247 36.2812L56.831 35.8875C56.3056 35.3621 56.3056 34.5129 56.831 33.9875L60.8623 29.9562C61.1243 29.6942 61.4683 29.5625 61.8123 29.5625V6.71875C61.8123 3.01403 58.7983 0 55.0935 0H9.37109C10.2244 1.12606 10.7498 2.51281 10.7498 4.03125V73.9062C10.7498 76.1288 12.5585 77.9375 14.781 77.9375C17.0036 77.9375 18.8123 76.1288 18.8123 73.9062V71.2188C18.8123 70.477 19.4129 69.875 20.156 69.875H61.8123ZM45.4615 61.744L45.913 61.0667C46.2409 60.5736 46.8563 60.3532 47.4207 60.5265C47.9878 60.6972 48.3748 61.2212 48.3748 61.8125C48.3748 63.2947 49.5801 64.5 51.0623 64.5H56.4373V67.1875H51.0623C49.1461 67.1875 47.461 66.1797 46.5097 64.668C44.8703 66.2684 42.6571 67.1875 40.3123 67.1875V64.5C42.3857 64.5 44.3113 63.4693 45.4615 61.744ZM14.781 5.375H59.1248V8.0625H14.781V5.375ZM14.781 10.75H59.1248V13.4375H14.781V10.75ZM14.781 16.125H59.1248V18.8125H14.781V16.125ZM14.781 21.5H59.1248V24.1875H14.781V21.5ZM14.781 26.875H59.1248V29.5625H14.781V26.875ZM14.781 32.25H53.7498V34.9375H14.781V32.25ZM14.781 37.625H51.0623V40.3125H14.781V37.625ZM13.4373 55.0938C13.4373 48.4261 18.862 43 25.531 43C32.2001 43 37.6248 48.4261 37.6248 55.0938C37.6248 61.7614 32.2001 67.1875 25.531 67.1875C18.862 67.1875 13.4373 61.7614 13.4373 55.0938Z" fill="#068747"/>
                                                </svg>
                                                
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
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x" >
                                            <svg style="transform: scale(0.6);" width="28"
                                                height="28" viewBox="0 0 78 78" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M76.9336 67.4426L61.7449 52.2539C61.0594 51.5684 60.1301 51.1875 59.1551 51.1875H56.6719C60.8766 45.8098 63.375 39.0457 63.375 31.6875C63.375 14.1832 49.1918 0 31.6875 0C14.1832 0 0 14.1832 0 31.6875C0 49.1918 14.1832 63.375 31.6875 63.375C39.0457 63.375 45.8098 60.8766 51.1875 56.6719V59.1551C51.1875 60.1301 51.5684 61.0594 52.2539 61.7449L67.4426 76.9336C68.8746 78.3656 71.1902 78.3656 72.607 76.9336L76.9184 72.6223C78.3504 71.1902 78.3504 68.8746 76.9336 67.4426ZM31.6875 51.1875C20.9168 51.1875 12.1875 42.4734 12.1875 31.6875C12.1875 20.9168 20.9016 12.1875 31.6875 12.1875C42.4582 12.1875 51.1875 20.9016 51.1875 31.6875C51.1875 42.4582 42.4734 51.1875 31.6875 51.1875Z"
                                                    fill="#068747" />
                                            </svg>
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
                    @endif
                    @if (globalUserInfo()->role_id == 25)
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click"
                                data-toggle="tooltip" data-placement="right" title data-original-title=""
                                aria-haspopup="true">
                                <a href="{{ route('createsizedList') }}" class="navi-link  ">
                                    <div class="btn-dropdown mr-2 pulse pulse-primary"
                                        style="padding-left: 0 !important;">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->

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
                            <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click"
                                data-toggle="tooltip" data-placement="right" title data-original-title=""
                                aria-haspopup="true">
                                <a href="{{ route('showProsecutionList') }}" class="navi-link  ">
                                    <div class="btn-dropdown mr-2 pulse pulse-primary"
                                        style="padding-left: 0 !important;">
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
                                        <svg style="display: inline-block; transform: scale(0.6);" width="47" height="75" viewBox="0 0 47 75" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0V74.1826H20.701V60.8698H27.2382V74.1826H46.9518V0H0ZM14.1631 53.837H7.62544V47.2965H14.1631V53.837ZM14.1631 40.7552H7.62544V34.2147H14.1631V40.7552ZM14.1631 27.9745H7.62544V21.434H14.1631V27.9745ZM14.1631 14.8926H7.62544V8.35217H14.1631V14.8926ZM27.2384 53.837H20.7007V47.2965H27.2384V53.837ZM27.2384 40.7552H20.7007V34.2147H27.2384V40.7552ZM27.2384 27.9745H20.7007V21.434H27.2384V27.9745ZM27.2384 14.8926H20.7007V8.35217H27.2384V14.8926ZM40.3137 53.837H33.7761V47.2965H40.3137V53.837ZM40.3137 40.7552H33.7761V34.2147H40.3137V40.7552ZM40.3137 27.9745H33.7761V21.434H40.3137V27.9745ZM33.7761 14.8926V8.35217H40.3137V14.8926H33.7761Z" fill="#068747"/>
                                            </svg>
                                            
                                        <p class="navi-text "> দাপ্তরিক</p>
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
                                        {{-- <li class="navi-item">
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
                                        </li> --}}
                                    @endif
                                    @if (globalUserInfo()->role_id == 26 || globalUserInfo()->role_id == 25)
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

                    @if (globalUserInfo()->role_id == 37 || globalUserInfo()->role_id == 38)
                        <div class="dropdown">
                            <!--begin::Toggle-->
                            <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                                <div
                                    class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">

                                        {{-- <svg style="display: inline-block; transform: scale(0.6);" width="70" height="90" viewBox="0 0 70 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.722656 2.63672V71.543C0.722656 73.0004 1.90197 74.1797 3.35938 74.1797H50.8203C52.2777 74.1797 53.457 73.0004 53.457 71.543V2.63672C53.457 1.17932 52.2777 0 50.8203 0H3.35938C1.90197 0 0.722656 1.17932 0.722656 2.63672ZM42.9102 60.8203H32.3633C30.9059 60.8203 29.7266 59.641 29.7266 58.1836C29.7266 56.7262 30.9059 55.5469 32.3633 55.5469H42.9102C44.3676 55.5469 45.5469 56.7262 45.5469 58.1836C45.5469 59.641 44.3676 60.8203 42.9102 60.8203ZM11.2695 13.3594H27.0898C28.5472 13.3594 29.7266 14.5387 29.7266 15.9961C29.7266 17.4535 28.5472 18.6328 27.0898 18.6328H11.2695C9.81213 18.6328 8.63281 17.4535 8.63281 15.9961C8.63281 14.5387 9.81213 13.3594 11.2695 13.3594ZM11.2695 23.9062H42.9102C44.3676 23.9062 45.5469 25.0856 45.5469 26.543C45.5469 28.0004 44.3676 29.1797 42.9102 29.1797H11.2695C9.81213 29.1797 8.63281 28.0004 8.63281 26.543C8.63281 25.0856 9.81213 23.9062 11.2695 23.9062ZM11.2695 34.4531H42.9102C44.3676 34.4531 45.5469 35.6324 45.5469 37.0898C45.5469 38.5472 44.3676 39.7266 42.9102 39.7266H11.2695C9.81213 39.7266 8.63281 38.5472 8.63281 37.0898C8.63281 35.6324 9.81213 34.4531 11.2695 34.4531ZM11.2695 45H42.9102C44.3676 45 45.5469 46.1793 45.5469 47.6367C45.5469 49.0941 44.3676 50.2734 42.9102 50.2734H11.2695C9.81213 50.2734 8.63281 49.0941 8.63281 47.6367C8.63281 46.1793 9.81213 45 11.2695 45Z" fill="#068747"/>
                                            <path d="M19.1797 90H66.6406C68.098 90 69.2773 88.8207 69.2773 87.3633V18.6328C69.2773 17.1754 68.098 15.9961 66.6406 15.9961H58.7305V71.543C58.7305 75.9048 55.1821 79.4531 50.8203 79.4531H16.543V87.3633C16.543 88.8207 17.7223 90 19.1797 90Z" fill="#068747"/>
                                            </svg> --}}
                                            
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
                                        <a href="{{ route('m.report')}}"
                                            class="navi-link  ">
                                        
                                            <span class="navi-text">প্রতিবেদন</span>
                                        </a>
                                    </li> 
 
                                 
                                    <li class="navi-item">
                                        <a href="{{ route('m.approvemonth') }}"
                                            class="navi-link  ">
                                
                                            <span class="navi-text">প্রতিবেদন অনুমোদন</span>
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
                                data-toggle="tooltip" data-placement="right" title="" data-original-title=""
                                aria-haspopup="true">
                                <a href="{{ route('showForms.mc') }}" class="navi-link  ">
                                    <div class="btn-dropdown mr-2 pulse pulse-primary"
                                        style="padding-left: 0 !important;">
                                        <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->

                                            <!-- <i class="fa fa-home"></i> -->
                                            <p class="navi-text">মামলার তথ্য</p>

                                        </span>
                                        <span class="pulse-ring"></span>
                                    </div>
                                </a>
                            </div>

                        </div>
                    @endif

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
                                            <span class="symbol2 ">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">মোবাইল কোর্টের মাসিক প্রতিবেদন</span>
                                        </a>
                                    </li>


                                    <li class="navi-item">
                                        <a href="{{ route('m.appealcasereport') }}" class="navi-link  ">
                                            <span class="symbol2  ">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">মোবাইল কোর্টের আপিল মামলার তথ্য</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('m.admcasereport') }}" class="navi-link  ">
                                            <span class="symbol2 ">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">ADM কোর্টের মামলার তথ্য</span>
                                        </a>
                                    </li>

                                    <li class="navi-item">
                                        <a href="{{ route('m.emcasereport') }}" class="navi-link  ">
                                            <span class="symbol2 ">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">EM কোর্টের মামলার তথ্য</span>
                                        </a>
                                    </li>


                                    <li class="navi-item">
                                        <a href="{{ route('m.courtvisitreport') }}" class="navi-link  ">
                                            <span class="symbol2 ">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">এক্সিকিউটিভ ম্যাজিস্ট্রেটের আদালত পরিদর্শন</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="{{ route('m.caserecordreport') }}" class="navi-link  ">
                                            <span class="symbol2">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i class="fas fa-gavel"></i>

                                                </span>
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
                            <a href="{{route('m.reportCorrectionList')}}" class="navi-link  ">
                            <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click" data-toggle="tooltip"
                            data-placement="right" title data-original-title="" aria-haspopup="true">
                                <div
                                    class=" btn-clean btn-dropdown mr-2 ">
                                    <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                    
                                        <p class="navi-text"> প্রতিবেদন সংশোধন</p>
                                    </span>
                                </div>
                            </div>
                            </a>
        
                            <!--begin::Dropdown-->
                            <!-- <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                          
                                 <ul class="navi navi-hover py-4">
                            
                                            <li class="navi-item">
                                                <a href="{{ route('m.report')}}"
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
                        <div class="btn  -mobile w-auto btn-clean d-flex align-items-center btn-sm px-2"
                            id="kt_quick_user_toggle" style="margin: -12px">
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
                                <i style="float: right; padding-left: 20px; padding-top: 12px;"
                                    class="fas fa-chevron-down"></i>
                                <b>

                                    {{ auth()->user()->name }}

                                </b>

                                <br>{{ roleName(globalUserInfo()->role_id)->role_name }}
                            </span>

                        </div>
                    </div>
                @else
                    <div class="tpbar_text_menu topbar-item mr-2">
                        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                            id="kt_quick_user_toggle">
                            <a href="" class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">ধারা
                                ভিত্তিক অভিযোগের ধরণ</a>
                        </div>
                    </div>
                    <div class="tpbar_text_menu topbar-item mr-2">
                        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                            id="kt_quick_user_toggle">
                            <a href="" class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">প্রসেস
                                ম্যাপ</a>
                        </div>
                    </div>
                    <div class="tpbar_text_menu tpbar_text_mlast topbar-item mr-8">
                        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                            id="kt_quick_user_toggle">
                            <a href="" class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">আইন ও
                                বিধি</a>
                        </div>
                    </div>
                    <div class="topbar-item">
                        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                            id="topbar_social_icon">
                            <a href="" class="social-svg-icon svg-icon-primary svg-icon-2x">
                                <svg style="color: rgb(109, 91, 220);" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 320 512">Copyright 2022 Fonticons, Inc. --><path
                                        d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
                                        fill="#6d5bdc"></path></svg>
                            </a>
                        </div>
                    </div>

                    <div class="topbar-item">
                        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                            id="topbar_social_icon">
                            <a href="" class="social-svg-icon svg-icon-primary svg-icon-2x">
                                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                    <path
                                        d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"
                                        fill="#6c5adc"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="topbar-item mr-8">
                        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                            id="topbar_social_icon">
                            <a href="" class="social-svg-icon svg-icon-primary svg-icon-2x">
                                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                                    <path
                                        d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z"
                                        fill="#6c5adc"></path>
                                </svg>
                            </a>
                        </div>
                    </div>




                    <div class="topbar-item">
                        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                            id="kt_quick_user_toggle">
                            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
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
                        <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                            id="kt_quick_user_toggle">
                            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
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
