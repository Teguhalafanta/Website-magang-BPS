{{-- javascripct --}}
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Need: Apexcharts -->
<script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>

{{-- Script untuk toggle sidebar (buka/tutup) --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("sidebar");
        const openBtn = document.querySelector(".burger-btn");
        const closeBtn = document.querySelector(".sidebar-hide");

        // buka sidebar di mobile
        if (openBtn && sidebar) {
            openBtn.addEventListener("click", function(e) {
                e.preventDefault();
                sidebar.classList.add("active");
            });
        }

        // tutup sidebar di mobile
        if (closeBtn && sidebar) {
            closeBtn.addEventListener("click", function(e) {
                e.preventDefault();
                sidebar.classList.remove("active");
            });
        }

        // cek ukuran layar (reset otomatis sesuai breakpoint)
        function handleResize() {
            if (window.innerWidth >= 1200) {
                sidebar.classList.add("active");
            } else {
                sidebar.classList.remove("active");
            }
        }

        handleResize();
        window.addEventListener("resize", handleResize);

        // Tambahan: Toggle submenu kegiatan & pelajar
        const menuKegiatan = document.getElementById("menu-kegiatan");
        const submenuKegiatan = document.getElementById("submenu-kegiatan");

        const menuPelajar = document.getElementById("menu-pelajar");
        const submenuPelajar = document.getElementById("submenu-pelajar");

        if (menuKegiatan && submenuKegiatan) {
            menuKegiatan.addEventListener("click", function(e) {
                e.preventDefault();
                submenuKegiatan.classList.toggle("hidden");
            });
        }

        if (menuPelajar && submenuPelajar) {
            menuPelajar.addEventListener("click", function(e) {
                e.preventDefault();
                submenuPelajar.classList.toggle("hidden");
            });
        }
    });
</script>
