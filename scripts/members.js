document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".card");
  
    cards.forEach((card, index) => {
      card.addEventListener("click", (e) => {
        e.stopPropagation();
        const isExpanded = card.classList.contains("expanded");
  
        cards.forEach(c => c.classList.remove("expanded"));

        if (!isExpanded) {
          card.classList.add("expanded");
        }
      });
    });
  
    document.addEventListener("click", () => {
      cards.forEach(c => c.classList.remove("expanded"));
    });
  });  