  function convertTimestamp(timestamp) {
  var d = new Date(timestamp), // Convert the passed timestamp to milliseconds
	yyyy = d.getFullYear(),
	mm = ("0" + (d.getMonth() + 1)).slice(-2),  // Months are zero based. Add leading 0.
	dd = ("0" + d.getDate()).slice(-2),     // Add leading 0.
	hh = d.getHours(),
	h = hh,
	min = ("0" + d.getMinutes()).slice(-2),   // Add leading 0.
	ampm = "AM",
	time;

  if (hh > 12) {
	h = hh - 12;
	ampm = "PM";
  } else if (hh === 12) {
	h = 12;
	ampm = "PM";
  } else if (hh == 0) {
	h = 12;
  }

  // ie: 2013-02-18, 8:35 AM
  //time = yyyy + "-" + mm + "-" + dd + ", " + h + ":" + min + " " + ampm;
  time = yyyy + "-" + mm + "-" + dd;

  return time;
}

//функция для highcharts;
function findme(point) {

$("#pointClickLog").html('Нажатая дата: '+point.x+'; '+point.y+"; "+ convertTimestamp(point.x));

link="finances/finshared?FinsharedSearch%5Bmoney%5D=&FinsharedSearch%5Bcurrency_id%5D=&FinsharedSearch%5Bmotion_id%5D=&FinsharedSearch%5Bcategory_id%5D=&FinsharedSearch%5Bwallet_id%5D=&FinsharedSearch%5Bdate%5D="+convertTimestamp(point.x)+"&FinsharedSearch%5Bcomment%5D=&FinsharedSearch%5Bmy_old_wallet_balance%5D=&FinsharedSearch%5Bmy_old_summa_balance%5D=&FinsharedSearch%5Bmy_new_wallet_balance%5D=&FinsharedSearch%5Bmy_new_summa_balance%5D=&FinsharedSearch%5Bour_old_wgsumma_balance%5D=&FinsharedSearch%5Bour_old_summa_balance%5D=&FinsharedSearch%5Bour_new_wgsumma_balance%5D=&FinsharedSearch%5Bour_new_summa_balance%5D=&FinsharedSearch%5Btimestamp%5D=";
window.open(link, "window name");

}
