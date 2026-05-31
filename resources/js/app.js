// Import the Alpine image cropper plugin
import imageCropperPlugin from './alpine-cropper';

// If you are defining Alpine manually in this project:
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Register the plugin
Alpine.plugin(imageCropperPlugin);

Alpine.start();
