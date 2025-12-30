@php
    use Illuminate\Support\Facades\Route;
@endphp

<!--APP-SIDEBAR-->
<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar" style="overflow: scroll">
        <div class="side-header">
            <a class="header-brand1" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset(settings('logo') ?? 'default/logo.svg') }}" id="header-brand-logo" alt="logo"
                    width="60" height="60">
            </a>
        </div>
        <ul class="side-menu mt-2">
            <li>
                <h3>Menu</h3>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('dashboard') ? 'has-link active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-house side-menu__icon"></i>
                    <span class=" side-menu__label">Dashboard</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('subscriptions-plans.*') ? 'active' : '' }}"
                    href="{{ route('subscriptions-plans.index') }}">
                    <i class="fa-solid fa-box side-menu__icon"></i>
                    <span class="side-menu__label">Subscription Plans</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('reviews.*') ? 'active' : '' }}"
                    href="{{ route('reviews.index') }}">
                    <i class="fa-solid fa-star side-menu__icon"></i>
                    <span class="side-menu__label">Reviews</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.category.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.category.index') }}">
                    <i class="fa-solid fa-layer-group side-menu__icon"></i>
                    <span class="side-menu__label">Category</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.theme.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.theme.index') }}">
                    <i class="fa-solid fa-layer-group side-menu__icon"></i>
                    <span class="side-menu__label">Themes</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.workout_videos.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.workout_videos.index') }}">
                    <i class="fa-solid fa-play-circle side-menu__icon"></i>
                    <span class="side-menu__label">Work-Out Videos Library</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.videos.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.videos.index') }}">
                    <i class="fa-solid fa-dumbbell side-menu__icon"></i>
                    <span class="side-menu__label">Work-Out List</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.music.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.music.index', 0) }}">
                    <i class="fa-solid fa-music side-menu__icon"></i>
                    <span class="side-menu__label">Music</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.motivational-quotes.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.motivational-quotes.index') }}">
                    <i class="fa-solid fa-quote-left side-menu__icon"></i>
                    <span class="side-menu__label">Motivational Quotes</span>
                </a>
            </li>


            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('user.info') ? 'active' : '' }}"
                    href="{{ route('user.info') }}">
                    <i class="fa-solid fa-user side-menu__icon"></i>
                    <span class="side-menu__label">User Info</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.setting.*') ? 'has-link active' : '' }}"
                    data-bs-toggle="slide" href="#">
                    <i class="fa-solid fa-gear side-menu__icon"></i>
                    <span class="side-menu__label">Settings</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a href="{{ route('admin.setting.general.index') }}" class="slide-item"><i
                                class="fa-solid fa-cogs"></i> General Settings</a></li>
                    <li><a href="{{ route('admin.setting.env.index') }}" class="slide-item"><i
                                class="fa-solid fa-code-branch"></i> Environment Settings</a></li>
                    <li><a href="{{ route('admin.setting.logo.index') }}" class="slide-item"><i
                                class="fa-solid fa-image"></i> Logo Settings</a></li>
                    <li><a href="{{ route('admin.setting.profile.index') }}" class="slide-item"><i
                                class="fa-solid fa-id-badge"></i> Profile Settings</a></li>
                    <li><a href="{{ route('admin.setting.mail.index') }}" class="slide-item"><i
                                class="fa-solid fa-envelope"></i> Mail Settings</a></li>
                    <li><a href="{{ route('admin.setting.stripe.index') }}" class="slide-item"><i
                                class="fa-brands fa-stripe"></i> Stripe Settings</a></li>
                    <li><a href="{{ route('admin.setting.firebase.index') }}" class="slide-item"><i
                                class="fa-brands fa-firefox-browser"></i> Firebase Settings</a></li>
                    <li><a href="{{ route('admin.setting.social.index') }}" class="slide-item"><i
                                class="fa-solid fa-share-nodes"></i> Social Settings</a></li>
                </ul>
            </li>
            <li>
                <h3>CMS</h3>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.page.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.page.index') }}">
                    <i class="fa-solid fa-file side-menu__icon"></i>
                    <span class="side-menu__label">Dynamic Page</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.social.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.social.index') }}">
                    <i class="fa-solid fa-link side-menu__icon"></i>
                    <span class="side-menu__label">Social Link</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item {{ request()->routeIs('admin.faq.*') ? 'has-link active' : '' }}"
                    href="{{ route('admin.faq.index') }}">
                    <i class="fa-solid fa-question-circle side-menu__icon"></i>
                    <span class="side-menu__label">FAQ</span>
                </a>
            </li>
            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="#">
                    <i class="fa-solid fa-file-alt side-menu__icon"></i>
                    <span class="side-menu__label">CMS</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li class="sub-slide">
                        <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="#"><i
                                class="fa-solid fa-house-chimney"></i> <span class="sub-side-menu__label">Home
                                Page</span><i class="sub-angle fa fa-angle-right"></i></a>
                        <ul class="sub-slide-menu">
                            <li><a href="{{ route('admin.cms.home.example.index') }}" class="sub-slide-item"><i
                                        class="fa-solid fa-puzzle-piece"></i> Example Section</a></li>
                            <li><a href="{{ route('admin.cms.home.intro.index') }}" class="sub-slide-item"><i
                                        class="fa-solid fa-info-circle"></i> Intro Section</a></li>
                            <li><a href="{{ route('admin.cms.home.about.index') }}" class="sub-slide-item"><i
                                        class="fa-solid fa-address-card"></i> About Section</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <h3>Location</h3>
            </li>
            <li class="slide">
                <a class="side-menu__item" href="#">
                    <i class="fa-solid fa-map-marker-alt side-menu__icon"></i>
                    <span class="side-menu__label">Locations</span>
                </a>
            </li>
        </ul>

    </div>
</div>
<!--/APP-SIDEBAR-->
