@extends('layouts.app')

@section('page_title', 'Settings')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <span class="h2 mb-0 text-dark">Platform Settings</span>
            </div>
            <div class="card-body">
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Settings updated! (Mock Action)');">
                    @csrf
                    
                    <h5 class="fw-bold mb-3" style="font-size: 15px; color: var(--color-primary);">System Preferences</h5>
                    
                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="emailNotif" checked style="accent-color: var(--color-primary);">
                        <label class="form-check-label body-sm text-dark" for="emailNotif">Enable email notifications on new ticket assignments</label>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="slackIntegration" style="accent-color: var(--color-primary);">
                        <label class="form-check-label body-sm text-dark" for="slackIntegration">Send critical alerts to Slack operational channel</label>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3" style="font-size: 15px; color: var(--color-primary);">Active Directory (AD) Config</h5>
                    
                    <div class="mb-3">
                        <label for="adServer" class="form-label label text-secondary">AD SERVER ADDRESS</label>
                        <input type="text" class="form-control" id="adServer" value="ldap://ad.example.corp" readonly>
                    </div>

                    <div class="mb-4">
                        <label for="syncInterval" class="form-label label text-secondary">USER SYNCHRONIZATION INTERVAL</label>
                        <select class="form-select" id="syncInterval" disabled>
                            <option value="hourly">Hourly</option>
                            <option value="daily" selected>Daily (At 02:00 AM)</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Preferences <i class="bi bi-floppy ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
