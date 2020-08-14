var my_date_format = function(date_string){
	const months = ["January", "February", "March","April", "May", "June", "July", "August", "September", "October", "November", "December"];
	let current_datetime = new Date(date_string)
	let formatted_date = current_datetime.getDate() + ' ' + months[current_datetime.getMonth()] + ' ' + current_datetime.getFullYear()
	return formatted_date;
}

$(window).on('load', function(){
	$('.loader_wrapper').fadeOut(400);
});

window.addEventListener('load', function () {
	var elements = document.getElementsByClassName("slide_alert_close_button");
	for (var i = 0; i < elements.length; i++) {
		elements[i].addEventListener('click', closeSlideAlert, false);
	}
});

function closeSlideAlert(){
	$(this).siblings('.alert_box_slide').hide("slide", { direction: "right" }, 250, function(){
		$(this).parent().fadeOut();
	});
}
// document.getElementsByClassName("slide_alert_close_button").addEventListener("click", closeSlideAlert());