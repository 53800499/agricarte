@extends('layouts.admin')

@section('title', 'Paramètres du site')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Paramètres du site</h1>
        </div>

        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Configuration générale</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Nom du site</label>
                                <input type="text" class="form-control" id="site_name" name="site_name"
                                    value="{{ old('site_name', optional($settings->where('key', 'site_name')->first())->value) }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="site_description" class="form-label">Description du site</label>
                                <textarea class="form-control" id="site_description" name="site_description" rows="3">{{ old('site_description', optional($settings->where('key', 'site_description')->first())->value) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Email de contact</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email"
                                    value="{{ old('contact_email', optional($settings->where('key', 'contact_email')->first())->value) }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Téléphone de contact</label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                                    value="{{ old('contact_phone', optional($settings->where('key', 'contact_phone')->first())->value) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ old('address', optional($settings->where('key', 'address')->first())->value) }}">
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ old('city', optional($settings->where('key', 'city')->first())->value) }}">
                            </div>

                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code"
                                    value="{{ old('postal_code', optional($settings->where('key', 'postal_code')->first())->value) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo du site</label>
                                <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                @php
                                    $logo = $settings->where('key', 'logo')->first();
                                @endphp
                                @if($logo && $logo->value)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $logo->value) }}"
                                            alt="Logo actuel" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="favicon" class="form-label">Favicon</label>
                                <input type="file" class="form-control" id="favicon" name="favicon" accept="image/*">
                                @php
                                    $favicon = $settings->where('key', 'favicon')->first();
                                @endphp
                                @if($favicon && $favicon->value)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $favicon->value) }}"
                                            alt="Favicon actuel" class="img-thumbnail" style="max-height: 32px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="facebook_url" class="form-label">URL Facebook</label>
                                <input type="url" class="form-control" id="facebook_url" name="facebook_url"
                                    value="{{ old('facebook_url', optional($settings->where('key', 'facebook_url')->first())->value) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="twitter_url" class="form-label">URL Twitter</label>
                                <input type="url" class="form-control" id="twitter_url" name="twitter_url"
                                    value="{{ old('twitter_url', optional($settings->where('key', 'twitter_url')->first())->value) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="instagram_url" class="form-label">URL Instagram</label>
                                <input type="url" class="form-control" id="instagram_url" name="instagram_url"
                                    value="{{ old('instagram_url', optional($settings->where('key', 'instagram_url')->first())->value) }}">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
