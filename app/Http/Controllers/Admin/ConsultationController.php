<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Consultation::latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->input('filter') === 'unread') {
            $query->unread();
        }

        $consultations = $query->paginate(25)->withQueryString();
        $unreadCount   = Consultation::unread()->count();

        return view('general.consultations.index', compact('consultations', 'unreadCount'));
    }

    public function show(Consultation $consultation): View
    {
        if (! $consultation->is_read) {
            $consultation->update(['read_at' => now()]);
        }

        return view('general.consultations.show', compact('consultation'));
    }

    public function destroy(Consultation $consultation): RedirectResponse
    {
        $consultation->delete();

        return redirect()->route('admin.consultations.index')
            ->with('success', "Enquiry from {$consultation->name} deleted.");
    }
}
