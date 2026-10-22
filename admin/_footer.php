        </main>

    </div>

</div>

<script>
    document
        .querySelectorAll('.delete-confirm')
        .forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!confirm('Hapus data ini?')) {
                    event.preventDefault();
                }
            });
        });
</script>

<script>
    document.body.classList.add('admin-page-ready');

    document.querySelectorAll('.sidebar a, .admin-top a, .admin-content a').forEach((link) => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');

            if (!href || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
                return;
            }

            if (link.target === '_blank') {
                return;
            }

            event.preventDefault();
            document.body.classList.add('admin-page-leaving');

            window.setTimeout(() => {
                window.location.href = href;
            }, 360);
        });
    });
</script>

</body>
</html>
