function selectRow(row, color)
{
	if (typeof selectedStyle === 'undefined' || selectedStyle === null) {
		var sheet = document.createElement('style')
		sheet.innerHTML = '.selected-row {background-color: ' + color + ' !important;}';
		document.body.appendChild(sheet);
	}

	$(row).toggleClass("selected-row");
}


