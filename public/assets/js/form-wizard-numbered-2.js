/**
 *  Form Wizard
 */

'use strict';

$(function () {
  const select2 = $('.select2'),
    selectPicker = $('.selectpicker');

  // Bootstrap select
  if (selectPicker.length) {
    selectPicker.selectpicker();
  }

  // select2
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        placeholder: 'Select value',
        dropdownParent: $this.parent()
      });
    });
  }
});
(function () {
  // Numbered Wizard
  // --------------------------------------------------------------------
  const wizardNumbered = document.querySelector('.wizard-numbered');

if (wizardNumbered !== null) {
    const wizardNumberedBtnNextList = Array.from(wizardNumbered.querySelectorAll('.btn-next'));
    const wizardNumberedBtnPrevList = Array.from(wizardNumbered.querySelectorAll('.btn-prev'));
    const wizardNumberedBtnSubmit = wizardNumbered.querySelector('.btn-submit');

    const numberedStepper = new Stepper(wizardNumbered, {
        linear: false
    });

    wizardNumberedBtnNextList.forEach(wizardNumberedBtnNext => {
        wizardNumberedBtnNext.addEventListener('click', event => {
            numberedStepper.next();
        });
    });

    wizardNumberedBtnPrevList.forEach(wizardNumberedBtnPrev => {
        wizardNumberedBtnPrev.addEventListener('click', event => {
            numberedStepper.previous();
        });
    });

    if (wizardNumberedBtnSubmit) {
        wizardNumberedBtnSubmit.addEventListener('click', event => {
            alert('Submitted..!!');
        });
    }
}


  
})();
