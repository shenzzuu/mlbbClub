document.addEventListener("DOMContentLoaded", () => {
    console.log("E-Sports page loaded");

    const tier = "<?= $tier ?>";
    if (tier === 'starter') {
        alert("Upgrade your plan to unlock live streams and exclusive content!");
    }
});