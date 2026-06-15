/**
 * ADNetwork Auth JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Password confirmation validation
        $('#reg_password_confirm').on('input', function() {
            var password = $('#reg_password').val();
            var confirm = $(this).val();
            
            if (password !== confirm) {
                $(this).addClass('adnetwork-error-field');
            } else {
                $(this).removeClass('adnetwork-error-field');
            }
        });
        
        // Show/hide password
        $('.adnetwork-toggle-password').on('click', function(e) {
            e.preventDefault();
            var input = $(this).siblings('input');
            var type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
        });
    });
    
})(jQuery);
