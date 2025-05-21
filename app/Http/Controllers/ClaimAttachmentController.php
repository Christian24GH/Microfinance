<?php

namespace App\Http\Controllers;

use App\Models\ClaimAttachment;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClaimAttachmentController extends Controller
{
    public function index($claimId)
    {
        $attachments = ClaimAttachment::where('claim_id', $claimId)->get();
        return response()->json(['status' => 'success', 'attachments' => $attachments]);
    }

    public function store(Request $request, $claimId)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }
        $claim = Claim::findOrFail($claimId);
        $file = $request->file('file');
        $path = $file->store('claim_attachments');
        $attachment = ClaimAttachment::create([
            'claim_id' => $claim->id,
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'uploaded_by' => Auth::id(),
            'uploaded_at' => Carbon::now(),
        ]);
        return response()->json(['status' => 'success', 'attachment' => $attachment]);
    }

    public function destroy($id)
    {
        $attachment = ClaimAttachment::findOrFail($id);
        Storage::delete($attachment->file_path);
        $attachment->delete();
        return response()->json(['status' => 'success']);
    }
}
