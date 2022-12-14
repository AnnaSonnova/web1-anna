window.addEventListener("load", ()=>{
   
    const btnList = document.querySelector('[data-js-list-btn]');
    console.log(btnList);
    const btnGrid = document.querySelector('[data-js-grid-btn]');

    /*-- les elements de la grille a transformer--*/

    const Cards = document.querySelectorAll("[data-js-list]");
    console.log(Cards);
    btnList.addEventListener('click', listView);

    btnGrid.addEventListener('click', gridView);

    function listView() {
      if (btnGrid.classList.contains('active')) {
        btnGrid.classList.remove('active');
        btnList.classList.add('active');
      }
      for (let i = 0; i < Cards.length; i++) {
         Cards[i].classList.add('list');
        
      }
    }

    function gridView() {
      if (btnList.classList.contains('active')) {
        btnList.classList.remove('active');
        btnGrid.classList.add('active');
      }
      for (let i = 0; i < Cards.length; i++) {
        if (Cards[i].classList.contains('list')) {
          Cards[i].classList.remove('list');
          
        }
      }
    }

   
});