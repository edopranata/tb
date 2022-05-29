require('./bootstrap');

import Fuse from 'fuse.js'
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'
import 'jquery-ui/ui/widgets/datepicker.js';

window.Alpine = Alpine;
Alpine.plugin(focus);

Alpine.start();

window.fuse = Fuse;
