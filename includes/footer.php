    </div> <!-- End container -->
    <footer class="footer mt-auto py-5 border-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span class="navbar-brand mb-2 d-block">Quotify</span>
                    <p class="text-muted small mb-0">© 2026 Quotify Portal. All rights reserved. Designed for excellence.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3">
                        <a href="#" class="text-muted text-decoration-none"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-muted text-decoration-none"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-muted text-decoration-none"><i class="bi bi-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const bodyElement = document.body;
        const icon = themeToggle.querySelector('i');

        // Check for saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        updateIcon(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        });

        function updateIcon(theme) {
            if (theme === 'dark') {
                icon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            } else {
                icon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
            }
        }
    </script>
</body>
</html>