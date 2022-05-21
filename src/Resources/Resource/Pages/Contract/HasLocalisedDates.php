<?php

namespace Phpsa\FilamentCms\Resources\Resource\Pages\Contract;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Phpsa\FilamentCms\Models\CmsContentPages;

trait HasLocalisedDates
{
    protected static array $dateColumns = [];

    protected function convertLocalDatesToSystemDates(array $data): array
    {
        $userTZ = static::getResource()::getUserTimezone();
        $systemTZ = config('app.timezome');
        if ($userTZ === $systemTZ || blank(static::$dateColumns)) {
            return $data;
        }

        $flat = Arr::dot($data);
        foreach (static::$dateColumns as $dateColum) {
            $flat[$dateColum] = Carbon::parse($flat[$dateColum], $userTZ)
                ->setTimezone($systemTZ)
                ->format(app(CmsContentPages::class)->getDateFormat());
        }

        return Arr::undot($flat);
    }

    protected function convertSystemDatesToLocalDates(array $data): array
    {
        $userTZ = static::getResource()::getUserTimezone();
        $systemTZ = config('app.timezone');

        if ($userTZ !== $systemTZ || blank(static::$dateColumns)) {
            return $data;
        }

        $flat = Arr::dot($data);
        foreach (static::$dateColumns as $dateColum) {
            try {
                $dt = Carbon::parse($flat[$dateColum], $tz);
            } catch (\Throwable) {
                try {
                    $dt = Carbon::createFromFormat(
                        app(CmsContentPages::class)->getDateFormat(),
                        $flat[$dateColum],
                        $systemTZ
                    );
                } catch (\Throwable $e) {
                    $dt = false;
                }
            }

            $flat[$dateColum] = $dt ? $dt->setTimezone($userTZ) : $flat[$dateColum];
        }

        return Arr::undot($flat);
    }
}
