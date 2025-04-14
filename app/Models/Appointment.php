<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'duration_minutes',
        'meeting_type',
        'meeting_link',
        'status',
        'description',
        'rejection_reason',
        'cancellation_reason',
        'is_rescheduled',
        'rescheduled_from_id',
        'suggested_date',
        'suggestion_note'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'suggested_date' => 'datetime',
        'is_rescheduled' => 'boolean'
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DATE_SUGGESTED = 'date_suggested';

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function rescheduledFrom()
    {
        return $this->belongsTo(Appointment::class, 'rescheduled_from_id');
    }

    public function rescheduledTo()
    {
        return $this->hasOne(Appointment::class, 'rescheduled_from_id');
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isVirtual()
    {
        return $this->meeting_type === 'virtual';
    }
}
