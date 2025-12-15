// 1. jQuery
import $ from 'jquery';
window.$ = window.jQuery = $;

// 2. Bootstrap (requires Popper.js, installed via dependency)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// 3. AmplitudeJS (Audio Player)
import Amplitude from 'amplitudejs';
window.Amplitude = Amplitude;

// 4. Moment.js (Time formatting)
import moment from 'moment';
window.moment = moment;

// 5. Perfect Scrollbar
import PerfectScrollbar from 'perfect-scrollbar';
window.PerfectScrollbar = PerfectScrollbar;
// Import Perfect Scrollbar CSS in app.css, not here

// 6. Chart.js
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;

// 7. Dropzone
import Dropzone from 'dropzone';
window.Dropzone = Dropzone;

// 8. Snackbar (Polonel/Snackbar)
// Note: 'node-snackbar' is the closest npm equivalent, but sometimes templates use custom forks.
// We map it to window.Snackbar to match the template.
import Snackbar from 'node-snackbar';
window.Snackbar = Snackbar;

// 9. Swiper
import Swiper from 'swiper/bundle';
// Swiper CSS should be imported in app.css
window.Swiper = Swiper;

console.log('Plugins loaded successfully.');
