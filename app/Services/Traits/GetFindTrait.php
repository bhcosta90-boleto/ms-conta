<?php

namespace App\Services\Traits;


trait GetFindTrait
{
    private static $dataGetFindTrait;

    public function findByUuid($uuid, $field = null)
    {
        if (empty($uuid)) {
            return null;
        }

        if ($uuid && empty(self::$dataGetFindTrait[$uuid])) {
            self::$dataGetFindTrait[$uuid] = $this->repository->where('uuid', $uuid)->first();
        }

        if (!empty(self::$dataGetFindTrait[$uuid]) && !empty($field)) {
            return self::$dataGetFindTrait[$uuid]->$field;
        }

        return self::$dataGetFindTrait[$uuid] ?? null;
    }
}
