$(document).ready(function () {
  if (typeof pdfjsLib === 'undefined') throw new Error('PDF.js was not loaded');
  pdfjsLib.GlobalWorkerOptions.workerSrc = `${window.baseUrl}assets/libs/pdf.js/build/pdf.worker.js`;

  const serviceApi = `${window.baseUrl}archive/service/Archive_service`;
  const parts = window.location.pathname.split('/');
  const id = parts[parts.length - 1];
  let pageActive = 1;
  let pagePending = null;
  let pdf = null;
  let totalPages = 0;
  let rendering = false;

  async function loadDocument () {
    pdf = await pdfjsLib.getDocument(`${serviceApi}/get_pdf/${id}`).promise;
    totalPages = pdf.numPages;
    $('#pdf-total-pages').val(totalPages);
  }

  async function renderPage (pageNum) {
    if (pdf === null) return;
    rendering = true;

    $('#pdf-page').val(pageNum);
    $('#pdf-prev').attr('disabled', () => pageNum <= 1 ? true : null);
    $('#pdf-next').attr('disabled', () => pageNum >= totalPages ? true : null);

    const page = await pdf.getPage(pageNum);
    const viewport = page.getViewport({ scale: 1.5 });
    const canvas = $('#canvas-pdf').get(0);
    const context = canvas.getContext('2d');
    canvas.height = viewport.height;
    canvas.width = viewport.width;

    await page.render({
      canvasContext: context,
      viewport: viewport
    }).promise;

    rendering = false;
    if (pagePending !== null) {
      renderPage(pagePending);
      pagePending = null;
    }
  }

  async function queuePage (page) {
    if (rendering) pagePending = page;
    else await renderPage(page);
  }

  $('#pdf-prev').click(async function (event) {
    event.preventDefault();
    if (pageActive <= 1) return;
    pageActive--;
    await queuePage(pageActive);
  });

  $('#pdf-next').click(async function (event) {
    event.preventDefault();
    if (pageActive >= totalPages) return;
    pageActive++;
    await queuePage(pageActive);
  });

  $('#pdf-page').on('change', async function (event) {
    const page = parseInt($(this).val());
    if (page <= 1 || page >= totalPages) return;
    pageActive = page;
    await queuePage(page);
  });

  loadDocument().then(async function () {
    await renderPage(1);
  });
});
