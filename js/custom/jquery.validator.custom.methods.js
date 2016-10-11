/*
 * Used for the regristration from to check passwords.
 * Code by Anton Roslund
 */

jQuery.validator.addMethod("containsLetter", function(value, element) {
    return this.optional(element) || /.*[A-Za-z].*/.test(value);
}, "Your password must contain a letter");

jQuery.validator.addMethod("containsNumber", function(value, element) {
    return this.optional(element) || /.*[0-9].*/.test(value);
}, "Your password must contain a number");

jQuery.validator.addMethod("containsSpecial", function(value, element) {
    return this.optional(element) || /.*[^A-Za-z0-9].*/.test(value);
}, "Your password must contain a special character");