<aside id="sidebar">
    <div class="sidebar-title">
        <div class="sidebar-brand">
            StagePassPro
        </div>
    </div>

    <ul class="sidebar-list">
        <li class="sidebar-list-item">
            <a href="{{ route('admin.dashboard') }}">
                <span class="material-icons-outlined">dashboard</span> Dashboard
            </a>
        </li>
        <li class="sidebar-list-item">
            <a href="{{ route('admin.users.index') }}">
                <span class="material-icons-outlined">group</span> Users
            </a>
        </li>
        <li class="sidebar-list-item">
            <a href="{{ route('admin.entertainment-venues.index') }}">
                <span class="material-icons-outlined">account_balance</span> Entertainment Venues
            </a>
        </li>
        <li class="sidebar-list-item">
            <a href="{{ route('admin.events.index') }}">
                <span class="material-icons-outlined">theater_comedy</span> Events
            </a>
        </li>
        <li class="sidebar-list-item">
            <a href="{{ route('admin.sessions.index') }}">
                <span class="material-icons-outlined">local_activity</span> Sessions
            </a>
        </li>
    </ul>

    <div class="sidebar-bottom">
        <div class="current-user">
            Logged in as: {{ Auth::user()->name }}
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</aside>
