<?php

namespace App\Repositories\Branch;

interface BranchInterface {
    public function parents();
    public function childs($child);
    public function findOne($id);
}