jQuery.extend(jQuery.validator.messages, {
	required: "This field is required.",
	remote: "Please fix this field.",
	email: "Please enter a valid email address.",
	url: "Please enter a valid URL.",
	date: "Please enter a valid date.",
	dateISO: "Please enter a valid date (ISO).",
	number: "Please enter a valid number.",
	digits: "Please enter only digits.",
	creditcard: "Please enter a valid credit card number.",
	equalTo: "Please enter the same value again.",
	maxlength: a.validator.format("Please enter no more than {0} characters."),
	minlength: a.validator.format("Please enter at least {0} characters."),
	rangelength: a.validator.format("Please enter a value between {0} and {1} characters long."),
	range: a.validator.format("Please enter a value between {0} and {1}."),
	max: a.validator.format("Please enter a value less than or equal to {0}."),
	min: a.validator.format("Please enter a value greater than or equal to {0}.")
});