<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAddressForm" action="{{ route('customer.location.add') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="add_location_name" class="form-label">Location Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_location_name" name="location_name" required>
                        <small class="form-text text-muted">e.g., Home, Office, etc.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="add_address_line1" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_address_line1" name="address_line1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="add_address_line2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="add_address_line2" name="address_line2">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_city" class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_state" class="form-label">State <span class="text-danger">*</span></label>
                            <select class="form-control" id="add_state" name="state" required>
                                <option value="">Select State</option>
                                <option value="Johor">Johor</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                <option value="Labuan">Labuan</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Penang">Penang</option>
                                <option value="Perak">Perak</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Putrajaya">Putrajaya</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Terengganu">Terengganu</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="add_postal_code" class="form-label">Postal Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_postal_code" name="postal_code" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="add_phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="add_phone_number" name="phone_number">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="add_is_default" name="is_default" value="1">
                            <label class="form-check-label" for="add_is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="bi bi-plus-circle me-2"></i>Add Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>