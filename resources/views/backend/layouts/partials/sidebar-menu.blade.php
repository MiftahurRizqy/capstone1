@php $user = Auth::user(); @endphp
<nav
    x-data="{
        isDark: document.documentElement.classList.contains('dark'),
        textColor: '',
        init() {
            this.updateColor();
            const observer = new MutationObserver(() => this.updateColor());
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        },
        updateColor() {
            this.isDark = document.documentElement.classList.contains('dark');
            this.textColor = this.isDark 
                ? '{{ config('settings.sidebar_text_dark') }}' 
                : '{{ config('settings.sidebar_text_lite') }}';
        }
    }"
    x-init="init()">


    <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
            {{ __('Menu') }}
        </h3>

        <ul class="flex flex-col gap-4 mb-6">
            @can('dashboard.view')
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-item group {{ Route::is('admin.dashboard') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    <i class="bi bi-grid text-xl text-center"></i>

                    <span :style="`color: ${textColor}`">
                        {{ __('Dashboard') }}
                    </span>
                </a>
            </li>
            @endcan
            @php echo ld_apply_filters('sidebar_menu_after_dashboard', '') @endphp

            @can('pelanggan.view')
            <li>
                <a href="{{ route('admin.pelanggan.index') }}"
                    class="menu-item group w-full text-left {{ Route::is('admin.pelanggan.*') ? 'menu-item-active' : 'menu-item-inactive text-white' }}">
                    
                    <i class="bi bi-people text-xl text-center"></i>
                    <span :style="`color: ${textColor}`">Pelanggan</span>
                </a>
            </li>
            @endcan

            <!-- Menu Jaringan -->
            @can('jaringan.view')
            <li>
                <button
                    class="menu-item group w-full text-left {{ Route::is('admin.jaringan.*') ? 'menu-item-active' : 'menu-item-inactive text-white' }}"
                    type="button" onclick="toggleSubmenu('jaringan-submenu')">
                    <i class="bi bi-diagram-3 text-xl text-center"></i>
                    <span :style="`color: ${textColor}`">Jaringan</span>
                    <i class="bi bi-chevron-down ml-auto"></i>
                </button>
                <ul id="jaringan-submenu"
                    class="submenu {{ Route::is('admin.jaringan.*') ? '' : 'hidden' }} pl-12 mt-2 space-y-2">
                    <li>
                        <a href="{{ route('admin.jaringan.pop.index') }}"
                            class="block px-4 py-2 rounded-lg {{ Route::is('admin.jaringan.pop.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            {{ __('POP') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.jaringan.wilayah.index') }}"
                            class="block px-4 py-2 rounded-lg {{ Route::is('admin.jaringan.wilayah.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            {{ __('Wilayah') }}
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            <!-- Menu Layanan -->
            @can('layanan.view')
            <li>
                <button
                    class="menu-item group w-full text-left {{ Route::is('admin.layanan.*') ? 'menu-item-active' : 'menu-item-inactive text-white' }}"
                    type="button" onclick="toggleSubmenu('layanan-submenu')">
                    <i class="bi bi-router-fill text-xl text-center"></i>
                    <span :style="`color: ${textColor}`">Layanan</span>
                    <i class="bi bi-chevron-down ml-auto"></i>
                </button>
                <ul id="layanan-submenu"
                    class="submenu {{ Route::is('admin.layanan.*') ? '' : 'hidden' }} pl-12 mt-2 space-y-2">
                    <li>
                        <a href="{{ route('admin.layanan.entry.index') }}"
                            class="block px-4 py-2 rounded-lg {{ Route::is('admin.layanan.entry.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            {{ __('Layanan Entry') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.layanan.induk.index') }}"
                            class="block px-4 py-2 rounded-lg {{ Route::is('admin.layanan.induk.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            {{ __('Layanan Induk') }}
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            <!-- Menu Keluhan -->
            @can('keluhan.view')
            <li>
                <a href="{{ route('admin.keluhan.index') }}"
                    class="menu-item group w-full text-left {{ Route::is('admin.keluhan.*') ? 'menu-item-active' : 'menu-item-inactive text-white' }}">
                    <i class="bi bi-chat-dots text-xl text-center"></i>
                    <span :style="`color: ${textColor}`">Keluhan</span>
                </a>
            </li>
            @endcan

            <!-- Menu SPK -->
            @can('spk.view')
            <li>
                <a href="{{ route('admin.spk.index') }}"
                    class="menu-item group w-full text-left {{ Route::is('admin.spk.*') ? 'menu-item-active' : 'menu-item-inactive text-white' }}">
                    <i class="bi bi-file-earmark-text text-xl text-center"></i>
                    <span :style="`color: ${textColor}`">SPK</span>
                </a>
            </li>
            @endcan

            <!-- Menu Invoice -->
            @can('invoice.view')
            <li>
                <a href="{{ route('admin.invoice.index') }}"
                    class="menu-item group w-full text-left {{ Route::is('admin.invoice.*') ? 'menu-item-active' : 'menu-item-inactive text-white' }}">
                    <i class="bi bi-receipt-cutoff text-xl text-center"></i>
                    <span :style="`color: ${textColor}`">Invoice</span>
                </a>
            </li>
            @endcan

            @canany(['user.create', 'user.view', 'user.edit', 'user.delete'])
            <li>
                <button
                    class="menu-item group w-full text-left {{ Route::is('admin.users.*') ? 'menu-item-active' : 'menu-item-inactive text-white' }}"
                    type="button" onclick="toggleSubmenu('users-submenu')">
                    <i class="bi bi-person text-xl text-center"></i>
                    <span :style="`color: ${textColor}`">Users</span>
                    <i class="bi bi-chevron-down ml-auto"></i>
                </button>
                <ul id="users-submenu"
                    class="submenu {{ Route::is('admin.users.*') ? '' : 'hidden' }} pl-12 mt-2 space-y-2">
                    @can('user.view')
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="block px-4 py-2 rounded-lg {{ Route::is('admin.users.index') || Route::is('admin.users.edit') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            {{ __('Users') }}
                        </a>
                    </li>
                    @endcan
                    @can('user.create')
                    <li>
                        <a href="{{ route('admin.users.create') }}"
                            class="block px-4 py-2 rounded-lg {{ Route::is('admin.users.create') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            {{ __('New User') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany
            @php echo ld_apply_filters('sidebar_menu_after_users', '') @endphp

            @canany(['role.create', 'role.view', 'role.edit', 'role.delete'])
            <li>
                <button
                    class="menu-item group w-full text-left {{ Route::is('admin.roles.*') ? 'menu-item-active' : 'menu-item-inactive text-white' }}"
                    type="button" onclick="toggleSubmenu('roles-submenu')">
                    <i class="bi bi-shield-check text-xl text-center"></i>
                    <span :style="`color: ${textColor}`">{{ __('Roles & Permissions') }}</span>
                    <i class="bi bi-chevron-down ml-auto"></i>
                </button>
                <ul id="roles-submenu"
                    class="submenu {{ Route::is('admin.roles.*') ? '' : 'hidden' }} pl-12 mt-2 space-y-2">
                    @can('role.view')
                    <li>
                        <a href="{{ route('admin.roles.index') }}"
                            class="block px-4 py-2 rounded-lg {{ Route::is('admin.roles.index') || Route::is('admin.roles.edit') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            {{ __('Roles') }}
                        </a>
                    </li>
                    @endcan
                    @can('role.create')
                    <li>
                        <a href="{{ route('admin.roles.create') }}"
                            class="block px-4 py-2 rounded-lg {{ Route::is('admin.roles.create') ? 'menu-item-active' : 'menu-item-inactive' }}">
                            {{ __('New Role') }}
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany
            @php echo ld_apply_filters('sidebar_menu_after_roles', '') @endphp




        </ul>
    </div>

    <!-- Others Group -->
    <div>
        <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
            {{ __('More') }}
        </h3> 

        <ul class="flex flex-col gap-4 mb-6">
            @if ($user->can('settings.edit'))
            <li class="menu-item-inactive rounded-md ">
                <a href="{{ route('admin.settings.index') }}" type="submit"
                    class="menu-item group w-full text-left {{ Route::is('admin.settings.index') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    <i class="bi bi-gear text-xl text-center dark:text-white/90"></i>
                    <span class="dark:text-white/90" :class="sidebarToggle ? 'lg:hidden' : ''" :style="`color: ${textColor}`">
                        {{ __('Settings') }}
                    </span>
                </a>
            </li>
            @endif
{{-- Menu Baru: Kategori Pelanggan --}}
            @can('kategori.view')
            <li class="menu-item-inactive rounded-md ">
                {{-- Pastikan route 'admin.kategori.index' sudah dibuat di web.php --}}
                <a href="{{ route('admin.kategori.index') }}" 
                   class="menu-item group w-full text-left {{ Route::is('admin.kategori.*') ? 'menu-item-active' : 'menu-item-inactive' }}">
                    {{-- Menggunakan icon tags/list --}}
                    <i class="bi bi-tags text-xl text-center dark:text-white/90"></i>
                    <span class="dark:text-white/90" :class="sidebarToggle ? 'lg:hidden' : ''" :style="`color: ${textColor}`">
                        {{ __('Kategori Pelanggan') }}
                    </span>
                </a>
            </li>
            @endcan
            <!-- Logout Menu Item -->
            <li class="menu-item-inactive rounded-md ">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="menu-item group w-full text-left">
                        <i class="bi bi-box-arrow-right text-xl text-center dark:text-white/90"></i>
                        <span class=" dark:text-white/90" :class="sidebarToggle ? 'lg:hidden' : ''" :style="`color: ${textColor}`">
                            {{ __('Logout') }}
                        </span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<script>
    function toggleSubmenu(submenuId) {
        const submenu = document.getElementById(submenuId);
        submenu.classList.toggle('hidden');
    }
</script>