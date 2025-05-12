import '../css/app.css';
import './bootstrap';
import '../scss/variables.scss';
import 'bootstrap/dist/js/bootstrap.bundle';

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.querySelector('.toggle-sidebar');

    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('active');
        sidebar.classList.add('fade');
        setTimeout(() => sidebar.classList.remove('fade'), 300);
    });
});