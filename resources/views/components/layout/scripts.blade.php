<script>
  // Menyiapkan tombol pengubah tema halaman.
  const themeToggle = document.getElementById('themeToggle');
  const html = document.documentElement;

  // Menerapkan tema pada elemen halaman.
  function applyTheme(isDark) {
    html.classList.toggle('dark', isDark);
    html.setAttribute('data-theme', isDark ? 'dark' : 'light');
    if (themeToggle) themeToggle.checked = isDark;
  }

  applyTheme(localStorage.getItem('theme') === 'dark');

  themeToggle?.addEventListener('change', () => {
    const isDark = themeToggle.checked;
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    applyTheme(isDark);
  });

  // Menampilkan animasi masuk ketika elemen terlihat di layar.
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
</script>
