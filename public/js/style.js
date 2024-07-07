// init Isotope
var $grid = $('.collection-list').isotope({
  // options
});

// filter items on button click
$('.filter-button-group').on('click', 'button', function() {
  var filterValue = $(this).attr('data-filter');
  resetFilterBtns();
  $(this).addClass('active-filter-btn');
  $grid.isotope({ filter: filterValue });
});

var filterBtns = $('.filter-button-group').find('button');
function resetFilterBtns() {
  filterBtns.each(function() {
    $(this).removeClass('active-filter-btn');
  });
}

// product 1,2 page
const allHoverImages = document.querySelectorAll('.hover-container div img');
const imgContainer = document.querySelector('.img-container');

window.addEventListener('DOMContentLoaded', () => {
  if (allHoverImages.length > 0 && imgContainer) {
    if (allHoverImages[0].parentElement) {
      allHoverImages[0].parentElement.classList.add('active');
    }
  } else {
    console.error('Hover images or image container not found');
  }
});

allHoverImages.forEach((image) => {
  image.addEventListener('mouseover', () => {
    if (imgContainer && imgContainer.querySelector('img')) {
      imgContainer.querySelector('img').src = image.src;
      resetActiveImg();
      if (image.parentElement) {
        image.parentElement.classList.add('active');
      }
    }
  });
});

function resetActiveImg() {
  allHoverImages.forEach((img) => {
    if (img.parentElement) {
      img.parentElement.classList.remove('active');
    }
  });
}
