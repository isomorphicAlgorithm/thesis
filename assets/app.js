/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import './styles/app.css';
//import 'alpinejs';
/*import Alpine from 'alpinejs'
import { themeSwitcher } from './js/theme'

window.themeSwitcher = themeSwitcher;
window.Alpine = Alpine

Alpine.data('themeSwitcher', themeSwitcher)

Alpine.start()*/
//import Alpine from 'alpinejs'
//import themeSwitcher from './js/theme'

//window.Alpine = Alpine
//window.themeSwitcher = themeSwitcher // 👈 This makes it usable in x-data="themeSwitcher()"

//Alpine.start()

import './styles/app.css';

// Alpine.js core
import Alpine from 'alpinejs';
import themeSwitcher from './js/theme';
import loginForm from './js/login_form';
import { inlineEditor } from './components/inline-editor.js';
import '@fortawesome/fontawesome-free/css/all.min.css';

window.inlineEditor = inlineEditor;
window.Alpine = Alpine;
Alpine.data('themeSwitcher', themeSwitcher);
Alpine.data('loginForm', loginForm);

// Important: start Alpine
Alpine.start();