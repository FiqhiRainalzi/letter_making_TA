@auth
    <aside id="sidebar" class="sidebar">
        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.home') ? '' : 'collapsed' }}" href="{{ route('admin.home') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>


            @if (Auth::user()->role == 'admin')
                <!-- Admin Nav -->
                <li class="nav-heading">Surat</li>
                {{-- Dropdown for Surat Tugas HKI --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.hkiView') ? '' : 'collapsed' }}"
                        data-bs-target="#aHki-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Surat Tugas HKI</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="aHki-nav" class="nav-content collapse {{ Request::routeIs('admin.hkiView') ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('admin.hkiView') }}"
                                class="{{ Request::routeIs('admin.hkiView') ? 'active' : '' }}">Surat HKI</a>
                        </li>
                    </ul>
                </li>

                {{-- Dropdown for Surat Tugas Pengabdian Kepada Masyarakat --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.penelitianView', 'admin.pkmView') ? '' : 'collapsed' }}"
                        data-bs-target="#aPKM-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Surat Tugas Pengabdian Kepada Masyarakat</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="aPKM-nav"
                        class="nav-content collapse {{ Request::routeIs('admin.penelitianView', 'admin.pkmView') ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('admin.penelitianView') }}"
                                class="{{ Request::routeIs('admin.penelitianView') ? 'active' : '' }}">Surat Tugas
                                Penelitian</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.pkmView') }}"
                                class="{{ Request::routeIs('admin.pkmView') ? 'active' : '' }}">Surat Tugas PKM</a>
                        </li>
                    </ul>
                </li>


                {{-- Dropdown for Surat Tugas Publikasi --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.ketpubView', 'admin.tugaspubView') ? '' : 'collapsed' }}"
                        data-bs-target="#aPub-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Surat Tugas Publikasi</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="aPub-nav"
                        class="nav-content collapse {{ Request::routeIs('admin.ketpubView', 'admin.tugaspubView') ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('admin.ketpubView') }}"
                                class="{{ Request::routeIs('admin.ketpubView') ? 'active' : '' }}">Surat Keterangan
                                Publikasi</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.tugaspubView') }}"
                                class="{{ Request::routeIs('admin.tugaspubView') ? 'active' : '' }}">Surat Tugas Publikasi
                                Nasional</a>
                        </li>
                    </ul>
                </li>

                <li class="nav-heading">Halaman</li>
                {{-- Akun Pengguna --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('admin.akunPengguna') ? '' : 'collapsed' }}"
                        href="{{ route('admin.akunPengguna') }}">
                        <i class="bi bi-person-fill"></i>
                        <span>Akun Pengguna</span>
                    </a>
                </li>

                <!-- End Admin Nav -->
            @elseif (Auth::user()->role == 'dosen')
                <!-- Dosen Nav -->
                <li class="nav-heading">Surat Tugas P3M</li>
                {{-- Dropdown for Surat Tugas HKI --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('hki.index') ? '' : 'collapsed' }}" data-bs-target="#hkidos-nav"
                        data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Surat Tugas HKI</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="hkidos-nav" class="nav-content collapse {{ Request::routeIs('hki.index') ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="{{ route('hki.index') }}"
                                class="{{ Request::routeIs('hki.index') ? 'active' : '' }}">Surat HKI</a>
                        </li>
                    </ul>
                </li>

                {{-- Dropdown for Surat Tugas PKM --}}
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('penelitian.index', 'pkm.index') ? '' : 'collapsed' }}"
                        data-bs-target="#pkm-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Surat Tugas Pengabdian Kepada Masyarakat</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="pkm-nav"
                        class="nav-content collapse {{ Request::routeIs('penelitian.index', 'pkm.index') ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        <li><a href="{{ route('penelitian.index') }}"
                                class="{{ Request::routeIs('penelitian.index') ? 'active' : '' }}">Surat Tugas
                                Penelitian</a></li>
                        <li><a href="{{ route('pkm.index') }}"
                                class="{{ Request::routeIs('pkm.index') ? 'active' : '' }}">Surat Tugas PKM</a></li>
                    </ul>
                </li>

                {{-- Dropdown for Surat Tugas Publikasi --}}
                <li class="nav-item">
                    <a class="nav-link  {{ Request::routeIs('ketpub.index', 'tugaspub.index') ? '' : 'collapsed' }}"
                        data-bs-target="#publikasi-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-journal-text"></i><span>Surat Tugas Publikasi</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="publikasi-nav"
                        class="nav-content collapse {{ Request::routeIs('ketpub.index', 'tugaspub.index') ? 'show' : '' }}"
                        data-bs-parent="#sidebar-nav">
                        <li><a href="{{ route('ketpub.index') }}"
                                class="{{ Request::routeIs('ketpub.index') ? 'active' : '' }}">Surat Keterangan
                                Publikasi</a></li>
                        <li><a href="{{ route('tugaspub.index') }}"
                                class="{{ Request::routeIs('tugaspub.index') ? 'active' : '' }}">Surat Tugas Publikasi
                                Nasional</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </aside><!-- End Sidebar -->
@endauth
