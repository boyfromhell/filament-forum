//import '../css/index.css';

import 'flowbite';
//import './pace.min';
window.addEventListener('load', function () {
    const element = document.getElementById('loading-page');
    element.style.opacity = 0;
    setTimeout(() => element.remove(), 500)
})

window.openModal = function (id) {
    const targetEl = document.getElementById(id);
    const options = {
        placement: 'center',
        backdrop: 'dynamic',
        backdropClasses: 'bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40'
    };
    const modal = new Modal(targetEl, options);
    modal.show();

    document.getElementById('hide-' + id).addEventListener('click', function () {
        modal.hide();
    });
}
