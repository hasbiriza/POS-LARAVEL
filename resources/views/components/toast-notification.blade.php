@if(session('success') || session('error'))
    <div class="bs-toast toast toast-placement-ex m-2 fade {{ session('success') ? 'bg-primary' : 'bg-danger' }} top-0 end-0 fade show"
                        role="alert"
                        aria-live="assertive"
                        aria-atomic="true">
        <div class="toast-header">
            <i class="bx {{ session('success') ? 'bx-check-circle text-success' : 'bx-x-circle text-danger' }} me-2"></i>
            <div class="me-auto fw-medium">{{ session('success') ? 'Success' : 'Error' }}</div>
            <small>Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <p class="mb-0">{{ session('success') ?? session('error') }}</p>
            <div class="mt-2 pt-2 border-top">
                <button type="button" class="btn btn-sm {{ session('success') ? 'btn-success' : 'btn-danger' }}" data-bs-dismiss="toast">OK</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="toast">Close</button>
            </div>
        </div>
    </div>
@endif