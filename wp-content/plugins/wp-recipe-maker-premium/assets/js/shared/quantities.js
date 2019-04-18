export function formatQuantity(quantity, decimals = 2) {
	decimals = parseInt(decimals);
	let formattedQuantity = parseFloat(parseFloat(quantity).toFixed(decimals));

	if ( 0.0 == formattedQuantity ) {
		formattedQuantity += Math.pow( 10, -1 * decimals );
	}

	return formattedQuantity;
};

export function parseQuantity(sQuantity) {
	// Use . for decimals
	sQuantity = sQuantity.replace(',', '.');

	// Replace fraction characters with equivalent
	var fractionsRegex = /(\u00BC|\u00BD|\u00BE|\u2150|\u2151|\u2152|\u2153|\u2154|\u2155|\u2156|\u2157|\u2158|\u2159|\u215A|\u215B|\u215C|\u215D|\u215E)/;
	var fractionsMap = {
		'\u00BC': ' 1/4', '\u00BD': ' 1/2', '\u00BE': ' 3/4', '\u2150': ' 1/7',
		'\u2151': ' 1/9', '\u2152': ' 1/10', '\u2153': ' 1/3', '\u2154': ' 2/3',
		'\u2155': ' 1/5', '\u2156': ' 2/5', '\u2157': ' 3/5', '\u2158': ' 4/5',
		'\u2159': ' 1/6', '\u215A': ' 5/6', '\u215B': ' 1/8', '\u215C': ' 3/8',
		'\u215D': ' 5/8', '\u215E': ' 7/8'
	};
	sQuantity = (sQuantity + '').replace(fractionsRegex, function(m, vf) {
		return fractionsMap[vf];
	});

	// Split by spaces
	sQuantity = sQuantity.trim();
	var parts = sQuantity.split(' ');

	var quantity = false;

	if(sQuantity !== '') {
		quantity = 0;

		// Loop over parts and add values
		for(var i = 0; i < parts.length; i++) {
			if(parts[i].trim() !== '') {
				var division_parts = parts[i].split('/', 2);
				var part_quantity = parseFloat(division_parts[0]);

				if(division_parts[1] !== undefined) {
					var divisor = parseFloat(division_parts[1]);

					if(divisor !== 0) {
						part_quantity /= divisor;
					}
				}

				quantity += part_quantity;
			}			
		}
	}

	return quantity;
};