<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Cancel Order
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-question-circle text-warning" style="font-size: 3rem;"></i>
                </div>
                <h6 class="mb-3">Are you sure you want to cancel this order?</h6>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left me-2"></i>No, Keep Order
                </button>
                <button type="button" class="btn btn-danger px-4" id="confirmCancelOrder" data-order-id="">
                    <i class="bi bi-x-circle me-2"></i>Yes, Cancel Order
                </button>
            </div>
        </div>
    </div>
</div>