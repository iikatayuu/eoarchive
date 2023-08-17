$(document).ready(function () {
  const serviceApi = `${window.baseUrl}archive/service/Archive_service`;

  $('#archive-edit').submit(async function (event) {
    event.preventDefault();

    $(this).formProcess();

    const form = $(this).get(0);
    const data = new FormData(form);
    const files = $('#eo-file').prop('files');
    if (files.length === 0) data.delete('eo-file');

    const response = await $.ajax(`${serviceApi}/edit_eo`, {
      type: 'POST',
      cache: false,
      dataType: 'json',
      data: data,
      processData: false,
      contentType: false
    });

    if (response.success) {
      $(this).formDone(response.message, false);
    } else {
      $(this).formError(response.message);
    }
  });
});
