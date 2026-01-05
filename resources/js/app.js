import './bootstrap';
import Lenis from 'lenis';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// 1. Import AOS JS (BARU)
import AOS from 'aos';

window.Swal = Swal;

// 2. Init AOS
AOS.init({
    once: true,
    duration: 600,
    offset: 50,
});

// 3. Konfigurasi Lenis
const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), 
    autoRaf: true, 
});

console.log('All libraries loaded via NPM (No CDN)');