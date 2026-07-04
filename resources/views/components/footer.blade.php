<footer class="bg-white border-t border-gray-100 mt-8">
    <div class="container py-8">

        <!-- ===== GRID FOOTER ===== -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            <!-- Logo & Description -->
            <div>
                <a href="{{ route('welcome') }}" class="logo text-2xl font-bold text-primary">
                    🎨 Art<span style="color: var(--secondary);">Market</span>
                </a>
                <p class="text-gray-500 text-sm mt-2 max-w-xs">
                    Le marché digital des œuvres artistiques. Connectez artisans et amateurs d'art.
                </p>
                <div class="flex gap-3 mt-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-primary hover:bg-purple-100 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-primary hover:bg-purple-100 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-primary hover:bg-purple-100 transition">
                        <i class="fab fa-pinterest-p"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-primary hover:bg-purple-100 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-primary hover:bg-purple-100 transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Liens rapides -->
            <div>
                <h4 class="font-semibold text-gray-800 mb-3">Liens rapides</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-500 hover:text-primary transition">À propos</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-primary transition">Comment ça marche</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-primary transition">Devenir artisan</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-primary transition">Nos engagements</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="font-semibold text-gray-800 mb-3">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-500 hover:text-primary transition">Centre d'aide</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-primary transition">Contact</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-primary transition">FAQ</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-primary transition">Livraison</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="font-semibold text-gray-800 mb-3">Newsletter</h4>
                <p class="text-sm text-gray-500 mb-3">
                    Recevez les dernières nouveautés et offres exclusives.
                </p>
                <form action="#" class="flex">
                    <input type="email"
                           placeholder="Votre email"
                           class="flex-1 px-4 py-3 rounded-l-xl border border-gray-200 focus:border-primary focus:outline-none transition text-sm">
                    <button type="submit"
                            class="px-4 py-3 bg-primary text-white rounded-r-xl hover:bg-primary-dark transition">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>

        </div>

        <!-- ===== BAS DE PAGE ===== -->
        <hr class="my-6 border-gray-100">

        <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500 gap-4">
            <p>
                &copy; {{ date('Y') }} <strong class="text-primary">ArtMarket</strong>.
                Tous droits réservés.
            </p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-primary transition">Confidentialité</a>
                <a href="#" class="hover:text-primary transition">Conditions générales</a>
                <a href="#" class="hover:text-primary transition">Cookies</a>
            </div>
        </div>

    </div>
</footer>

<style>
    .grid {
        display: grid;
    }

    .grid-cols-1 {
        grid-template-columns: 1fr;
    }

    .gap-8 {
        gap: 32px;
    }

    .gap-3 {
        gap: 12px;
    }

    .gap-6 {
        gap: 24px;
    }

    .space-y-2 > * + * {
        margin-top: 8px;
    }

    .max-w-xs {
        max-width: 320px;
    }

    .mt-2 { margin-top: 8px; }
    .mt-4 { margin-top: 16px; }
    .mt-8 { margin-top: 32px; }
    .mb-3 { margin-bottom: 12px; }
    .mb-6 { margin-bottom: 24px; }
    .my-6 { margin-top: 24px; margin-bottom: 24px; }

    .w-10 { width: 40px; }
    .h-10 { height: 40px; }

    .rounded-full { border-radius: 50%; }
    .rounded-xl { border-radius: 12px; }
    .rounded-l-xl { border-radius: 12px 0 0 12px; }
    .rounded-r-xl { border-radius: 0 12px 12px 0; }

    .bg-purple-50 { background: #f5f0ff; }
    .bg-purple-100 { background: #ede3fc; }
    .bg-primary { background: var(--primary); }
    .bg-primary-dark { background: var(--primary-dark); }

    .text-primary { color: var(--primary); }
    .text-gray-500 { color: #718096; }
    .text-gray-800 { color: #2d3748; }
    .text-white { color: white; }

    .font-bold { font-weight: 700; }
    .font-semibold { font-weight: 600; }

    .text-2xl { font-size: 24px; }
    .text-sm { font-size: 14px; }
    .text-xs { font-size: 12px; }

    .border-t { border-top-width: 1px; }
    .border-gray-100 { border-color: #f7fafc; }
    .border-gray-200 { border-color: #e2e8f0; }

    .shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }

    .hover\:bg-purple-100:hover { background: #ede3fc; }
    .hover\:bg-primary-dark:hover { background: var(--primary-dark); }
    .hover\:text-primary:hover { color: var(--primary); }

    .transition { transition: all 0.3s ease; }

    .flex { display: flex; }
    .flex-1 { flex: 1; }

    .items-center { align-items: center; }
    .justify-center { justify-content: center; }
    .justify-between { justify-content: space-between; }

    .gap-4 { gap: 16px; }

    .py-3 { padding-top: 12px; padding-bottom: 12px; }
    .py-4 { padding-top: 16px; padding-bottom: 16px; }
    .px-4 { padding-left: 16px; padding-right: 16px; }

    .outline-none { outline: none; }

    .focus\:border-primary:focus { border-color: var(--primary); }

    .bg-white { background: white; }
    .container { max-width: 1400px; margin: 0 auto; padding: 0 16px; }
    .py-8 { padding-top: 32px; padding-bottom: 32px; }

    @media (min-width: 768px) {
        .md\\:grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
        .md\\:flex-row { flex-direction: row; }
    }

    .flex-col { flex-direction: column; }

    @media (max-width: 767px) {
        .md\\:flex-row { flex-direction: column; }
    }
</style>