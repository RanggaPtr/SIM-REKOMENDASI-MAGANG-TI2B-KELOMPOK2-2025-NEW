<header class="header">
    <div class="header-brand">
        <a href="{{ url('/') }}"><span style="color: #EEB521;">M</span>agang<span style="color: #EEB521;">.In</span></a>
    </div>
    <div class="header-user">
        <div class="user-info">
            <span>{{ Auth::user()->name }}</span>
            <a href="{{ url('/logout') }}">Logout</a>
        </div>
        <img src="{{ Auth::user()->profile_photo_url }}" alt="User Profile">
    </div>
    <i class="fas fa-bars toggle-sidebar"></i>
</header>