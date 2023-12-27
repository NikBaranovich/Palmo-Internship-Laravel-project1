<div class="sidebar">
    <h2>Меню</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link{{ Request::is('admin/dashboard') ? ' active' : '' }}" href="{{ url('admin/dashboard') }}">
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ Request::is('admin/users') ? ' active' : '' }}" href="{{ url('admin/users') }}">
                Users
            </a>
        </li>
    </ul>
</div>
