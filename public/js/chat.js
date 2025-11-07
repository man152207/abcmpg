document.addEventListener('DOMContentLoaded', () => {
    const chatIconButton = document.querySelector('#chatIconButton');
    const chatPopup = document.querySelector('#chatPopup');
    const chatBody = document.querySelector('#chatBody');

    // Toggle chat popup
    chatIconButton.addEventListener('click', (e) => {
        e.stopPropagation();
        chatPopup.style.display = chatPopup.style.display === 'block' ? 'none' : 'block';
    });

    // Close on click outside
    document.addEventListener('click', (e) => {
        if (!chatPopup.contains(e.target) && !chatIconButton.contains(e.target)) {
            chatPopup.style.display = 'none';
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            chatPopup.style.display = 'none';
        }
    });

    // Auto-scroll to bottom
    if (chatBody) {
        chatBody.scrollTop = chatBody.scrollHeight;
    }
});