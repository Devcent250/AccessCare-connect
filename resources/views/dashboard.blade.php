@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        @if(auth()->user()->isDoctor())
                        Doctor Dashboard
                        @else
                        Patient Dashboard
                        @endif
                    </h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h3>Welcome, {{ auth()->user()->isDoctor() ? 'Dr. ' : '' }}{{ auth()->user()->name }}</h3>

                    @if(auth()->user()->isDoctor())
                    <!-- Doctor's View -->
                    <div class="mb-4">
                        <h4>Your Appointments</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name of Patient</th>
                                        <th>Date & Time</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->patient->name }}</td>
                                        <td>{{ $appointment->appointment_date }}</td>
                                        <td>{{ $appointment->description }}</td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status === 'approved' ? 'success' : ($appointment->status === 'rejected' ? 'danger' : ($appointment->status === 'date_suggested' ? 'info' : 'warning')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                            </span>
                                            @if($appointment->status === 'date_suggested')
                                            <br>
                                            <small class="text-muted">Suggested: {{ $appointment->suggested_date }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($appointment->status === 'pending')
                                            <form action="{{ route('appointments.approve', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <form action="{{ route('appointments.reject', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#suggestDateModal{{ $appointment->id }}">
                                                Suggest Date
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No appointments yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Suggest Date Modals -->
                    @foreach($appointments as $appointment)
                    @if($appointment->status === 'pending')
                    <div class="modal fade" id="suggestDateModal{{ $appointment->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Suggest Alternative Date</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('appointments.suggest-date', $appointment) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="suggested_date" class="form-label">Suggested Date & Time</label>
                                            <input type="datetime-local" class="form-control @error('suggested_date') is-invalid @enderror"
                                                id="suggested_date" name="suggested_date" required>
                                            @error('suggested_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="suggestion_note" class="form-label">Note to Patient</label>
                                            <textarea class="form-control @error('suggestion_note') is-invalid @enderror"
                                                id="suggestion_note" name="suggestion_note" rows="3" required
                                                placeholder="Explain why you're suggesting this alternative date..."></textarea>
                                            @error('suggestion_note')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Send Suggestion</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @else
                    <!-- Patient's View -->
                    <div class="mb-4">
                        <h4>Available Doctors</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Doctor Name</th>
                                        <th>Specialization</th>
                                        <th>Contact</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($doctors as $doctor)
                                    <tr>
                                        <td>Dr. {{ $doctor->name }}</td>
                                        <td>{{ $doctor->doctorProfile->specialization ?? 'Not specified' }}</td>
                                        <td>{{ $doctor->doctorProfile->contact_number ?? 'Not specified' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#appointmentModal{{ $doctor->id }}">
                                                Request Appointment
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No doctors available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4>Your Appointments</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Doctor Name</th>
                                        <th>Date & Time</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($appointments as $appointment)
                                    <tr>
                                        <td>Dr. {{ $appointment->doctor->name }}</td>
                                        <td>
                                            {{ $appointment->appointment_date }}
                                            @if($appointment->status === 'date_suggested')
                                            <br>
                                            <small class="text-muted">Suggested: {{ $appointment->suggested_date }}</small>
                                            <br>
                                            <small class="text-muted">Note: {{ $appointment->suggestion_note }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status === 'approved' ? 'success' : ($appointment->status === 'rejected' ? 'danger' : ($appointment->status === 'date_suggested' ? 'info' : 'warning')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($appointment->status === 'date_suggested')
                                            <form action="{{ route('appointments.accept-suggested-date', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Accept Date</button>
                                            </form>
                                            <form action="{{ route('appointments.reject-suggested-date', $appointment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Decline</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No appointments yet</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Appointment Request Modals -->
                    @foreach($doctors as $doctor)
                    <div class="modal fade" id="appointmentModal{{ $doctor->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Request Appointment with Dr. {{ $doctor->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('appointments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                    <div class="modal-body">
                                        <div class="alert alert-info">
                                            <small>All appointments are scheduled for 30 minutes by default.</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="appointment_date" class="form-label">Appointment Date & Time</label>
                                            <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror"
                                                id="appointment_date" name="appointment_date" required
                                                value="{{ old('appointment_date') }}">
                                            @error('appointment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="meeting_type" class="form-label">Meeting Type</label>
                                            <select class="form-control @error('meeting_type') is-invalid @enderror"
                                                id="meeting_type" name="meeting_type" required>
                                                <option value="">Select Type</option>
                                                <option value="in-person">In Person</option>
                                                <option value="virtual">Virtual</option>
                                            </select>
                                            @error('meeting_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3" id="meeting_link_container" style="display: none;">
                                            <label for="meeting_link" class="form-label">Meeting Link</label>
                                            <input type="url" class="form-control @error('meeting_link') is-invalid @enderror"
                                                id="meeting_link" name="meeting_link"
                                                placeholder="https://meet.example.com/..."
                                                value="{{ old('meeting_link') }}">
                                            @error('meeting_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                            @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Request Appointment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide meeting link field based on meeting type
        const meetingTypeSelect = document.querySelectorAll('#meeting_type');
        meetingTypeSelect.forEach(select => {
            select.addEventListener('change', function() {
                const meetingLinkContainer = this.closest('.modal-body').querySelector('#meeting_link_container');
                const meetingLinkInput = meetingLinkContainer.querySelector('#meeting_link');

                if (this.value === 'virtual') {
                    meetingLinkContainer.style.display = 'block';
                    meetingLinkInput.required = true;
                } else {
                    meetingLinkContainer.style.display = 'none';
                    meetingLinkInput.required = false;
                    meetingLinkInput.value = '';
                }
            });
        });
    });
</script>
@endpush