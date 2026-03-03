@extends('layouts.admin')
@include('partials/admin.nav', ['route' => 'admin.theme'])

@section('title', 'Premium Theme')
@section('content-header', 'Premium Theme Settings')
@section('content-subheader', 'Configure Pterodactyl to your liking. By volexion95-tech')

@section('content')
<div class="row">
  <div class="col-xs-12">

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.theme.update') }}" method="POST">
      @csrf
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">🎨 Theme Settings</h3>
        </div>
        <div class="box-body">

          {{-- LINKS --}}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Logo</label>
                <input type="text" name="logo_path" class="form-control" value="{{ $settings['logo_path'] }}">
                <p class="text-muted small">The logo path for the theme.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Discord Link</label>
                <input type="text" name="discord_link" class="form-control" value="{{ $settings['discord_link'] }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Billing Link</label>
                <input type="text" name="billing_link" class="form-control" value="{{ $settings['billing_link'] }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Support Link</label>
                <input type="text" name="support_link" class="form-control" value="{{ $settings['support_link'] }}">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status Link</label>
                <input type="text" name="status_link" class="form-control" value="{{ $settings['status_link'] }}">
              </div>
            </div>
          </div>

          <hr>

          {{-- COLORS --}}
          <h4>🎨 Colors</h4>
          <p class="text-muted small">After saving, run <code>cd /var/www/pterodactyl && yarn build:production</code> to apply color changes.</p>
          <div class="row" style="margin-top:12px">
            <div class="col-md-4">
              <div class="form-group">
                <label>Primary Color</label>
                <div style="display:flex;gap:8px;align-items:center">
                  <input type="color" name="primary" value="{{ $settings['primary'] }}" style="height:38px;width:60px;padding:2px;border-radius:4px;border:1px solid #555;background:#222;cursor:pointer">
                  <input type="text" id="txt-primary" value="{{ $settings['primary'] }}" class="form-control" style="font-family:monospace" oninput="document.querySelector('[name=primary]').value=this.value">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Primary Hover</label>
                <div style="display:flex;gap:8px;align-items:center">
                  <input type="color" name="primary_hover" value="{{ $settings['primary_hover'] }}" style="height:38px;width:60px;padding:2px;border-radius:4px;border:1px solid #555;background:#222;cursor:pointer">
                  <input type="text" value="{{ $settings['primary_hover'] }}" class="form-control" style="font-family:monospace" oninput="document.querySelector('[name=primary_hover]').value=this.value">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Background</label>
                <div style="display:flex;gap:8px;align-items:center">
                  <input type="color" name="bg" value="{{ $settings['bg'] }}" style="height:38px;width:60px;padding:2px;border-radius:4px;border:1px solid #555;background:#222;cursor:pointer">
                  <input type="text" value="{{ $settings['bg'] }}" class="form-control" style="font-family:monospace" oninput="document.querySelector('[name=bg]').value=this.value">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Secondary Background</label>
                <div style="display:flex;gap:8px;align-items:center">
                  <input type="color" name="bg2" value="{{ $settings['bg2'] }}" style="height:38px;width:60px;padding:2px;border-radius:4px;border:1px solid #555;background:#222;cursor:pointer">
                  <input type="text" value="{{ $settings['bg2'] }}" class="form-control" style="font-family:monospace" oninput="document.querySelector('[name=bg2]').value=this.value">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Card Color</label>
                <div style="display:flex;gap:8px;align-items:center">
                  <input type="color" name="card" value="{{ $settings['card'] }}" style="height:38px;width:60px;padding:2px;border-radius:4px;border:1px solid #555;background:#222;cursor:pointer">
                  <input type="text" value="{{ $settings['card'] }}" class="form-control" style="font-family:monospace" oninput="document.querySelector('[name=card]').value=this.value">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Border Color</label>
                <div style="display:flex;gap:8px;align-items:center">
                  <input type="color" name="border" value="{{ $settings['border'] }}" style="height:38px;width:60px;padding:2px;border-radius:4px;border:1px solid #555;background:#222;cursor:pointer">
                  <input type="text" value="{{ $settings['border'] }}" class="form-control" style="font-family:monospace" oninput="document.querySelector('[name=border]').value=this.value">
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-primary btn-sm pull-right">
            💾 Save Settings
          </button>
        </div>
      </div>
    </form>

  </div>
</div>
@endsection
