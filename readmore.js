// $('#buttonId).on('click', function(){
//   $('div_id_of_wordpress').addClass('hidden')
//   ..... same for all other divs
//   $('div_id_of_examples').removeClass('hidden')
// });
  <script>
    const panels = document.querySelectorAll('.panel');

    function toggleOpen() {
      console.log('Hello');
      this.classList.toggle('open');
    }

    function toggleActive(e) {
      console.log(e.propertyName);
      if (e.propertyName.includes('flex')) {
        this.classList.toggle('open-active');
      }
    }

    panels.forEach(panel => panel.addEventListener('click', toggleOpen));
    panels.forEach(panel => panel.addEventListener('transitionend', toggleActive));
  //</script>




 //    const panels = document.querySelectorAll('.panel');

 //    function toggleOpen() {
 //      console.log('Hello');
 //      this.classList.toggle('open');
 //    }

 //    function toggleActive(e) {
 //      console.log(e.propertyName);
 //      if (e.propertyName.includes('flex')) {
 //        this.classList.toggle('open-active');
 //      }
 //    }

 //    panels.forEach(panel => panel.addEventListener('click', toggleOpen));
 //    panels.forEach(panel => panel.addEventListener('transitionend', toggleActive));
 // // </script>
