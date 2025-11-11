@extends('admin.layouts.app')

@section('title', __('stripe::admin.settings.title'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('stripe::admin.settings.title') }}</h3>
                </div>

                <form action="{{ route('admin.stripe.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="key">{{ __('stripe::admin.settings.fields.key') }}</label>
                            <input type="text" name="key" id="key" class="form-control @error('key') is-invalid @enderror"
                                   value="{{ old('key', $config['key']) }}" required>
                            @error('key')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="secret">{{ __('stripe::admin.settings.fields.secret') }}</label>
                            <input type="password" name="secret" id="secret" class="form-control @error('secret') is-invalid @enderror"
                                   value="{{ old('secret', $config['secret']) }}" required>
                            @error('secret')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="webhook_secret">{{ __('stripe::admin.settings.fields.webhook_secret') }}</label>
                            <input type="password" name="webhook_secret" id="webhook_secret" class="form-control @error('webhook_secret') is-invalid @enderror"
                                   value="{{ old('webhook_secret', $config['webhook_secret']) }}" required>
                            @error('webhook_secret')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="currency">{{ __('stripe::admin.settings.fields.currency') }}</label>
                            <input type="text" name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror"
                                   value="{{ old('currency', $config['currency']) }}" required maxlength="3">
                            @error('currency')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">{{ __('stripe::admin.settings.currency_help') }}</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="sandbox" class="custom-control-input" id="sandbox"
                                       value="1" {{ old('sandbox', $config['sandbox']) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="sandbox">
                                    {{ __('stripe::admin.settings.fields.sandbox') }}
                                </label>
                            </div>
                            <small class="form-text text-muted">{{ __('stripe::admin.settings.sandbox_help') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="statement_descriptor">{{ __('stripe::admin.settings.fields.statement_descriptor') }}</label>
                            <input type="text" name="statement_descriptor" id="statement_descriptor" 
                                   class="form-control @error('statement_descriptor') is-invalid @enderror"
                                   value="{{ old('statement_descriptor', $config['statement_descriptor']) }}" 
                                   required maxlength="22">
                            @error('statement_descriptor')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">{{ __('stripe::admin.settings.statement_descriptor_help') }}</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="automatic_tax" class="custom-control-input" id="automatic_tax"
                                       value="1" {{ old('automatic_tax', $config['automatic_tax']) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="automatic_tax">
                                    {{ __('stripe::admin.settings.fields.automatic_tax') }}
                                </label>
                            </div>
                            <small class="form-text text-muted">{{ __('stripe::admin.settings.automatic_tax_help') }}</small>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            {{ __('stripe::admin.settings.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 