

<style type="text/css">
    .notification {
        position: absolute;
        top: 0;
        right: 40px;
    }
</style>

<div id="kt_header" class="header header-fixed">
    <!--begin::Container-->
    {{-- @if(citizen_auth_menu()) --}}
    <div class="container align-items-stretch justify-content-between">
        
        <!--begin::Topbar-->
        <div class="topbar_wrapper">
            <div class="topbar">
                
                @auth
                  
                <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-offset="10px,0px" data-menu-toggle="click" data-toggle="tooltip"
                        data-placement="right" title data-original-title="" aria-haspopup="true">
                        <a href=" "
                            class="navi-link  ">
                            <div class="btn-dropdown mr-2 pulse pulse-primary" style="padding-left: 0 !important;">
                                <span class="svg-icon auth-svg-icon-bar svg-icon-xl svg-icon-primary">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Compiling.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24">
                                            </rect>
                                            <rect fill="#000000" x="4" y="4" width="7"
                                                height="7" rx="1.5"></rect>
                                            <path
                                                d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
                                                fill="#000000" opacity="0.3"></path>
                                        </g>
                                    </svg>
                                    
                                    <p class="navi-text">ড্যাশবোর্ড</p>
                                </span>
                                <span class="pulse-ring"></span>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- <div class="dropdown">
                   
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                        <div
                            class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <p class="navi-text">খবর</p>
                            </span>
                        </div>
                    </div>
                   
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                        
                        <ul class="navi navi-hover py-4">
                        

                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                  
                                                </span>
                                            </span>
                                            <span class="navi-text">খবর</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                
                                                </span>
                                            </span>
                                            <span class="navi-text">খবর তালিকা</span>
                                        </a>
                                    </li>
                                    
                                    
                      
                        </ul>
                        
                    </div>
                   
                </div> -->
                <div class="dropdown">
                    <!--begin::Toggle-->

                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                        <div
                            class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <p class="navi-text">ইউজার</p>
                            </span>
                        </div>
                    </div>

                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Nav-->
                        <ul class="navi navi-hover py-4">
                            <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href="{{ route('admin.doptor.management.user_list.segmented.all', [
                                     'office_id' => encrypt('5') ]) }}"
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">ইউজার</span>
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
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <p class="navi-text">কর্মসূচি প্রণয়ন</p>
                            </span>
                        </div>
                    </div>

                   
                </div>

                <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                        <div
                            class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg> -->
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
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">স্বপ্রণোদিত কোর্ট</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">অসম্পূর্ণ মামলা(স্বপ্রণোদিত)</span>
                                        </a>
                                    </li> 
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">আসামি ছাড়া মামলা</span>
                                            
                                        </a>
                                        
                                        
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">প্রসিকিউশনের তালিকা</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">অসম্পূর্ণ মামলা(প্রসিকিউশনসহ)</span>
                                        </a>
                                    </li> 
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">মামলার তথ্য</span>
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
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg> -->
                                <p class="navi-text">ম্যানুয়াল</p>
                            </span>
                        </div>
                    </div>
                    <!--begin::Dropdown-->
                    {{--<div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Nav-->
                         <ul class="navi navi-hover py-4">
                            <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">সেটিংস</span>
                                        </a>
                                    </li>   
                            <!--end::Item-->
                        </ul> 
                        <!--end::Nav-->
                    </div>--}}
                    <!--end::Dropdown-->
                </div>
                
                <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                        <div
                            class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg> -->
                                <p class="navi-text"> অপরাধের তথ্য</p>
                            </span>
                        </div>
                    </div>
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Nav-->
                         <ul class="navi navi-hover py-4">
                            <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">অভিযোগ  সংযুক্তিকরণ </span>
                                        </a>
                                    </li> 
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
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
                <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                        <div
                            class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg> -->
                                <p class="navi-text"> অনুসন্ধান</p>
                            </span>
                        </div>
                    </div>
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Nav-->
                         <ul class="navi navi-hover py-4">
                            <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">মামলা / অভিযোগ অনুসন্ধান</span>
                                        </a>
                                    </li> 
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">আসামি  অনুসন্ধান</span>
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
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg> -->
                                <p class="navi-text"> দাপ্তরিক</p>
                            </span>
                        </div>
                    </div>
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Nav-->
                         <ul class="navi navi-hover py-4">
                            <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">রেজিস্টার</span>
                                        </a>
                                    </li> 
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">খবর আপলোড</span>
                                        </a>
                                    </li>    
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">খবর   তালিকা</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">গল্প আপলোড </span>
                                        </a>
                                    </li>    
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">গল্পের তালিকা</span>
                                        </a>
                                    </li> 
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">কর্মস্থল (অধিক্ষেত্র) পরিবর্তন</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text"> বাতিলকৃত  মামলা  </span>
                                        </a>
                                    </li>
                            <!--end::Item-->
                        </ul> 
                        <!--end::Nav-->
                    </div>
                    <!--end::Dropdown-->
                </div>
{{------------
                <div class="dropdown">
                    <!--begin::Toggle-->
                    <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
                        <div
                            class=" btn-clean btn-dropdown mr-2 {{ request()->is('peshkar/adm/dm/list', 'doptor/user/management/office_list') ? 'menu-item-active' : '' }}">
                            <span class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                                <p class="navi-text">সেটিংস</p>
                            </span>
                        </div>
                    </div>
                    <!--begin::Dropdown-->
                    <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Nav-->
                        <ul class="navi navi-hover py-4">
                            <!--begin::Item-->
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">সেটিংস</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">ADM/DM/DIV_COM</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">ACJM Profile</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">প্রসিকিউটর</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">এক্সিকিউটিভ ম্যাজিস্ট্রেট </span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href=" "
                                            class="navi-link  ">
                                            <span class="symbol2 symbol-20 mr-3">
                                                <span
                                                    class="svg-icon auth-svg-icon-bar svg-icon-primary svg-icon-2x">
                                                    <i style="color: #3699ff; margin-top: 0 !important;"
                                                        class="fas fa-user"></i>
                                                    <!--end::Svg Icon-->
                                                </span>
                                            </span>
                                            <span class="navi-text">ম্যাজিস্ট্রেটগণের  ক্ষমতা  অর্পন </span>
                                        </a>
                                    </li>
                                    
                            <!--end::Item-->
                        </ul>
                        <!--end::Nav-->
                    </div>
                    <!--end::Dropdown-->
                </div>
------------}}               
<!-- <div class="dropdown">
  <button  class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" title="">
    Dropdown button
  </button>
  <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
    <li><a class="dropdown-item" href="#">Action</a></li>
    <li>
      <a class="dropdown-item" href="#">Another action</a>
    </li>
    <li>
      <a class="dropdown-item" href="#">
        Submenu &raquo;
      </a>
      <ul class="dropdown-menu dropdown-submenu">
        <li>
          <a class="dropdown-item" href="#">Submenu item 1</a>
        </li>
        <li>
          <a class="dropdown-item" href="#">Submenu item 2</a>
        </li>
        <li>
          <a class="dropdown-item" href="#">Submenu item 3 &raquo; </a>
          <ul class="dropdown-menu dropdown-submenu">
            <li>
              <a class="dropdown-item" href="#">Multi level 1</a>
            </li>
            <li>
              <a class="dropdown-item" href="#">Multi level 2</a>
            </li>
          </ul>
        </li>
        <li>
          <a class="dropdown-item" href="#">Submenu item 4</a>
        </li>
        <li>
          <a class="dropdown-item" href="#">Submenu item 5</a>
        </li>
      </ul>
    </li>
  </div>
