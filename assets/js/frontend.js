jQuery(document).ready(function ($) {
  $("#bcf-form").on("submit", function (e) {
    e.preventDefault();
    var form = $(this),
      submitButton = form.find('button[type="submit"]'),
      message = $("#bcf-messages");
    submitButton.prop("disabled", true).text("Sending...");
    message.empty();
    var formData = {
      action: "bcf_submit_form",
      bcf_nonce: bcf_ajax.nonce,
    };
    form.find('input:not([name="bcf_nonce"]), textarea').each(function () {
      var field = $(this),
        fieldName = field.attr("name");
      if (fieldName && fieldName.startsWith("bcf_")) {
        formData[fieldName] = field.val();
      }
    });
    /** Submit via ajax */
    $.post(bcf_ajax.ajax_url, formData, function (response) {
      if (response.success) {
        message.html(
          '<div class="bcf-message success">' + response.data + "</div>"
        );
        form[0].reset();
      } else {
        message.html(
          '<div class="bcf-message error">' + response.data + "</div>"
        );
      }
    })
      .fail(function () {
        message.html(
          '<div class="bcf-message error">An error occurred. Please try again.</div>'
        );
      })
      .always(function () {
        submitButton.prop("disabled", false).text("Send Message");
        // Scroll to messages
        $("html, body").animate(
          {
            scrollTop: message.offset().top - 20,
          },
          500
        );
      });
  });
});
