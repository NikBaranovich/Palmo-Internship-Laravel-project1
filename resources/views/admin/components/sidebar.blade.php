<aside class="sidebar">
    <h2>Меню</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link{{ Request::url() === route('admin.dashboard') ? ' active' : '' }}"
                href="{{ route('admin.dashboard') }}">
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ Request::url() === route('admin.users.index') ? ' active' : '' }}"
                href="{{ route('admin.users.index') }}">
                Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ Request::url() === route('admin.entertainment_venues.index') ? ' active' : '' }}"
                href="{{ route('admin.entertainment_venues.index') }}">
                Entertainment Venues
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ Request::url() === route('admin.events.index') ? ' active' : '' }}"
                href="{{ route('admin.events.index') }}">
                Events
            </a>
        </li>
    </ul>
</aside>
