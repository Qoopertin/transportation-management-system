<?php

namespace App\Services;

use App\Enums\LoadStatus;
use App\Models\Load;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LoadService
{
    public function createLoad(array $data): Load
    {
        // Generate unique reference number
        $data['reference_no'] = $data['reference_no'] ?? $this->generateReferenceNumber();
        
        return Load::create($data);
    }

    public function updateLoad(Load $load, array $data): Load
    {
        $load->update($data);
        return $load->fresh();
    }

    public function assignDriver(Load $load, ?int $driverId): Load
    {
        $load->update([
            'assigned_driver_id' => $driverId,
            'status' => $driverId ? LoadStatus::ASSIGNED : LoadStatus::PENDING,
        ]);

        return $load->fresh();
    }

    public function updateStatus(Load $load, LoadStatus $status): Load
    {
        $load->update(['status' => $status]);
        \Log::info('Load status updated', [
            'load_id' => $load->id,
            'reference_no' => $load->reference_no,
            'status' => $status->value,
        ]);

        return $load->fresh();
    }

    public function getActiveLoads(): Collection
    {
        return Load::active()
            ->with(['driver'])
            ->orderBy('pickup_at')
            ->get();
    }

    public function getPaginatedLoads(int $perPage = 15): LengthAwarePaginator
    {
        return Load::with(['driver'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getDriverLoads(int $driverId): Collection
    {
        return Load::forDriver($driverId)
            ->active()
            ->with(['documents'])
            ->orderBy('pickup_at')
            ->get();
    }

    public function getLoadWithDetails(int $loadId): Load
    {
        return Load::with(['driver', 'documents.uploader', 'breadcrumbs'])
            ->findOrFail($loadId);
    }

    private function generateReferenceNumber(): string
    {
        $prefix = 'LD';
        $timestamp = now()->format('Ymd');
        $random = strtoupper(substr(md5(microtime()), 0, 4));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }
}
