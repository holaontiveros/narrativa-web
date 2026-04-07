import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('mobileMenu', {
  open: false,
  toggle() {
    this.open = !this.open;
  },
});

Alpine.start();
