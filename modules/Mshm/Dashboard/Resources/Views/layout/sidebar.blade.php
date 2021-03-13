<div class="sidebar__nav border-top border-left  ">
    <span class="bars d-none padding-0-18"></span>
    <a class="header__logo  d-none" href="https://webamooz.net"></a>
    <!--suppress HtmlUnknownTag, CheckEmptyScriptTag -->
    <x-user-photo/>
    <br>
    <ul>
        @foreach(config('sidebar.items') as $sidebarItem)
            @if (!array_key_exists('permission',$sidebarItem) ||
                  auth()->user()->hasAnyPermission($sidebarItem['permission']) ||
                  auth()->user()->hasPermissionTo(\Mshm\RolePermissions\Models\Permission::PERMISSION_SUPER_ADMIN))
                <li class="item-li {{ $sidebarItem['icon'] }}
                @if(str_starts_with(request()->url(),$sidebarItem['url'])) is-active @endif">
                    <a
                        href="{{ $sidebarItem['url'] }}">{{ $sidebarItem['title'] }}</a></li>
            @endif
        @endforeach
    </ul>

</div>
