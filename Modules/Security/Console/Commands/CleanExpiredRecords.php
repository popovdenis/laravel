<?php
declare(strict_types=1);

namespace Modules\Security\Console\Commands;

use Illuminate\Console\Command;
use Modules\Security\Models\SecurityManager;

/**
 * Class CleanExpiredRecords
 *
 * @package Modules\Security\Console\Commands
 */
class CleanExpiredRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:clean_expired_records'; //<schedule>0 0 * * *</schedule>

    /**
     * @var \Modules\Security\Models\SecurityManager
     */
    private SecurityManager $securityManager;

    public function __construct(SecurityManager $securityManager)
    {
        parent::__construct();
        $this->securityManager = $securityManager;
    }

    public function handle()
    {
        try {
            $this->securityManager->cleanExpiredRecords();
        } catch (\Exception $e) {
        }
    }
}
