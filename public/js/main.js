/**
 * Main JavaScript file for Lumière Beauty
 */
console.log('Lumière Beauty loaded.');

// You can add global UI interactions here, e.g. mobile menu toggle, flash message auto-hide, etc.
document.addEventListener('DOMContentLoaded', () => {
   // Auto-hide flash messages after 5 seconds
   const flashMessages = document.querySelectorAll('.alert');
   if(flashMessages.length > 0) {
       setTimeout(() => {
           flashMessages.forEach(msg => {
               msg.style.transition = "opacity 0.5s ease";
               msg.style.opacity = "0";
               setTimeout(() => msg.remove(), 500);
           });
       }, 5000);
   }
});
