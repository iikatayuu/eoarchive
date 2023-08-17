$(document).ready(function () {
  const serviceApi = `${window.baseUrl}admins/service/Admins_service`;
  const params = new URLSearchParams(window.location.search);
  const next = params.get('next') || `${window.baseUrl}admins`;

  $('#admin-login').submit(async function (event) {
    event.preventDefault();

    $(this).formProcess();

    const response = await $.ajax(`${serviceApi}/login`, {
      type: 'POST',
      cache: false,
      dataType: 'json',
      data: {
        username: $('#admin-username').val(),
        password: $('#admin-password').val()
      }
    });

    if (response.success) {
      $(this).formDone(response.message, false);
      window.location.href = next;
    } else {
      $(this).formError(response.message);
    }
  });
});
