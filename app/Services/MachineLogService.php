<?php

namespace App\Services;

use App\DTOs\MachineLogDto;
use App\Models\MachineLog;
use App\Models\User;
use Illuminate\Support\Str;

class MachineLogService
{
    public static function addLog(MachineLogDto $dto): void
    {
        MachineLog::create([
            'ulid' => (string) Str::ulid(),
            'user_id' => $dto->user->id,
            'machine_code' => $dto->machineCode,
            'event' => $dto->event->value,
            'log_message' => $dto->logMessage,
        ]);
    }
}
