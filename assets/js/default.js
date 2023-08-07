$(document).ready(function () {
  const tempAskBtn = $('#temp-ask-btn').prop('content');

  $.fn.extend({
    formProcess: function () {
      const submits = $(this).find('[type="submit"]').attr('disabled', true);
      $(this).find('.form-error,.form-success').text('').hide();
      submits.find('.btn-display').hide();
      submits.find('.btn-process').show();
    },
    formDone: function (message, reset = true) {
      const submits = $(this).find('[type="submit"]').attr('disabled', null);
      if (reset) $(this).trigger('reset');
      $(this).find('.form-success').text(message).show();
      submits.find('.btn-display').show();
      submits.find('.btn-process').hide();
    },
    formError: function (message) {
      const submits = $(this).find('[type="submit"]').attr('disabled', null);
      $(this).find('.form-error').text(message).show();
      submits.find('.btn-display').show();
      submits.find('.btn-process').hide();
    }
  });

  $.extend({
    modalAsk: function (options) {
      const { title, body, buttons } = options;
      $('#modal-ask').find('.modal-title').text(title);
      $('#modal-ask').find('.modal-body').html(body);
      $('#modal-ask').find('.modal-footer').empty();

      for (let i = 0; i < buttons.length; i++) {
        const button = buttons[i];
        const elem = $(tempAskBtn).clone(true, true).find('button');
        if (button.dismiss) elem.attr('data-bs-dismiss', 'modal');
        if (button.type) elem.addClass('btn-' + button.type);
        if (typeof button.action === 'function') {
          elem.click(function (event) {
            button.action(event);
            $('#modal-ask').modal('hide');
          });
        }

        elem.text(button.text);
        $('#modal-ask').find('.modal-footer').append(elem);
      }

      $('#modal-ask').modal('show');
    }
  });
});
