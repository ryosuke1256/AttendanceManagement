<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class AttendanceRecordService
{
    // TODO Refactor
    private const ATTENDANCE_STATUS = [
        '未登録' => 0,
        '始業済み' => 1,
        '終業済み' => 2,
        '不正登録' => 3,
    ];

    final public function getAttendanceStatus(User $user, Carbon $date): int
    {
        $startedRecordCounts = $this->getTodayStartedRecordCounts($user, $date);
        $endedRecordCounts = $this->getTodayEndedRecordCounts($user, $date);

        $attendanceStatus = $this->assignAttendanceStatus($startedRecordCounts, $endedRecordCounts);
        return $attendanceStatus;
    }

    private function assignAttendanceStatus(int $startedRecordCounts, int $endedRecordCounts): int
    {
        try {
            if ($startedRecordCounts == 0 && $endedRecordCounts == 0) {
                return self::ATTENDANCE_STATUS['未登録'];
            } elseif ($startedRecordCounts >= 1 && $endedRecordCounts == 0) {
                return self::ATTENDANCE_STATUS['始業済み'];
            } elseif ($startedRecordCounts == 0 && $endedRecordCounts >= 1) {
                throw new \RecordException('未始業かつ終業済み登録');
            } elseif ($startedRecordCounts >= 1 && $endedRecordCounts >= 1) {
                return self::ATTENDANCE_STATUS['終業済み'];
            }
        } catch (\RecordException $e) {
            \Log::error('ABNORMAL_RECORD : ', $e->getMessage());
            return self::ATTENDANCE_STATUS['不正登録'];
        }
    }

    private function getTodayStartedRecordCounts(User $user, Carbon $date): int
    {
        $todayDateString = \DateConverter::getTodayString($date, 'YYYY-MM-DD');
        $todaysDateRegex = '%' . $todayDateString . '%';

        $startedRecordCounts = $user
            ->attendanceRecords()
            ->where('start_time', 'like', $todaysDateRegex)
            ->count();

        try {
            if ($startedRecordCounts > 1) {
                throw new \RecordException('today started record 1 than many');
            }
        } catch (\RecordException $e) {
            \Log::error('STARTED_RECORD  : ', $e->getMessage());
            return $startedRecordCounts;
        }

        return $startedRecordCounts;
    }

    private function getTodayEndedRecordCounts(User $user, Carbon $date): int
    {
        $todayDateString = \DateConverter::getTodayString($date, 'YYYY-MM-DD');
        $todaysDateRegex = '%' . $todayDateString . '%';

        $endedRecordCounts = $user
            ->attendanceRecords()
            ->where('end_time', 'like', $todaysDateRegex)
            ->count();

        try {
            if ($endedRecordCounts > 1) {
                throw new \RecordException('today ended record 1 than many');
            }
        } catch (\RecordException $e) {
            \Log::error('ENDED_RECORD : ', $e->getMessage());
            return $endedRecordCounts;
        }

        return $endedRecordCounts;
    }
}