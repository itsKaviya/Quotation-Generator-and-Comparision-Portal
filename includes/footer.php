    </div> <!-- End container -->
    <footer class="footer mt-auto py-3 bg-light border-top mt-5">
        <div class="container text-center">
            <span class="text-muted">© 2026 Quotation Generator & Comparison Portal. Built with ❤️</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS and Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const htmlElement = document.documentElement;

        // Check for saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        htmlElement.setAttribute('data-bs-theme', savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });

        // Simple Navbar shadow on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-sm');
                nav.style.backgroundColor = 'rgba(var(--bs-bg-opacity, 1), 0.9)';
            } else {
                nav.classList.remove('shadow-sm');
            }
        });
    </script>
</body>
</html>