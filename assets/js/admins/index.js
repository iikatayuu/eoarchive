$(document).ready(function () {
  const serviceApi = `${window.baseUrl}admins/service/Admins_service`;
  const tempItem = $('#temp-table-admin').prop('content');
  const tempPage = $('#temp-page').prop('content');
  const limit = 10;
  let totalCount = 0;
  let offset = 0;
  let qUsername = '';
  let searchTimeout = null;

  function pageNav (page) {
    return async function (event) {
      event.preventDefault();

      offset = (limit * page) - limit;
      await loadItems();
    }
  }

  function editAdmin (event) {
    event.preventDefault();
    const id = $(this).data('id');
    const username = $(this).data('username');

    $('#admin-edit-id').val(id);
    $('#modal-admin-edit').find('.modal-title').text(`Change ${username}'s password`);
    $('#modal-admin-edit').find('.alert').empty().hide();
    $('#modal-admin-edit').modal('show')
  }

  function deleteAdmin (event) {
    event.preventDefault();
    const id = $(this).data('id');
    const username = $(this).data('username');
    $.modalAsk({
      title: 'Delete item?',
      body: `Are you sure to delete item with username: ${username}?`,
      buttons: [
        {
          dismiss: true,
          text: 'Cancel'
        },
        {
          type: 'danger',
          text: 'Delete',
          action: proceedDelete(id)
        }
      ]
    });
  }

  function proceedDelete (id) {
    return async function (event) {
      const response = await $.ajax(`${serviceApi}/delete_admin`, {
        type: 'POST',
        cache: false,
        dataType: 'json',
        data: { id: id }
      });
  
      if (response.success) {
        offset = 0;
        totalCount = 0;
        await loadAdmins();
      } else {
        alert(response.message);
      }
    }
  }

  async function loadAdmins () {
    const params = new URLSearchParams();
    params.set('username', qUsername);
    params.set('offset', offset);
    params.set('limit', limit);
    const paramsQuery = params.toString();
    const admins = await $.getJSON(`${serviceApi}/admins?${paramsQuery}`);

    $('#table-admins').empty();
    $('#admins-pagination').children().not(':first').not(':last').remove();
    totalCount = admins.count;

    for (let i = 0; i < admins.results.length; i++) {
      const admin = admins.results[i];
      const elem = $(tempItem).clone(true, true);

      $(elem).find('.admin-id').text(admin.id)
      $(elem).find('.admin-username').text(admin.username)
      $(elem).find('.admin-edit').attr('href', (i, value) => value + admin.id);

      $(elem).find('.admin-edit').data({
        id: admin.id,
        username: admin.username
      }).click(editAdmin);

      $(elem).find('.admin-delete').data({
        id: admin.id,
        username: admin.username
      }).click(deleteAdmin);

      $('#table-admins').append(elem);
    }

    const active = (offset / limit) + 1;
    const totalPages = Math.ceil(totalCount / limit);
    if (totalPages > 7) {
      const addEllipsis = () => {
        const ellipsis = $(tempPage).clone(true, true);
        const link = $(ellipsis).find('.page-link');
        $(link).attr('href', '#').addClass('disabled').text('...');
        $('#admins-next').parent().before(ellipsis);
      };

      const firstPage = $(tempPage).clone(true, true);
      const firstLink = $(firstPage).find('.page-link');
      const lastPage = $(tempPage).clone(true, true);
      const lastLink = $(lastPage).find('.page-link');

      firstLink.attr('href', '#').text(1).click(pageNav(1));
      lastLink.attr('href', '#').text(totalPages).click(pageNav(totalPages));

      if (active === 1) firstLink.addClass('active');
      if (active === totalPages) lastLink.addClass('active');

      $('#admins-next').parent().before(firstPage);
      if (active - 2 > 2) addEllipsis();

      for (let i = active - 2; i <= active + 2; i++) {
        if (i <= 1 || i >= totalPages) continue;

        const elem = $(tempPage).clone(true, true);
        const link = $(elem).find('.page-link');
  
        if (active === i) link.addClass('active');
        link.attr('href', '#').text(i).click(pageNav(i));
  
        $('#admins-next').parent().before(elem);
      }

      if (active + 2 < totalPages - 1) addEllipsis();
      $('#admins-next').parent().before(lastPage);
    } else {
      for (let i = 0; i < totalPages; i++) {
        const elem = $(tempPage).clone(true, true);
        const link = $(elem).find('.page-link');
        const page = i + 1;
  
        if (page === active) link.addClass('active');
        link.attr('href', '#').text(page).click(pageNav(page));
  
        $('#admins-next').parent().before(elem);
      }
    }

    $('#admins-prev').parent().toggleClass('disabled', active === 1);
    $('#admins-next').parent().toggleClass('disabled', active === totalPages || totalPages === 0);
  }

  async function submitSearch (advanced = false) {
    qUsername = $('#admins-search-q').val();
    offset = 0;
    await loadAdmins();
  }

  $('#admins-prev').click(async function (event) {
    event.preventDefault();

    offset -= limit;
    await loadAdmins();
  });

  $('#admins-next').click(async function (event) {
    event.preventDefault();

    offset += limit;
    await loadAdmins();
  });

  $('#admins-search-q').on('keyup', function () {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function () {
      submitSearch();
    }, 500);
  });

  $('#admins-search').submit(async function (event) {
    event.preventDefault();
    await submitSearch();
  });

  $('#admins-add').submit(async function (event) {
    event.preventDefault();

    $(this).formProcess();

    const response = await $.ajax(`${serviceApi}/add_admin`, {
      type: 'POST',
      cache: false,
      dataType: 'json',
      data: {
        username: $('#admin-username').val(),
        password: $('#admin-password').val()
      }
    });

    if (response.success) {
      $(this).formDone(response.message);
      loadAdmins();
    } else {
      $(this).formError(response.message);
    }
  });

  $('#admin-edit').submit(async function (event) {
    event.preventDefault();

    $(this).formProcess();

    const response = await $.ajax(`${serviceApi}/edit_admin`, {
      type: 'POST',
      cache: false,
      dataType: 'json',
      data: {
        id: $('#admin-edit-id').val(),
        password: $('#admin-edit-password').val()
      }
    });

    if (response.success) {
      $(this).formDone(response.message);
      await loadAdmins();
      $('#modal-admin-edit').modal('hide');
    } else {
      $(this).formError(response.message);
    }
  });

  loadAdmins();
});
