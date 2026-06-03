$(document).ready(function () {
    let currentPage = 1;
    let lastPage = 1;
    let services = [];

    const $modal = $('#serviceModal');
    const $form = $('#serviceForm');

    function getServiceIcon(name = '') {
        name = name.toLowerCase();

        if (name.includes('clean')) return 'bi-stars';
        if (name.includes('extract')) return 'bi-scissors';
        if (name.includes('brace')) return 'bi-align-center';
        if (name.includes('whiten')) return 'bi-brightness-high';
        if (name.includes('check')) return 'bi-heart-pulse';

        return 'bi-heart-pulse';
    }

    function loadServices(page = 1) {
        const search = $('#searchService').val();
        const duration = $('#filterDuration').val();
        const sort = $('#sortServices').val();

        $.get(App.api('services/list'), {
            page,
            limit: 12,
            search,
            duration,
            sort
        }, function (response) {
            services = response.data.data;

            currentPage = response.data.pagination.page;
            lastPage = response.data.pagination.pages;

            renderServices();
            renderPagination();
        });
    }

    function renderServices() {
        let html = '';

        if (!services.length) {
            $('#serviceContainer').html(`
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <h5 class="mt-3">No Services Found</h5>
                        </div>
                    </div>
                </div>
            `);
            return;
        }

        services.forEach(service => {
            html += `
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 service-card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start mb-3">

                                <div class="service-icon">
                                    <i class="bi ${getServiceIcon(service.name)}"></i>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light"
                                            data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item btnEditService"
                                                    data-id="${service.id}">
                                                <i class="bi bi-pencil me-2"></i>
                                                Edit
                                            </button>
                                        </li>

                                        <li>
                                            <button class="dropdown-item text-danger btnDeleteService"
                                                    data-id="${service.id}">
                                                <i class="bi bi-trash me-2"></i>
                                                Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <h5 class="mb-1">${service.name}</h5>

                            <p class="text-muted small mb-3">
                                ${service.description || 'No description'}
                            </p>

                            <div class="d-flex justify-content-between align-items-center">

                                <span class="badge bg-success">
                                    Php ${Number(service.price).toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })}
                                </span>

                                <span class="badge bg-light text-dark border">
                                    ${service.duration_minutes} mins
                                </span>

                            </div>

                        </div>
                    </div>
                </div>
            `;
        });

        $('#serviceContainer').html(html);
    }

    function renderPagination() {
        let html = '';
        html += `
            <li class="page-item ${currentPage == 1 ? 'disabled' : ''}">
                <button class="page-link btnPage"
                        data-page="${currentPage - 1}">
                    Prev
                </button>
            </li>
        `;

        for (let i = 1; i <= lastPage; i++) {
            html += `
                <li class="page-item ${currentPage == i ? 'active' : ''}">
                    <button class="page-link btnPage"
                            data-page="${i}">
                        ${i}
                    </button>
                </li>
            `;
        }

        html += `
            <li class="page-item ${currentPage == lastPage ? 'disabled' : ''}">
                <button class="page-link btnPage"
                        data-page="${currentPage + 1}">
                    Next
                </button>
            </li>
        `;

        $('#servicePagination').html(html);
    }

    function resetForm() {
        $form[0].reset();
        $('input[name="id"]').val('');
    }

    $('#btnAddService').on('click', function () {
        resetForm();
        $modal.modal('show');
    });

    $('#btnSaveService').on('click', function () {
        $.ajax({
            url: App.api('services/save'),
            type: 'POST',
            data: $form.serialize(),
            success() {
                $modal.modal('hide');
                loadServices(currentPage);
                alertify.success('Service saved');
            },
            error(xhr) {
                alertify.error(
                    xhr.responseJSON?.error || 'Failed to save service'
                );
            }
        });
    });

    $(document).on('click', '.btnEditService', function () {
        const id = $(this).data('id');
        const service = services.find(s => s.id == id);
        if (!service) return;
        $('input[name="id"]').val(service.id);
        $('input[name="name"]').val(service.name);
        $('textarea[name="description"]').val(service.description);
        $('input[name="price"]').val(service.price);
        $('select[name="duration_minutes"]').val(service.duration_minutes);
        $modal.modal('show');
    });

    $(document).on('click', '.btnDeleteService', function () {
        const id = $(this).data('id');
        alertify.confirm(
            'Delete Service',
            'Are you sure you want to delete this service?',
            function () {
                $.ajax({
                    url: App.api(`services/delete`),
                    type: 'POST',
                    data: { id },
                    success() {
                        loadServices(currentPage);
                        alertify.success('Service deleted');
                    },
                    error(xhr) {
                        alertify.error(
                            xhr.responseJSON?.error || 'Failed to delete service'
                        );
                    }
                });
            },
            function () { }
        );
    });

    $(document).on('click', '.btnPage', function () {
        const page = $(this).data('page');
        if (page < 1 || page > lastPage) {
            return;
        }
        loadServices(page);
    });

    $('#searchService').on('keyup', function () {
        loadServices(1);
    });

    $('#filterDuration').on('change', function () {
        loadServices(1);
    });

    $('#sortServices').on('change', function () {
        loadServices(1);
    });
    loadServices();
});