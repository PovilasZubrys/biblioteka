import './styles/app.scss';
import 'bootstrap';

// start the Stimulus application
// import './bootstrap';

const $ = require('jquery');
const summernote = require('summernote');

$('#summernote').summernote();

console.log('summernote');