</div> -->

                <div class="topbar-item">
                    <div class="btn  -mobile w-auto btn-clean d-flex align-items-center btn-sm px-2"
                        id="kt_quick_user_toggle" style="margin: -12px">
                        <span class="text-muted font-size-base d-none d-md-inline mr-1">
                            @if (Auth::user()->profile_pic != null)
                                @if (Auth::user()->doptor_user_flag == 1)
                                    <img src="{{ Auth::user()->profile_pic }}">
                                @else
                                    <img
                                        src="{{ url('/') }}/uploads/profile/{{ Auth::user()->profile_pic }}">
                                @endif
                            @else
                                <img src="{{ url('/') }}/uploads/profile/default.jpg">
                            @endif

                        </span>
                        <span class="text-dark font-size-base d-none d-md-inline mr-3 text-left">
                            <i style="float: right; padding-left: 20px; padding-top: 12px;"
                                class="fas fa-chevron-down"></i>
                            <b>
                            {{--  @if (count($nameWords) > 2)
                                {{ $nameWords[0] }} {{$nameWords[1]}}
                            @else
                                {{ auth()->user()->name }}
                            @endif --}}
                                </b>
                                
                                <br>{{-- Auth::user()->role->role_name --}}
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
                                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-twitter"
                                    viewBox="0 0 16 16">
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
                                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-youtube"
                                    viewBox="0 0 16 16">
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
                                <svg style="color: rgb(108, 90, 220);" xmlns="http://www.w3.org/2000/svg"
                                    width="16" height="16" fill="currentColor" class="bi bi-play-fill"
                                    viewBox="0 0 16 16">
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
