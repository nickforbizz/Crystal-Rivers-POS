@extends('layouts.cms')

@section('content')
<div class="page-inner">
  <div class="page-header">
    <h4 class="page-title">Orders</h4>
    <ul class="breadcrumbs">
      <li class="nav-home"><a href="#"><i class="flaticon-home"></i></a></li>
      <li class="separator"><i class="flaticon-right-arrow"></i></li>
      <li class="nav-item"><a href="#">Orders</a></li>
      <li class="separator"><i class="flaticon-right-arrow"></i></li>
      <li class="nav-item"><a href="#">Create</a></li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white py-3 d-flex align-items-center justify-content-between rounded-top-4">
          <h4 class="mb-0"><i class="flaticon-interface-5 mr-2"></i> Add / Edit Order</h4>
          <a href="{{ route('orders.index') }}" class="btn btn-light btn-round">
            <i class="flaticon-left-arrow-4 mr-2"></i> View Records
          </a>
        </div>

        <div class="card-body p-4">
          @include('cms.helpers.partials.feedback')

          <form id="orders-create"
                action="@if(isset($order->id)) {{ route('orders.update', ['order' => $order->id]) }} @else {{ route('orders.store') }} @endif"
                method="post">

            @csrf
            @if(isset($order->id))
              @method('PUT')
              <input type="hidden" name="created_by" value="{{ auth()->id() }}">
            @endif

            <!-- ========== Order Info Section ========== -->
            <div class="row mb-4">
              <div class="col-md-4">
                <label for="order_number" class="form-label fw-semibold">Order Number</label>
                <input id="order_number" type="text" readonly class="form-control shadow-sm rounded" name="order_number"
                       placeholder="Auto-generated..." value="{{ old('order_number') }}" required>
                @error('order_number') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="col-md-4">
                <label for="order_date" class="form-label fw-semibold">Order Date</label>
                <input id="order_date" type="date" class="form-control shadow-sm rounded" name="order_date"
                       value="{{ old('order_date', now()->format('Y-m-d')) }}" required>
                @error('order_date') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="col-md-4">
                <label for="fk_customer" class="form-label fw-semibold">Customer</label>
                <select name="fk_customer" id="fk_customer" class="form-control shadow-sm rounded" required>
                  <option value="">-- Select Customer --</option>
                  @foreach($customers as $customer)
                    <option value="{{ $customer->id }}"
                            {{ old('fk_customer') == $customer->id ? 'selected' : '' }}>
                      {{ $customer->names }}
                    </option>
                  @endforeach
                </select>
                @error('fk_customer') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <!-- ========== Product & Status Section ========== -->
            <div class="row mb-4">
              <div class="col-md-4">
                <label for="status" class="form-label fw-semibold">Status</label>
                <select name="status" id="status" class="form-control shadow-sm rounded" required>
                  <option value="">-- Select Status --</option>
                  <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                  <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                </select>
                @error('status') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="col-md-4">
                <label for="fk_product" class="form-label fw-semibold">Product</label>
                <select name="fk_product" id="fk_product" class="form-control shadow-sm rounded" required>
                  <option value="">-- Select Product --</option>
                  @foreach($products as $product)
                    <option value="{{ $product->id }}"
                            data-price="{{ $product->price }}"
                            data-stock="{{ $product->quantity }}"
                            {{ old('fk_product') == $product->id ? 'selected' : '' }}>
                      {{ e($product->title) . ' — Ksh ' . number_format($product->price) }}
                    </option>
                  @endforeach
                </select>
                @error('fk_product') <span class="text-danger">{{ $message }}</span> @enderror
              </div>

              <div class="col-md-4">
                <label for="quantity" class="form-label fw-semibold">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control shadow-sm rounded"
                       value="{{ old('quantity', 1) }}" min="1">
                @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
              </div>
            </div>

            <!-- ========== Pricing Summary Section ========== -->
            <div class="row mb-4 align-items-end">
              <div class="col-md-4">
                <label class="form-label fw-semibold">Available Stock</label>
                <input type="text" id="available_stock" class="form-control shadow-sm rounded bg-light" readonly>
              </div>

              <div class="col-md-4">
                <label class="form-label fw-semibold">Price per Unit (Ksh)</label>
                <input type="text" id="unit_price" class="form-control shadow-sm rounded bg-light" readonly>
              </div>

              <div class="col-md-4">
                <label for="total_amount" class="form-label fw-semibold">Total Amount (Ksh)</label>
                <input id="total_amount" type="number" min="0"
                       class="form-control shadow-sm rounded text-success fw-bold bg-light"
                       name="amount" readonly>
              </div>
            </div>

            <!-- ========== Buttons ========== -->
            <div class="card-footer bg-light d-flex justify-content-between rounded-bottom-4 mt-4">
              <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="flaticon-cancel-12"></i> Cancel
              </a>
              <button class="btn btn-success rounded-pill px-4">
                <i class="flaticon-interface-10"></i> Submit Order
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Optional: Add a nice dropdown arrow for consistent style */
  select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='10' height='10'><path d='M0 0 L10 0 L5 7 Z' fill='%23666'/></svg>");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 10px;
    padding-right: 30px;
  }
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {

  // === Generate Order Number ===
  function generateOrderNumber(customerId = '000') {
    const today = new Date();
    const datePart = today.getFullYear().toString() +
                     String(today.getMonth() + 1).padStart(2, '0') +
                     String(today.getDate()).padStart(2, '0');
    const random = Math.random().toString(36).substring(2, 7).toUpperCase();
    return `${datePart}/${customerId.padStart(3, '0')}/${random}`;
  }

  // Initialize order number
  if (!$('#order_number').val()) {
    $('#order_number').val(generateOrderNumber());
  }

  $('#fk_customer').on('change', function() {
    const customerId = $(this).val() || '000';
    $('#order_number').val(generateOrderNumber(customerId));
  });

  // === Product Selection & Total Calculation ===
  $('#fk_product').on('change', function() {
    const selected = $(this).find(':selected');
    const price = parseFloat(selected.data('price')) || 0;
    const stock = parseInt(selected.data('stock')) || 0;

    // Update price and stock display fields
    $('#unit_price').val(price);
    $('#available_stock').val(stock);

    // Reset quantity if greater than stock
    const qty = parseInt($('#quantity').val()) || 0;
    if (qty > stock) {
      $('#quantity').val(stock);
    }

    // Update max attribute for quantity input
    $('#quantity').attr('max', stock);

    updateTotal();
  });

  // === Quantity Input Handler ===
  $('#quantity').on('input', function() {
    const stock = parseInt($('#available_stock').val()) || 0;
    let qty = parseInt($(this).val()) || 0;

    // Restrict to min 1 and max available stock
    if (qty < 1) {
      qty = 1;
    } else if (qty > stock) {
      qty = stock;
    }

    $(this).val(qty);
    updateTotal();
  });

  // === Total Calculator ===
  function updateTotal() {
    const qty = parseFloat($('#quantity').val()) || 0;
    const price = parseFloat($('#unit_price').val()) || 0;
    $('#total_amount').val((qty * price).toFixed(2));
  }

  // === Validate Before Submit ===
  $('#orders-create').on('submit', function(e) {
    const orderNum = $('#order_number').val();
    const validFormat = /^\d{8}\/\d{3}\/[A-Z0-9]{5}$/;
    if (!validFormat.test(orderNum)) {
      alert('Invalid order number format.');
      e.preventDefault();
    }

    const stock = parseInt($('#available_stock').val()) || 0;
    const qty = parseInt($('#quantity').val()) || 0;

    if (qty > stock) {
      alert('Quantity exceeds available stock.');
      e.preventDefault();
    }
  });

});
</script>
@endpush
