<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Enable/disable backward compatibility breaking features.
 */
class Feature extends BaseConfig
{
    /**
     * Use improved new auto routing instead of the legacy version.
     */
    public bool $autoRoutesImproved = true;

    /**
     * Use filter execution order in 4.4 or before.
     */
    public bool $oldFilterOrder = false;

    /**
     * The behavior of `limit(0)` in Query Builder.
     */
    public bool $limitZeroAsAll = true;

    /**
     * Use strict locale negotiation.
     */
    public bool $strictLocaleNegotiation = false;
}
