require('./bootstrap');

import Fuse from 'fuse.js'
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus'

window.Alpine = Alpine;
Alpine.plugin(focus);

Alpine.start();

window.fuse = Fuse;
