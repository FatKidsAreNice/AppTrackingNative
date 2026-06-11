<?php

namespace App\Services\ColdstoreJobs;

class EtikInterfaceLookupRepository
{
    /**
     * @return array{
     *     uid: string,
     *     etikinterface_id: ?int,
     *     etikinterface_pe_text1: ?string
     * }|null
     */
    public function lookupByUid(string $uid): ?array
    {
        return null;
    }
}
