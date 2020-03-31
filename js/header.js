var my_date_format = function(date_string){
	const months = ["January", "February", "March","April", "May", "June", "July", "August", "September", "October", "November", "December"];
	let current_datetime = new Date(date_string)
	let formatted_date = current_datetime.getDate() + ' ' + months[current_datetime.getMonth()] + ' ' + current_datetime.getFullYear()
	return formatted_date;
}