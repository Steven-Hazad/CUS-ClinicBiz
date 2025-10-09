import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
window.Alpine = Alpine;
window.Chart = Chart; // Make Chart.js globally available
Alpine.start();
