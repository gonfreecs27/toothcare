<?php
require '../../../init.php';
Permission::authorize(['admin']);

Component::header(false, null, [
    PROJECT_BASE . 'assets/js/services.js'
], [
    PROJECT_BASE . 'assets/css/services.css'
]);
Component::sidebar();
?>

<div class="main-wrapper">
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h3 class="fw-bold mb-1">Services</h3>
                <small class="text-muted">
                    Manage clinic services and pricing
                </small>
            </div>

            <button class="btn btn-primary" id="btnAddService">
                <i class="bi bi-plus-circle me-1"></i>
                Add Service
            </button>

        </div>

        <!-- FILTERS -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">

                <div class="row g-2">

                    <div class="col-md-6">
                        <input type="text"
                            class="form-control"
                            id="searchService"
                            placeholder="Search services...">
                    </div>

                    <div class="col-md-3">
                        <select class="form-select" id="filterDuration">
                            <option value="">All Durations</option>
                            <option value="15">15 mins</option>
                            <option value="30">30 mins</option>
                            <option value="60">1 hour</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select class="form-select" id="sortServices">
                            <option value="name">Sort by Name</option>
                            <option value="price">Sort by Price</option>
                            <option value="duration">Sort by Duration</option>
                        </select>
                    </div>

                </div>

            </div>
        </div>

        <!-- SERVICES GRID -->
        <div class="row g-3" id="serviceContainer"></div>

        <div class="row g-3" id="serviceContainer"></div>

        <div class="d-flex justify-content-center mt-4">
            <nav>
                <ul class="pagination" id="servicePagination"></ul>
            </nav>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-heart-pulse me-2"></i>
                    Service
                </h5>

                <button class="btn-close btn-close-white"
                    data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="serviceForm">

                    <input type="hidden" name="id">

                    <div class="mb-2">
                        <label class="form-label">Service Name</label>

                        <input type="text"
                            name="name"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Description</label>

                        <textarea name="description"
                            class="form-control"
                            rows="2"></textarea>
                    </div>

                    <div class="row g-2">

                        <div class="col-md-6">
                            <label class="form-label">Price</label>

                            <input type="number"
                                name="price"
                                class="form-control"
                                min="0"
                                step="0.01"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Duration</label>

                            <select name="duration_minutes"
                                class="form-select">

                                <option value="15">15 mins</option>
                                <option value="30">30 mins</option>
                                <option value="45">45 mins</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>

                            </select>
                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer border-0">

                <button class="btn btn-light border"
                    data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn btn-primary"
                    id="btnSaveService">
                    Save Service
                </button>

            </div>

        </div>
    </div>
</div>

<?php Component::footer(); ?>