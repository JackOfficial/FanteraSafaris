@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3"><i class="fas fa-cogs mr-2"></i>System Settings</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h5 class="card-title">General Configuration</h5>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="Fantera Safaris">
                        </div>
                        
                        <div class="form-group">
                            <label>Contact Email (For Inquiries)</label>
                            <input type="email" name="contact_email" class="form-control" placeholder="info@fanterasafaris.com">
                        </div>

                        <div class="form-group">
                            <label>WhatsApp Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                </div>
                                <input type="text" name="whatsapp_number" class="form-control" placeholder="+255...">
                            </div>
                        </div>

                        <hr>
                        <h6>Social Media Links</h6>
                        <div class="form-group">
                            <label>Instagram URL</label>
                            <input type="url" name="social_instagram" class="form-control">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm bg-info">
                <div class="card-body">
                    <h5><i class="fas fa-info-circle"></i> Quick Tip</h5>
                    <p>These settings are used globally across your website. Updating the WhatsApp number here will update it in the footer and contact pages automatically.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection