@extends('admin.layout.layout')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/smmx/css/smmx.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/smmx/js/smmx.js') }}"></script>
<script>
    // Optional: enhance tooltips, etc.
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush


    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit Onboarding</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.smmx.onboarding.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header"><h3 class="card-title">Edit Social Media Onboarding</h3></div>
                    <div class="card-body">
                        @include('admin.smmx.partials.form')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>Business Name</label><input type="text" name="business_name" class="form-control" value="{{ old('business_name', $item->business_name) }}" required></div>
                                <div class="form-group"><label>Brand Name</label><input type="text" name="brand_name" class="form-control" value="{{ old('brand_name', $item->brand_name) }}"></div>
                                <div class="form-group"><label>Contact Person</label><input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $item->contact_person) }}"></div>
                                <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $item->phone) }}"></div>
                                <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $item->email) }}"></div>
                                <div class="form-group"><label>Business Address</label><textarea name="business_address" class="form-control">{{ old('business_address', $item->business_address) }}</textarea></div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label>Facebook Link</label><input type="text" name="facebook_link" class="form-control" value="{{ old('facebook_link', $item->facebook_link) }}"></div>
                                <div class="form-group"><label>Instagram Link</label><input type="text" name="instagram_link" class="form-control" value="{{ old('instagram_link', $item->instagram_link) }}"></div>
                                <div class="form-group"><label>TikTok Link</label><input type="text" name="tiktok_link" class="form-control" value="{{ old('tiktok_link', $item->tiktok_link) }}"></div>
                                <div class="form-group"><label>Website Link</label><input type="text" name="website_link" class="form-control" value="{{ old('website_link', $item->website_link) }}"></div>
                                <div class="form-group"><label>Page Access Status</label><input type="text" name="page_access_status" class="form-control" value="{{ old('page_access_status', $item->page_access_status) }}"></div>
                                <div class="form-group"><label>Business Manager Status</label><input type="text" name="business_manager_status" class="form-control" value="{{ old('business_manager_status', $item->business_manager_status) }}"></div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"><label>Primary Goal</label><input type="text" name="primary_goal" class="form-control" value="{{ old('primary_goal', $item->primary_goal) }}"></div>
                                <div class="form-group"><label>Target Location</label><input type="text" name="target_location" class="form-control" value="{{ old('target_location', $item->target_location) }}"></div>
                                <div class="form-group"><label>Target Age Group</label><input type="text" name="target_age_group" class="form-control" value="{{ old('target_age_group', $item->target_age_group) }}"></div>
                                <div class="form-group"><label>Target Gender</label><input type="text" name="target_gender" class="form-control" value="{{ old('target_gender', $item->target_gender) }}"></div>
                                <div class="form-group"><label>Target Interests</label><textarea name="target_interests" class="form-control">{{ old('target_interests', $item->target_interests) }}</textarea></div>
                                <div class="form-group"><label>Competitors</label><textarea name="competitors" class="form-control">{{ old('competitors', $item->competitors) }}</textarea></div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group"><label>Brand Colors</label><input type="text" name="brand_colors" class="form-control" value="{{ old('brand_colors', $item->brand_colors) }}"></div>
                                <div class="form-group"><label>Preferred Language</label><input type="text" name="preferred_language" class="form-control" value="{{ old('preferred_language', $item->preferred_language) }}"></div>
                                <div class="form-group"><label>Content Preferences</label><textarea name="content_preferences" class="form-control">{{ old('content_preferences', $item->content_preferences) }}</textarea></div>
                                <div class="form-group"><label>Monthly Budget</label><input type="text" name="monthly_budget" class="form-control" value="{{ old('monthly_budget', $item->monthly_budget) }}"></div>
                                <div class="form-group">
                                    <label><input type="checkbox" name="approval_required" value="1" {{ old('approval_required', $item->approval_required) ? 'checked' : '' }}> Approval Required</label>
                                </div>
                                <div class="form-group"><label>Approval Contact</label><input type="text" name="approval_contact" class="form-control" value="{{ old('approval_contact', $item->approval_contact) }}"></div>
                                <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control">{{ old('notes', $item->notes) }}</textarea></div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="draft" {{ $item->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="completed" {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button class="btn btn-primary">Update Onboarding</button>
                        <a href="{{ route('admin.smmx.onboarding.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection