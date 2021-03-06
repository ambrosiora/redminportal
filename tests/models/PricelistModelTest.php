<?php namespace Redooor\Redminportal\Test;

use Redooor\Redminportal\App\Models\Pricelist;

class PricelistModelTest extends BaseModelTest
{
    /**
     * Contructor
     */
    public function __construct()
    {
        $model = new Pricelist;
        $testcase = array(
            'price' => 0,
            'module_id' => 1,
            'membership_id' => 1,
            'active' => true
        );
        
        parent::__construct($model, $testcase);
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        parent::__destruct();
    }
}
