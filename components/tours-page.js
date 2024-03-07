var datepickerWithFilter = document.querySelector('.datepicker-with-filter');

function filterFunction(date) {
var isSaturday = date.getDay() === 6;
var isSunday = date.getDay() === 0;
var isBeforeToday = date &lt; new Date(); return isSaturday || isSunday || isBeforeToday; } new
  mdb.Datepicker(datepickerWithFilter, { filter: filterFunction });