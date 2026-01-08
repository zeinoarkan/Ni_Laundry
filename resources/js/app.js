import './bootstrap';
import Lenis from 'lenis';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import AOS from 'aos';

// 1. Import GSAP Core
import gsap from 'gsap';

// 2. Integrasi Alpine
window.Alpine = Alpine;
Alpine.plugin(collapse); 
Alpine.start();

// 3. Expose Library ke Window (Agar bisa diakses di Blade/Inline Script)
window.Swal = Swal;
window.gsap = gsap; // <--- PENTING: Ini kuncinya agar script di Blade bisa pakai GSAP

// 4. Inisialisasi AOS
AOS.init({
    once: true,
    duration: 600,
    offset: 50,
});

// 5. Inisialisasi Lenis (Smooth Scroll)
const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), 
    autoRaf: true, 
});

// 6. Hubungkan Lenis dengan GSAP (Opsional tapi Recommended untuk 'Sync')
// Ini membuat animasi scroll trigger GSAP sinkron dengan Lenis
// (Uncomment jika nanti Anda pakai ScrollTrigger)
/*
gsap.ticker.add((time) => {
  lenis.raf(time * 1000);
});
gsap.ticker.lagSmoothing(0);
*/

// Expose lenis ke window jika ingin mengontrol scroll dari luar (misal stop scroll saat loading)
window.lenis = lenis; 

console.log('All libraries loaded via NPM (High End Setup Ready)');