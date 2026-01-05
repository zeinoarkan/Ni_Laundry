import './bootstrap';

// 1. Import Lenis (Sudah ada sebelumnya)
import Lenis from 'lenis';

// 2. Import SweetAlert2 (BARU)
import Swal from 'sweetalert2';

// 3. Jadikan SweetAlert global agar bisa dipanggil di Blade via script tag
window.Swal = Swal;

// 4. Konfigurasi Lenis
const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), 
    autoRaf: true, 
});

console.log('Lenis & SweetAlert2 loaded via NPM');