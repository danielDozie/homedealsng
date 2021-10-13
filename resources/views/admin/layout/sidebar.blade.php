<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main"
            aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <?php $logo = \App\CompanySetting::find(1)->logo; ?>

        @if(Auth::check())
            <a class="navbar-brand pt-0" href="{{url('home')}}">
                <img src="{{ url('images/upload/'.$logo)}}" class="navbar-brand-img" alt="...">
            </a>
            <!-- User -->
            <ul class="nav align-items-center d-md-none">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                <img alt="Image placeholder" src="{{ url('images/upload/'.Auth::user()->image) }}">
                            </span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                        </div>
                        <a href="#" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>{{ __('My profile') }}</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="ni ni-settings-gear-65"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="ni ni-calendar-grid-58"></i>
                            <span>{{ __('Activity') }}</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="ni ni-support-16"></i>
                            <span>{{ __('Support') }}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="ni ni-user-run"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                    </div>
                </li>
            </ul>
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Collapse header -->
                <div class="navbar-collapse-header d-md-none">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="#">
                                <img src="{{ url('images/upload/'.$logo) }}">
                            </a>
                        </div>
                        <div class="col-6 collapse-close">
                            <button type="button" class="navbar-toggler" data-toggle="collapse"
                                data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false"
                                aria-label="Toggle sidenav">
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Form -->
                <form class="mt-4 mb-3 d-md-none">
                    {{-- here --}}
                    <div class="input-group input-group-rounded input-group-merge">
                        <input type="search" class="form-control form-control-rounded form-control-prepended"
                            placeholder="{{ __('Search') }}" aria-label="Search">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="fa fa-search"></span>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Navigation -->
                <ul class="navbar-nav">
                    @if(Auth::check())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('home')  ? 'active' : ''}}" href="{{url('home')}}">
                            <i class="ni ni-chart-pie-35" style="color: #87889b;"></i> {{ __('Dashboard') }}
                        </a>
                    </li>
                 
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('StoreOrder')  ? 'active' : ''}}" href="{{url('StoreOrder')}}">
                            <i class="ni ni-calendar-grid-58" style="color: #87889b;"></i> {{ __('Orders') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('Location')  ? 'active' : ''}}" href="{{url('Location')}}">
                            <i class="ni ni-pin-3 " style="color: #87889b;"></i> {{ __('Locations') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('StoreCategory')  ? 'active' : ''}}" href="{{url('StoreCategory')}}">
                            <i class="ni ni-app " style="color: #87889b;"></i> {{ __('Store Category') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('StoreSubCategory')  ? 'active' : ''}}" href="{{url('StoreSubCategory')}}">
                            <i class="fas fa-list-ul" style="color: #87889b;"></i> {{ __('Store SubCategory') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('StoreShop')  ? 'active' : ''}}" href="{{url('StoreShop')}}">
                            <i class="fas fa-store-alt" style="color: #87889b;"></i> {{ __('Store Branches') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('StoreItem')  ? 'active' : ''}}" href="{{url('StoreItem')}}">
                            <i class="ni ni-app" style="color: #87889b;"></i> {{ __('Store Items') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('StoreCoupon')  ? 'active' : ''}}" href="{{url('StoreCoupon')}}">
                            <i class="fas fa-tags" style="color: #87889b;"></i> {{ __('Store Coupon') }}
                        </a>
                    </li>
              
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('Banner')  ? 'active' : ''}}" href="{{url('Banner')}}">
                            <i class="ni ni-image" style="color: #87889b;"></i> {{ __('Banner') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('Customer') || request()->is('deliveryGuys')  ? 'active' : ''}}" href="#navbar-examples" data-toggle="collapse" role="button"
                            aria-expanded="true" aria-controls="navbar-examples">
                            <i class="ni ni-circle-08" style="color: #87889b;"></i>
                            <span class="nav-link-text">{{ __('Manage Users') }}</span>
                        </a>

                        <div class="collapse {{ request()->is('Customer') || request()->is('deliveryGuys') ? 'show' : ''}}" id="navbar-examples">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('Customer')  ? 'active-tab' : ''}}" href="{{url('Customer')}}">
                                        <i class="fas fa-users" style="color: #87889b;"></i> {{ __('Users') }}
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('deliveryGuys')  ? 'active-tab' : ''}}" href="{{url('deliveryGuys')}}">
                                        <i class="fas fa-truck-moving" style="color: #87889b;"></i>
                                        {{ __('Delivery Guys') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>                             
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('customerReport') || request()->is('storeRevenueReport') ? 'active' : ''}}" href="#report-expand" data-toggle="collapse" role="button"
                            aria-expanded="true" aria-controls="report-expand">
                            <i class="fas fa-chart-bar" style="color: #87889b;"></i>
                            <span class="nav-link-text">{{ __('Reports') }}</span>
                        </a>
                 
                        <div class="collapse {{ request()->is('customerReport') || request()->is('storeRevenueReport') ? 'show' : ''}}" id="report-expand">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('customerReport')  ? 'active-tab' : ''}}" href="{{url('customerReport')}}">
                                            <i class="ni ni-paper-diploma" style="color: #87889b;"></i> {{ __('Customer Report') }}
                                        </a>
                                    </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('storeRevenueReport')  ? 'active-tab' : ''}}" href="{{url('storeRevenueReport')}}">
                                        <i class="ni ni-chart-pie-35" style="color: #87889b;"></i>
                                        {{ __('Revenue Report') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('NotificationTemplate')  ? 'active' : ''}}" href="{{url('NotificationTemplate')}}">
                            <i class="fas fa-bell" style="color: #87889b;"></i> {{ __('Notification Setting') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('Faq')  ? 'active' : ''}}" href="{{url('Faq')}}">
                            <i class="fa fa-question-circle" style="color: #87889b;"></i> {{ __('FAQ') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('user-request')  ? 'active' : ''}}" href="{{url('user-request')}}">
                            <i class="fa fa-paper-plane" style="color: #87889b;"></i> {{ __('User Request') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('OwnerSetting')  ? 'active' : ''}}" href="{{url('OwnerSetting')}}">
                            <i class="ni ni-settings-gear-65" style="color: #87889b;"></i> {{ __('Setting') }}
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
</nav>