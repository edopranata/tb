require('./bootstrap');

import Fuse from 'fuse.js'
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.fuse = Fuse;
