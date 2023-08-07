$(document).ready(function () {
  const serviceApi = '/index.php/archive/service/Archive_service';

  $('#archive-add').submit(async function (event) {
    event.preventDefault();

    $(this).formProcess();

    const form = $(this).get(0);
    const data = new FormData(form);

    const response = await $.ajax(`${serviceApi}/add_eo`, {
      type: 'POST',
      cache: false,
      dataType: 'json',
      data: data,
      processData: false,
      contentType: false
    });

    if (response.success) {
      $(this).formDone(response.message);
    } else {
      $(this).formError(response.message);
    }
  });
});
