<?php

namespace App\Deployers;

interface DeployerInterface
{

    public function create();
    public function update();
    public function delete();

    public static function links();


}
