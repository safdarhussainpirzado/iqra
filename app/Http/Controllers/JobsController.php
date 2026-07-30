<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    /**
     * Return a merged list of pending, processing, and failed queue jobs.
     */
    public function index()
    {
        // Pending / processing jobs from the 'jobs' table
        $pending = DB::table('jobs')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($job) {
                $payload = json_decode($job->payload, true);
                return [
                    'id'          => $job->id,
                    'type'        => 'pending',
                    'job_name'    => class_basename($payload['displayName'] ?? 'Unknown Job'),
                    'attempts'    => $job->attempts,
                    'reserved_at' => $job->reserved_at,
                    'created_at'  => date('Y-m-d H:i:s', $job->created_at),
                    'error'       => null,
                    'status'      => $job->reserved_at ? 'processing' : 'pending',
                ];
            });

        // Failed jobs from the 'failed_jobs' table
        $failed = DB::table('failed_jobs')
            ->orderByDesc('failed_at')
            ->get()
            ->map(function ($job) {
                $payload = json_decode($job->payload, true);
                // Extract only the first line of the exception for display
                $exceptionLines = explode("\n", $job->exception ?? '');
                return [
                    'id'          => $job->id,
                    'type'        => 'failed',
                    'job_name'    => class_basename($payload['displayName'] ?? 'Unknown Job'),
                    'attempts'    => null,
                    'reserved_at' => null,
                    'created_at'  => $job->failed_at,
                    'error'       => trim($exceptionLines[0] ?? 'Unknown error'),
                    'status'      => 'failed',
                ];
            });

        return response()->json([
            'pending' => $pending->values(),
            'failed'  => $failed->values(),
            'counts'  => [
                'pending'    => $pending->where('status', 'pending')->count(),
                'processing' => $pending->where('status', 'processing')->count(),
                'failed'     => $failed->count(),
            ],
        ]);
    }

    /**
     * Delete a failed job record.
     */
    public function destroyFailed($id)
    {
        DB::table('failed_jobs')->where('id', $id)->delete();
        return response()->json(['message' => 'Failed job deleted.']);
    }

    /**
     * Retry a failed job by moving it back to the jobs queue.
     */
    public function retryFailed(Request $request, $id)
    {
        $failedJob = DB::table('failed_jobs')->where('id', $id)->first();
        if (!$failedJob) {
            return response()->json(['message' => 'Failed job not found.'], 404);
        }

        $payload = json_decode($failedJob->payload, true);

        DB::table('jobs')->insert([
            'queue'      => $failedJob->queue,
            'payload'    => $failedJob->payload,
            'attempts'   => 0,
            'reserved_at'=> null,
            'available_at'=> time(),
            'created_at' => time(),
        ]);

        DB::table('failed_jobs')->where('id', $id)->delete();

        return response()->json(['message' => 'Job re-queued successfully.']);
    }
}
