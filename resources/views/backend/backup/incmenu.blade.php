<!-- me add -->
<!-- backup and restore Manager -->
{{--                @can('backup_restore_settings')--}}

{{--                                        for create and store ftp information in env file--}}
{{--                                        <li class="aiz-side-nav-item">--}}
{{--                                            <a href="{{route('backup_restore_settings.index')}}" class="aiz-side-nav-link">--}}
{{--                                                <i class="las la-database aiz-side-nav-icon"></i>--}}
{{--                                                <span class="aiz-side-nav-text">{{translate('backup and restore')}}</span>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}

{{--                @endcan--}}
{{--@dd(addon_is_activated('backup_restore_system') && auth()->user()->can('backup_restore_system'))--}}
@if (addon_is_activated('backup_restore_system') && auth()->user()->can('backup_restore'))
    {{--                @if (addon_is_activated('backup_restore_system') )--}}
    @canany(['create_backup','delete_backup','download_backup','restore_backup','share_backup','backup_restore'])
        <li class="aiz-side-nav-item">
            <a href="{{route('backups')}}" class="aiz-side-nav-link">
                {{--                                                        <i class="las la-database aiz-side-nav-icon"></i>--}}

                <div class="aiz-side-nav-icon">
                    <svg fill="#eae206" width="18" height="18" viewBox="0 0 36 36"  preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>backup-restore-outline-badged</title>
                        <rect class="clr-i-outline--badged clr-i-outline-path-1--badged" x="6" y="22" width="24" height="2"></rect><rect class="clr-i-outline--badged clr-i-outline-path-2--badged" x="26" y="26" width="4" height="2"></rect>
                        <path class="clr-i-outline--badged clr-i-outline-path-3--badged"
                              d="M13,9.92,17,6V19a1,1,0,1,0,2,0V6l4,3.95a1,1,0,0,0,.71.29l.11,0a7.46,7.46,0,0,1-1.25-3.52L18,2.16,11.61,8.5A1,1,0,0,0,13,9.92Z"></path>
                        <path class="clr-i-outline--badged clr-i-outline-path-4--badged" d="M30.87,13.45a7.55,7.55,0,0,1-.87.05A7.46,7.46,0,0,1,25.51,12H21v2h7.95C30,16.94,31.72,21.65,32,22.48V30H4V22.48C4.28,21.65,7.05,14,7.05,14H15V12H7.07a1.92,1.92,0,0,0-1.9,1.32C2,22,2,22.1,2,22.33V30a2,2,0,0,0,2,2H32a2,2,0,0,0,2-2V22.33C34,22.1,34,22,30.87,13.45Z"></path>
                        <circle class="clr-i-outline--badged clr-i-outline-path-5--badged clr-i-badge" cx="30" cy="6" r="5">
                        </circle>
                        <rect x="0" y="0" width="36" height="36" fill-opacity="0"/>
                    </svg>
                </div>

                <span class="aiz-side-nav-text">{{translate('backup and restore')}}</span>
                @if (env("DEMO_MODE") == "On")
                    <span class="badge badge-inline badge-danger">Addon</span>
                @endif
            </a>
        </li>


        {{--                    <li class="aiz-side-nav-item">--}}
        {{--                        <a href="#" class="aiz-side-nav-link">--}}
        {{--                            <i class="las la-database aiz-side-nav-icon"></i>--}}
        {{--                            <span class="aiz-side-nav-text">{{translate('backup & restore')}}</span>--}}
        {{--                            <span class="aiz-side-nav-arrow"></span>--}}
        {{--                        </a>--}}
        {{--                        <ul class="aiz-side-nav-list level-2">--}}
        {{--                                <li class="aiz-side-nav-item">--}}
        {{--                                    <a href="{{ route('backups') }}" class="aiz-side-nav-link">--}}
        {{--                                        <i class="las la-database aiz-side-nav-icon"></i>--}}
        {{--                                        <span class="aiz-side-nav-text">{{translate('backup and restore')}}</span>--}}
        {{--                                    </a>--}}
        {{--                                </li>--}}
        {{--                                <li class="aiz-side-nav-item">--}}
        {{--                                    <a href="{{route('aecfilemanager')}}" class="aiz-side-nav-link">--}}
        {{--                                        <i class="las la-database aiz-side-nav-icon"></i>--}}
        {{--                                        <span class="aiz-side-nav-text">{{translate('File manager')}}</span>--}}
        {{--                                    </a>--}}
        {{--                                </li>--}}
        {{--                        </ul>--}}
        {{--                    </li>--}}



    @endcanany

@endif

<!-- me end -->

