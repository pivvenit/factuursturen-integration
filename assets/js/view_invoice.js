jQuery(document).ready(function ($) {
    // View the invoice in a new window on press of the '#view-factuursturen' hyperlink
    $('#fsi-create-invoice').click(function (e) {
        //submit the form via ajax
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                action: 'fsi_create_invoice',
                order_id: $(this).data('order-id')
            },
            success: function (response) {
                // reload the window
                window.location.reload();
            },
            error: function (response) {
                alert("Er is een fout opgetreden bij het aanmaken van de factuur. Probeer het later nog eens.");
                console.error(response);
            }
        });
    });
});