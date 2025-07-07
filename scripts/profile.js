function openModal() {
    document.getElementById('editModal').style.display = "block";
}

function closeModal() {
    document.getElementById('editModal').style.display = "none";
}

window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeModal();
    }
}

window.addEventListener("DOMContentLoaded", () => {
    const success = document.querySelector(".success");
    const error = document.querySelector(".error");

    if (success) {
        setTimeout(() => success.style.display = "none", 3000);
    }

    if (error) {
        setTimeout(() => error.style.display = "none", 3000);
    }
});