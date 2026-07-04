<nav class="navbar-custom">
    <div class="container">
        <div class="flex justify-between items-center">

            <!-- ===== LOGO ===== -->
            <a href="{{ route('welcome') }}" class="logo">
                🎨 Art<span>Market</span>
            </a>

            <!-- ===== NAVIGATION CENTRALE ===== -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('welcome') }}" class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="{{ route('client.catalogue') }}" class="nav-link {{ request()->routeIs('client.catalogue') ? 'active' : '' }}">
                    <i class="fas fa-compass"></i> Explorer
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-simple"></i> Tableau de bord
                    </a>
                @endauth
            </div>

            <!-- ===== ACTIONS DROITE ===== -->
            <div class="flex items-center gap-4">

                <!-- Recherche (mobile) -->
                <button class="md:hidden text-gray-600 text-lg" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>

                @auth
                    <!-- Panier -->
                    <a href="{{ route('client.panier.index') }}" class="relative text-gray-600 hover:text-primary transition text-lg">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="absolute -top-2 -right-2 bg-danger text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            3
                        </span>
                    </a>

                    <!-- Notifications -->
                    <button class="relative text-gray-600 hover:text-primary transition text-lg">
                        <i class="fas fa-bell"></i>
                        <span class="absolute -top-2 -right-2 bg-danger text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                            2
                        </span>
                    </button>

                    <!-- ===== PROFIL AVATAR (CORRIGÉ) ===== -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-gray-700 hover:text-primary transition">
                            <img src="{{ auth()->user()->photo_profil_url ?? asset('images/default-avatar.png') }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-primary">
                            <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <!-- Dropdown -->
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg py-2 hidden group-hover:block z-50 border border-gray-100">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-purple-50 transition text-gray-700">
                                <i class="fas fa-user mr-2 text-primary"></i> Mon profil
                            </a>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-purple-50 transition text-gray-700">
                                <i class="fas fa-chart-simple mr-2 text-primary"></i> Tableau de bord
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-red-50 transition text-red-500">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Boutons Connexion / Inscription -->
                    <a href="{{ route('login') }}" class="btn-secondary text-sm py-2 px-4">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-4">
                        <i class="fas fa-user-plus"></i> Inscription
                    </a>
                @endauth

                <!-- Menu mobile toggle -->
                <button class="md:hidden text-gray-600 text-2xl" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

        </div>

        <!-- ===== RECHERCHE MOBILE ===== -->
        <div class="hidden md:hidden mt-3" id="mobileSearch">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text"
                       placeholder="Rechercher une œuvre..."
                       class="w-full px-4 py-3 pl-12 rounded-xl border border-gray-200 focus:border-primary focus:outline-none transition">
            </div>
        </div>
    </div>
</nav>

<!-- ===== MENU MOBILE ===== -->
<div class="hidden md:hidden bg-white border-t border-gray-100 py-4 px-4" id="mobileMenu">
    <div class="flex flex-col gap-2">
        <a href="{{ route('welcome') }}" class="block px-4 py-3 rounded-xl hover:bg-purple-50 transition text-gray-700">
            <i class="fas fa-home mr-3 text-primary"></i> Accueil
        </a>
        <a href="{{ route('client.catalogue') }}" class="block px-4 py-3 rounded-xl hover:bg-purple-50 transition text-gray-700">
            <i class="fas fa-compass mr-3 text-primary"></i> Explorer
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-purple-50 transition text-gray-700">
                <i class="fas fa-chart-simple mr-3 text-primary"></i> Tableau de bord
            </a>
            <a href="{{ route('client.panier.index') }}" class="block px-4 py-3 rounded-xl hover:bg-purple-50 transition text-gray-700">
                <i class="fas fa-shopping-bag mr-3 text-primary"></i> Panier
            </a>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 rounded-xl hover:bg-purple-50 transition text-gray-700">
                <i class="fas fa-user mr-3 text-primary"></i> Mon profil
            </a>
            <hr class="my-2 border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-3 rounded-xl hover:bg-red-50 transition text-red-500">
                    <i class="fas fa-sign-out-alt mr-3"></i> Déconnexion
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl hover:bg-purple-50 transition text-gray-700">
                <i class="fas fa-sign-in-alt mr-3 text-primary"></i> Connexion
            </a>
            <a href="{{ route('register') }}" class="block px-4 py-3 rounded-xl hover:bg-purple-50 transition text-gray-700">
                <i class="fas fa-user-plus mr-3 text-primary"></i> Inscription
            </a>
        @endauth
    </div>
