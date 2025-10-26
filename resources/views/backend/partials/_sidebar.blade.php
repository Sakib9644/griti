@php
use Illuminate\Support\Facades\Route;
@endphp

<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar" style="overflow: scroll">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset(settings('logo') ?? 'default/logo.svg') }}" id="header-brand-logo" alt="logo" width="{{ settings('logo_width') ?? 100 }}" height="{{ settings('logo_height') ?? 100 }}">
            </a>
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                    fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg>
            </div>
            <ul class="side-menu mt-2">
                <li>
                    <h3>Menu</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{  request()->routeIs('dashboard') ? 'has-link active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-house side-menu__icon"></i>
                        <span class=" side-menu__label">Dashboard</span>
                    </a>
                </li>
          <li class="slide">
                    <a class="side-menu__item {{ request()->routeIs('subscriptions-plans.*') ? 'active' : '' }}"
                        href="{{ route('subscriptions-plans.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17.93c-2.83.48-5.52-.41-7.54-2.43-2.02-2.02-2.9-4.71-2.43-7.54C4.09 7.46 7.46 4.09 12 4.09c4.54 0 7.91 3.37 7.97 7.97 0 .16-.01.31-.02.47-1.06-.3-2.16-.46-3.22-.46-1.59 0-3.14.46-4.43 1.26v5.52h1.7v-4.51c.58-.35 1.23-.58 1.89-.69v3.96h1.7v-3.68c.34.11.68.24 1 .39v3.29h1.7v-5.51c-1.28-.63-2.75-1-4.28-1-1.61 0-3.17.44-4.51 1.26v5.56z" />
                        </svg>
                        <span class="side-menu__label">Subscription Plans</span>
                    </a>
                </li>
              

                <li>
                    <h3>CMS</h3>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{  request()->routeIs('admin.page.*') ? 'has-link active' : '' }}" href="{{ route('admin.page.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="side-menu__icon" viewBox="0 0 16 16">
                            <path d="M15 14l-5-5-5 5v-3l10 -10z" />
                        </svg>
                        <span class="side-menu__label">Dynamic Page</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{  request()->routeIs('admin.social.*') ? 'has-link active' : '' }}" href="{{ route('admin.social.index') }}">
                        <i class="fa-solid fa-link side-menu__icon"></i>
                        <span class="side-menu__label">Social Link</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item {{  request()->routeIs('admin.faq.*') ? 'has-link active' : '' }}" href="{{ route('admin.faq.index') }}">
                        <i class="fa-solid fa-clipboard-question side-menu__icon"></i>
                        <span class="side-menu__label">FAQ</span>
                    </a>
                </li>

                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="side-menu__icon" viewBox="0 0 16 16">
                            <path d="M7.5 5.5a.5.5 0 0 0-1 0v.634l-.549-.317a.5.5 0 1 0-.5.866L6 7l-.549.317a.5.5 0 1 0 .5.866l.549-.317V8.5a.5.5 0 1 0 1 0v-.634l.549.317a.5.5 0 1 0 .5-.866L8 7l.549-.317a.5.5 0 1 0-.5-.866l-.549.317zm-2 4.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z" />
                            <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
                        </svg>
                        <span class="side-menu__label">CMS</span><i class="angle fa fa-angle-right"></i>
                    </a>
                    <ul class="slide-menu">
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="#"><span
                                    class="sub-side-menu__label">Home Page</span><i
                                    class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a href="{{ route('admin.cms.home.example.index') }}" class="sub-slide-item">Example Section</a></li>
                                <li><a href="{{ route('admin.cms.home.intro.index') }}" class="sub-slide-item">Intro Section</a></li>
                                <li><a href="{{ route('admin.cms.home.about.index') }}" class="sub-slide-item">About Section</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>


                <li>
                    <h3>Location</h3>
                </li>


            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg"
                    fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg>
            </div>
        </div>
    </div>
</div>
<!--/APP-SIDEBAR-->
