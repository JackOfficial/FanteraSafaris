@extends('admin.layouts.app')
@section('title', "Destination")
@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold">Add Safari Destination</h3>
        </div>
        <div class="card-body">
            @include('admin.partials.alerts')

            <form action="{{ route('admin.destinations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Destination Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Serengeti National Park" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Country</label>
                        <select name="country" class="form-control" required>
                            <option value="Uganda">Uganda</option>
                            <option value="Rwanda">Rwanda</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Tanzania">Tanzania</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 form-group">
                        <label>Cover Image</label>
                        <input type="file" name="image" class="form-control-file">
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" name="is_featured" value="1" class="custom-control-input" id="featured">
                            <label class="custom-control-label" for="featured">Show on Homepage Destinations?</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning px-5 mt-4 font-weight-bold">Save Destination</button>
            </form>
        </div>
    </div>
@endsection