document.querySelectorAll('[data-image]').forEach(button => {
    button.addEventListener('click', () => {
        const image = document.getElementById('large-image');
        image.src = button.dataset.image;
        image.alt = button.dataset.title;
        document.getElementById('image-caption').textContent = button.dataset.title;
        document.getElementById('image-dialog').showModal();
    });
});
document.querySelectorAll('[data-vote]').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('vote-title').textContent = button.dataset.title;
        document.getElementById('vote-artwork').value = button.dataset.vote;
        document.getElementById('vote-dialog').showModal();
    });
});
document.querySelectorAll('.close-dialog').forEach(button => button.addEventListener('click', () => button.closest('dialog').close()));
document.querySelectorAll('form[data-confirm]').forEach(form => form.addEventListener('submit', event => {
    if (!window.confirm(form.dataset.confirm)) event.preventDefault();
}));
