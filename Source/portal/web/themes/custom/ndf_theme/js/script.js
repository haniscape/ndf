// Main script file
(function ($, drupalSettings) {

  // Search filter on app add form.
  if ($('form').hasClass('developer-app-add-for-developer-form')) {
    let inputId 	 = '#apis-filter-search';
    let itemsData  = 'filter-value';
    let displaySet = false;
    let displayArr = [];
    function getDisplayType(element) {
      let elementStyle = element.currentStyle || window.getComputedStyle(element, "");
      return elementStyle.display;
    }

    $(document).on('keyup',inputId,function(){
      let searchVal = this.value.toLowerCase();
      let filterItems = document.querySelectorAll('[' + itemsData + ']');
      for(let i = 0; i < filterItems.length; i++) {
        if (!displaySet) {
          displayArr.push(getDisplayType(filterItems[i]));
        }
        filterItems[i].style.display = 'none';
        console.log(filterItems[i]);
        if(filterItems[i].getAttribute('filter-value').indexOf(searchVal) >= 0) {
          filterItems[i].style.display = displayArr[i];
        }
      }
      displaySet = true;
    })
  }

  // Search filter on app edit form.
  if ($('form').hasClass('developer-app-edit-for-developer-form')) {
    // Get filter input id.
    // Loop on Credentials items.
    $('.cred-accordion').each(function (i) {
      let credElement = $(this)
      let inputId 	 = $(this).find('.cred-filter-search').attr('id');
      let itemsData  = $(this).find('div[filter-value]');
      let displaySet = false;
      let displayArr = [];

      function getDisplayType(element) {
        let elementStyle = element.currentStyle || window.getComputedStyle(element, "");
        return elementStyle.display;
      }

      document.getElementById(inputId).onkeyup = function() {
        let searchVal = this.value.toLowerCase();
        let filterItems = itemsData;
        for(let i = 0; i < filterItems.length; i++) {
          if (!displaySet) {
            displayArr.push(getDisplayType(filterItems[i]));
          }
          filterItems[i].style.display = 'none';
          if(filterItems[i].getAttribute('filter-value').indexOf(searchVal) >= 0) {
            filterItems[i].style.display = displayArr[i];
          }
        }
        displaySet = true;
      }
    })
  }

  // Make submit button disabled if the email input is empty in forget password form
  if ($('form').hasClass('user-pass')) {
    var emailInput = $("input[type='email']");
    var emailVal = emailInput.val();
    var formSubmitBtn = $('.form-submit');

    // onchange mail
    $(emailInput).each(function () {
      $(this).val().length == 0 ? $(formSubmitBtn).attr('disabled', true) : $(formSubmitBtn).attr('disabled', false);
      $(this).on('input', function () {
        $(this).val().length == 0 ? $(formSubmitBtn).attr('disabled', true) : $(formSubmitBtn).attr('disabled', false);
      });
    });
  }

})(jQuery, drupalSettings);
