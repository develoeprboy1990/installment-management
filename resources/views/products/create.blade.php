@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Add New Product</h1>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" class="form-control" id="company" name="company" placeholder="eg. Vivo, Samsung , PEL" value="{{ old('company') }}" required>
        </div>

        <div class="form-group">
            <label for="model">Model</label>
            <input type="text" class="form-control" id="model" name="model" placeholder="eg. Vivo y36" value="{{ old('model') }}" required>
        </div>

        <div class="form-group">
            <label for="serial_no">Serial No</label>
            <input type="text" class="form-control" id="serial_no" name="serial_no" placeholder="eg, 12345" value="{{ old('serial_no') }}" required>
        </div>

        <div class="form-group">
            <label for="price">Cost Price</label>
            <input type="number" class="form-control" id="cost_price" name="cost_price" placeholder="eg. RS 456" value="{{ old('cost_price') }}" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label for="price">Sell Price</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="eg. RS 456" value="{{ old('price') }}" step="0.01" min="0" required>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('products.index') }}" class="btn btn-default">Cancel</a>
    </form>
</div>
@endsection