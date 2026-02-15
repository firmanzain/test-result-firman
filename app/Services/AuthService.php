<?php

namespace App\Services;

use App\DTOs\AuthCredentialDto;
use App\DTOs\AuthDto;
use App\DTOs\MachineLogDto;
use Illuminate\Support\Facades\Hash;
use App\Enums\SystemAbility;
use App\Models\User;

class AuthService
{
    public static function authenticateUseMachine(AuthCredentialDto $dto): AuthDto
    {
        $user = $dto->user;
        $machineCode = $dto->machineCode;

        $userShift = $user->userShifts()
            ->with('shift')
            ->where('machine_code', $machineCode)
            ->whereDate('shift_date', now()->format('Y-m-d'))
            ->first();

        if (!$userShift || !$userShift->shift) {
            $timeNow = now()->format('d-m-Y H:i:s');
            $auth = AuthDto::failure("login gagal pada {$timeNow}, karena tidak memiliki shift.");
        } else if ($userShift && $userShift->shift && ($userShift->shift->start_time > now()->format('H:i:s') || $userShift->shift->end_time < now()->format('H:i:s'))) {
            $timeNow = now()->format('d-m-Y H:i:s');
            $auth = AuthDto::failure("login gagal pada {$timeNow}, di luar jam kerja shift. Shift mulai pukul {$userShift->shift->start_time} sampai {$userShift->shift->end_time}.");
        } else {
            $auth = AuthDto::success($user->createToken('auth_token', [\App\Enums\SystemAbility::MACHINE->value])->plainTextToken);
        }

        dispatch(fn() => MachineLogService::addLog(MachineLogDto::fromAuth($dto, $auth)))->name('log_machine_auth_' . $user->employee_number . '_' . now()->format('YmdHis'));

        return $auth;
    }

    public static function authenticateUsePassword(AuthCredentialDto $dto): AuthDto
    {
        $user = $dto->user;
        $password = $dto->password;

        if (!$password || !Hash::check($password, $user->password)) {
            return AuthDto::failure('Employee number atau password salah');
        }

        return AuthDto::success(
            $user->createToken(
                'auth_token',
                [SystemAbility::BACKOFFICE->value]
            )->plainTextToken
        );
    }

    public static function logout(User $user): void
    {
        $token = $user->currentAccessToken();
        $token?->delete();
    }
}
