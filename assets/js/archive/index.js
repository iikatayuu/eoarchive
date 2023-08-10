$(document).ready(function () {
  const serviceApi = '/index.php/archive/service/Archive_service';
  const tempItem = $('#temp-table-eo').prop('content');
  const tempPage = $('#temp-page').prop('content');
  const limit = 10;
  let totalCount = 0;
  let offset = 0;
  let qDescription = '';
  let qSeries = '';
  let qAuthor = '';
  let qApproved = '';
  let qApprovedFrom = '';
  let qApprovedTo = '';
  let searchTimeout = null;

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
    params.set('description', qDescription);
    params.set('series', qSeries);
    params.set('author', qAuthor);
    params.set('approved_by', qApproved);
    params.set('approved_from', qApprovedFrom);
    params.set('approved_to', qApprovedTo);
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
    if (totalPages > 7) {
      const addEllipsis = () => {
        const ellipsis = $(tempPage).clone(true, true);
        const link = $(ellipsis).find('.page-link');
        $(link).attr('href', '#').addClass('disabled').text('...');
        $('#eos-next').parent().before(ellipsis);
      };

      const firstPage = $(tempPage).clone(true, true);
      const firstLink = $(firstPage).find('.page-link');
      const lastPage = $(tempPage).clone(true, true);
      const lastLink = $(lastPage).find('.page-link');

      firstLink.attr('href', '#').text(1).click(pageNav(1));
      lastLink.attr('href', '#').text(totalPages).click(pageNav(totalPages));

      if (active === 1) firstLink.addClass('active');
      if (active === totalPages) lastLink.addClass('active');

      $('#eos-next').parent().before(firstPage);
      if (active - 2 > 2) addEllipsis();

      for (let i = active - 2; i <= active + 2; i++) {
        if (i <= 1 || i >= totalPages) continue;

        const elem = $(tempPage).clone(true, true);
        const link = $(elem).find('.page-link');
  
        if (active === i) link.addClass('active');
        link.attr('href', '#').text(i).click(pageNav(i));
  
        $('#eos-next').parent().before(elem);
      }

      if (active + 2 < totalPages - 1) addEllipsis();
      $('#eos-next').parent().before(lastPage);
    } else {
      for (let i = 0; i < totalPages; i++) {
        const elem = $(tempPage).clone(true, true);
        const link = $(elem).find('.page-link');
        const page = i + 1;
  
        if (page === active) link.addClass('active');
        link.attr('href', '#').text(page).click(pageNav(page));
  
        $('#eos-next').parent().before(elem);
      }
    }

    $('#eos-prev').parent().toggleClass('disabled', active === 1);
    $('#eos-next').parent().toggleClass('disabled', active === totalPages || totalPages === 0);
  }

  async function submitSearch (advanced = false) {
    if (advanced) {
      qDescription = $('#archive-search-description').val();
      qSeries = $('#archive-search-series').val();
      qAuthor = $('#archive-search-author').val();
      qApproved = $('#archive-search-approved').val();
      qApprovedFrom = $('#archive-search-approved-date-from').val();
      qApprovedTo = $('#archive-search-approved-date-to').val();
    } else {
      qDescription = $('#archive-search-q').val();
      qSeries = '';
      qAuthor = '';
      qApproved = '';
      qApprovedFrom = '';
      qApprovedTo = '';
    }

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

  $('#archive-advanced-search').submit(async function (event) {
    event.preventDefault();
    await submitSearch(true);
  });

  $('#btn-adv-search').click(function () {
    $('#archive-advanced-search').show();
    $('#archive-search').hide();
  });

  $('#btn-simple-search').click(function () {
    $('#archive-advanced-search').hide();
    $('#archive-search').show();
  });

  loadItems();
});
