@php
    $baseActive = request()->routeIs(
        'user.olt.index',
        'user.olt_port.index',
        'user.otb.index',
        'user.odc.index',
        'user.odp.index',
        'user.pelanggan.index'
    );

    $odpPortActive = request()->routeIs('user.odp-port.*');
@endphp

<!-- Sidebar -->
<div class="sidebar sidebar-style-2">			
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">

                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item {{ $baseActive ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#base" {{ $baseActive ? 'aria-expanded=true' : '' }}>
                        <i class="fas fa-database"></i>
                        <p>Master Data</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $baseActive ? 'show' : '' }}" id="base">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('user.olt.index') ? 'active' : '' }}">
                                <a href="{{ route('user.olt.index') }}">
                                    <span class="sub-item">OLT</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('user.olt_port.index') ? 'active' : '' }}">
                                <a href="{{ route('user.olt_port.index') }}">
                                    <span class="sub-item">OLT Port</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('user.otb.index') ? 'active' : '' }}">
                                <a href="{{ route('user.otb.index') }}">
                                    <span class="sub-item">OTB</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('user.odc.index') ? 'active' : '' }}">
                                <a href="{{ route('user.odc.index') }}">
                                    <span class="sub-item">ODC</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('user.odp.index') ? 'active' : '' }}">
                                <a href="{{ route('user.odp.index') }}">
                                    <span class="sub-item">ODP</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('user.pelanggan.index') ? 'active' : '' }}">
                                <a href="{{ route('user.pelanggan.index') }}">
                                    <span class="sub-item">Pelanggan</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->routeIs('user.pelanggan.map') ? 'active' : '' }}">
                    <a href="{{ route('user.pelanggan.map') }}">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Sebaran Pelanggan</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('user.pelanggan.sebaran-odp') ? 'active' : '' }}">
                    <a href="{{ route('user.pelanggan.sebaran-odp') }}">
                        <i class="fas fa-map"></i>
                        <p>Sebaran ODP</p>
                    </a>
                </li>

                <li class="nav-item {{ $odpPortActive ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#odpPortMenu" {{ $odpPortActive ? 'aria-expanded=true' : '' }}>
                        <i class="fas fa-th-large"></i>
                        <p>ODP Port</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $odpPortActive ? 'show' : '' }}" id="odpPortMenu">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('user.odp-port.assign.form') ? 'active' : '' }}">
                                <a href="{{ route('user.odp-port.assign.form') }}">
                                    <span class="sub-item">Assign Pelanggan</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('user.odp-port.monitoring') ? 'active' : '' }}">
                                <a href="{{ route('user.odp-port.monitoring') }}">
                                    <span class="sub-item">Monitoring Port</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
