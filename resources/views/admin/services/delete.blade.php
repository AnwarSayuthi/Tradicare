<div class="modal fade" id="deleteModal{{ $service->service_id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $service->service_id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="deleteModalLabel{{ $service->service_id }}">
                    <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Delete Service
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger bg-danger-subtle border-0">
                    <p class="mb-0">Are you sure you want to delete service "<strong>{{ $service->service_name }}</strong>"?</p>
                    <p class="mb-0 mt-2">This service will be hidden from customers, but all past appointments will be preserved.</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.services.destroy', $service->service_id) }}" method="POST" class="service-delete-form d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">Delete Service</button>
                </form>
            </div>
        </div>
    </div>
</div>