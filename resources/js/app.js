import './bootstrap';

// 1. Import Alpine.js
import Alpine from 'alpinejs';

// 2. Import AOS & CSS-nya
import AOS from 'aos';
import 'aos/dist/aos.css';

// 3. Import Icon (Sesuai yang kamu pakai: Bold & Fill)
// Kamu menggunakan class 'ph-bold' (menu, logout) dan 'ph-fill' (sosmed, success)
import '@phosphor-icons/web/bold';
import '@phosphor-icons/web/fill';
import '@phosphor-icons/web/regular'; // Jaga-jaga jika ada icon regular

// 4. Start Alpine
window.Alpine = Alpine;
Alpine.start();

// 5. Start AOS (Parameter SAMA PERSIS dengan yang ada di blade kamu sebelumnya)
document.addEventListener('DOMContentLoaded', () => {
    AOS.init({ 
        once: true, 
        mirror: false, 
        duration: 600, 
        easing: 'ease-out-cubic', 
        offset: 50, 
        throttleDelay: 99 
    });
});