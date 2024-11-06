document.querySelectorAll('.panel').forEach(panel => {
    panel.addEventListener('click', () => {
        alert(panel.querySelector('h2').innerText + "をクリックしました！");
    });
});
