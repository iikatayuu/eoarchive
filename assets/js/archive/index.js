$(document).ready(function () {
  const serviceApi = '/index.php/archive/service/Archive_service';
  const tempItem = $('#temp-table-eo').prop('content');
  const tempPage = $('#temp-page').prop('content');
  const limit = 10;
  let totalCount = 0;
  let offset = 0;
  let query = '';

  function pageNav (page) {
    return async function (event) {
      event.preventDefault();

      offset = (limit * page) - limit;
      await loadItems();
    }
  }

  function deleteItem (event) {
    event.preventDefault();
    const id = $(this).data('id');
    $.modalAsk({
      title: 'Delete item?',
      body: `Are you sure to delete item with ID: ${id}?`,
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
      const response = await $.ajax(`${serviceApi}/delete_eo`, {
        type: 'POST',
        cache: false,
        dataType: 'json',
        data: { id: id }
      });
  
      if (response.success) {
        offset = 0;
        totalCount = 0;
        await loadItems();
      } else {
        alert(response.message);
      }
    }
  }

  async function loadItems () {
    const params = new URLSearchParams();
    params.set('query', query);
    params.set('offset', offset);
    params.set('limit', limit);
    const items = await $.getJSON(`${serviceApi}/eos?${params.toString()}`);

    $('#table-eos').empty();
    $('#eos-pagination').children().not(':first').not(':last').remove();
    totalCount = items.count;

    for (let i = 0; i < items.results.length; i++) {
      const item = items.results[i];
      const elem = $(tempItem).clone(true, true);

      $(elem).find('.eo-number').text(item.number);
      $(elem).find('.eo-description').text(item.description);
      $(elem).find('.eo-author').text(item.author);
      $(elem).find('.eo-date-approved').text(item.date_approved);
      $(elem).find('.eo-view,.eo-edit').attr('href', (i, value) => value + item.id);
      $(elem).find('.eo-delete').data('id', item.id).click(deleteItem);
      $('#table-eos').append(elem);
    }

    const active = (offset / limit) + 1;
    const totalPages = Math.ceil(totalCount / limit);
    for (let i = 0; i < totalPages; i++) {
      const elem = $(tempPage).clone(true, true);
      const page = i + 1;
      const link = $(elem).find('.page-link');

      if (page === active) link.addClass('active');
      link.attr('href', '#').text(page).click(pageNav(page));

      $('#eos-next').parent().before(elem);
    }

    $('#eos-prev').parent().toggleClass('disabled', active === 1);
    $('#eos-next').parent().toggleClass('disabled', active === totalPages || totalPages === 0);
  }

  async function submitSearch () {
    query = $('#archive-search-q').val();
    offset = 0;
    await loadItems();
  }

  $('#eos-prev').click(async function (event) {
    event.preventDefault();

    offset -= limit;
    await loadItems();
  });

  $('#eos-next').click(async function (event) {
    event.preventDefault();

    offset += limit;
    await loadItems();
  });

  let searchTimeout = null;
  $('#archive-search-q').on('keyup', function () {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function () {
      submitSearch();
    }, 500);
  });

  $('#archive-search').submit(async function (event) {
    event.preventDefault();
    await submitSearch();
  });

  loadItems();
});
