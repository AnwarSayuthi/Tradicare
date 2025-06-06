<div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">My Orders</h5>
        </div>
        <div class="card-body">
            @include('customer.profile.partials.order-tabs')
            @include('customer.profile.partials.order-content')
        </div>
    </div>
</div>