</div>

<style>
    .nav-link {
        color: #4a5568;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: color 0.3s ease;
        padding: 6px 0;
        border-bottom: 2px solid transparent;
    }

    .nav-link:hover {
        color: var(--primary);
    }

    .nav-link.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }

    .flex { display: flex; }
    .justify-between { justify-content: space-between; }
    .items-center { align-items: center; }
    .gap-4 { gap: 16px; }
    .gap-8 { gap: 32px; }
    .hidden { display: none; }

    @media (min-width: 768px) {
        .md\:flex { display: flex; }
        .md\:hidden { display: none; }
        .md\:block { display: block; }
    }

    @media (max-width: 767px) {
        .sm\:block { display: none; }
    }

    .relative { position: relative; }
    .absolute { position: absolute; }
    .top-0 { top: 0; }
    .right-0 { right: 0; }
    .-top-2 { top: -8px; }
    .-right-2 { right: -8px; }
    .z-50 { z-index: 50; }

    .w-5 { width: 20px; }
    .h-5 { height: 20px; }
    .w-10 { width: 40px; }
    .h-10 { height: 40px; }
    .w-56 { width: 224px; }

    .rounded-full { border-radius: 50%; }
    .rounded-xl { border-radius: 12px; }

    .bg-danger { background: #E74C3C; }
    .bg-white { background: white; }
    .bg-purple-50 { background: #f5f0ff; }
    .bg-red-50 { background: #fef2f2; }

    .text-xs { font-size: 12px; }
    .text-sm { font-size: 14px; }
    .text-lg { font-size: 18px; }
    .text-2xl { font-size: 24px; }
    .text-gray-400 { color: #a0aec0; }
    .text-gray-600 { color: #4a5568; }
    .text-gray-700 { color: #2d3748; }
    .text-red-500 { color: #E74C3C; }
    .text-white { color: white; }

    .border { border: 1px solid; }
    .border-t { border-top-width: 1px; }
    .border-b { border-bottom-width: 1px; }
    .border-gray-100 { border-color: #f7fafc; }
    .border-gray-200 { border-color: #e2e8f0; }
    .border-2 { border-width: 2px; }
    .border-primary { border-color: var(--primary); }

    .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }

    .object-cover { object-fit: cover; }

    .mt-2 { margin-top: 8px; }
    .mt-3 { margin-top: 12px; }
    .mr-2 { margin-right: 8px; }
    .mr-3 { margin-right: 12px; }
    .mb-2 { margin-bottom: 8px; }
    .my-1 { margin-top: 4px; margin-bottom: 4px; }
    .my-2 { margin-top: 8px; margin-bottom: 8px; }

    .px-4 { padding-left: 16px; padding-right: 16px; }
    .py-2 { padding-top: 8px; padding-bottom: 8px; }
    .py-3 { padding-top: 12px; padding-bottom: 12px; }
    .py-4 { padding-top: 16px; padding-bottom: 16px; }

    .pl-12 { padding-left: 48px; }

    .hover\:bg-purple-50:hover { background: #f5f0ff; }
    .hover\:bg-red-50:hover { background: #fef2f2; }
    .hover\:text-primary:hover { color: var(--primary); }

    .transition { transition: all 0.3s ease; }

    .group:hover .group-hover\:block { display: block; }

    .block { display: block; }
    .w-full { width: 100%; }
    .text-left { text-align: left; }

    .outline-none { outline: none; }
    .focus\:border-primary:focus { border-color: var(--primary); }

    .btn-secondary {
        background: white;
        color: #2D3436;
        border: 2px solid #E8ECF1;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-secondary:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 60, 225, 0.35);
        color: white;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const menuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Mobile search toggle
        const searchToggle = document.getElementById('searchToggle');
        const mobileSearch = document.getElementById('mobileSearch');

        if (searchToggle && mobileSearch) {
            searchToggle.addEventListener('click', function() {
                mobileSearch.classList.toggle('hidden');
            });
        }

        // Close mobile menu on link click
        const mobileLinks = mobileMenu ? mobileMenu.querySelectorAll('a, button') : [];
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.add('hidden');
            });
        });
    });
</script>