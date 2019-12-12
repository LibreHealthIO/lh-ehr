// This file is used to validate input before Report forms are submitted.
// Author: Maggie Negm (maggiehn94@gmail.com)


// Validates the entered date range
function validateFromAndToDates() {
    var d = document.forms["theform"];

    var fromDate = d.form_from_date.value;
    var toDate = d.form_to_date.value;
    if (fromDate.length > 0 && toDate.length > 0) {
      if (fromDate > toDate) {
        iziToast.warning({
          title: 'Caution:',
          message: 'You must enter a To date that is after the From date.',
        });
        return false;
      }
    }
    
    return true;
}

// Validates the entered age range
function validateAgeRange() {
    var d = document.forms["theform"];

    var minAge = d.age_from.value;
    var maxAge = d.age_to.value;
    if (minAge.length > 0 && maxAge.length > 0) {
        if (minAge > maxAge) {
            iziToast.warning({
                title: 'Caution:',
                message: 'You must enter a To age that is greater than the From age.',
              });
              return false;
        }
    }
    return true;
}