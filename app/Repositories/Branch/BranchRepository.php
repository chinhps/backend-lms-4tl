<?php

namespace App\Repositories\Branch;

use App\Models\FolderTree;

class BranchRepository implements BranchInterface
{
    public function model()
    {
        return FolderTree::class;
    }
    public function findOne($id)
    {
        return $this->model()::find($id);
    }
    public function parents()
    {
        return $this->model()::where('parent_tree_id', 0)->get();
    }
    public function childs($child)
    {
        return $this->model()::where('parent_tree_id', $child)->get();
    }
}
