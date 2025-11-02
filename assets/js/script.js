function confirmAction(message) {
    return confirm(message);
}
// Example: Add a small interaction effect
document.querySelectorAll('button').forEach(button => {
    button.addEventListener('click', () => {
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 150);
    });
});
