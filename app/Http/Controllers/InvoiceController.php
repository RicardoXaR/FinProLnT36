<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::where('user_id', Auth::id())->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        // Check if user owns this invoice or is admin
        if (Auth::user()->role !== 'admin' && $invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->load('invoiceItems.good.category');
        return view('invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        // Check if user owns this invoice or is admin
        if (Auth::user()->role !== 'admin' && $invoice->user_id !== Auth::id()) {
            abort(403);
        }

        $invoice->load('invoiceItems.good.category', 'user');
        return view('invoices.print', compact('invoice'));
    }
}