<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Events\PaymentStatusUpdated;

class PaymentController extends Controller
{
    public function getPendingPayments()
    {
        try {
            $payments = Payment::with(['claim.employee'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'payments' => $payments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pending payments'
            ], 500);
        }
    }

    public function getPaymentStats()
    {
        try {
            $today = now()->startOfDay();
            $monthStart = now()->startOfMonth();

            $stats = [
                'pending_count' => Payment::where('status', 'pending')->count(),
                'processed_today' => Payment::where('status', 'processed')
                    ->whereDate('processed_at', $today)
                    ->count(),
                'total_amount_today' => Payment::where('status', 'processed')
                    ->whereDate('processed_at', $today)
                    ->sum('amount'),
                'monthly_total' => Payment::where('status', 'processed')
                    ->whereDate('processed_at', '>=', $monthStart)
                    ->sum('amount')
            ];

            return response()->json([
                'status' => 'success',
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve payment statistics'
            ], 500);
        }
    }

    public function getPayment($id)
    {
        try {
            $payment = Payment::with(['claim.employee'])
                ->findOrFail($id);

            return response()->json([
                'status' => 'success',
                'payment' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }
    }

    public function processPayment(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_method' => 'required|in:bank_transfer,cash,check',
                'reference_number' => 'required|string|max:255',
                'payment_date' => 'required|date',
                'notes' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $payment = Payment::findOrFail($id);

            if ($payment->status !== 'pending') {
                throw new \Exception('Payment is not in pending status');
            }

            $payment->update([
                'status' => 'processed',
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'payment_date' => $request->payment_date,
                'notes' => $request->notes,
                'processed_at' => now()
            ]);

            // Update claim status
            $payment->claim->update([
                'status' => 'paid'
            ]);

            DB::commit();

            // Broadcast payment status update
            broadcast(new PaymentStatusUpdated($payment))->toOthers();

            return response()->json([
                'status' => 'success',
                'message' => 'Payment processed successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
