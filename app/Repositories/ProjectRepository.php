<?php

namespace App\Repositories;

use App\Models\Project;

class ProjectRepository extends BasicRepository
{
    public function model(): string
    {
        return Project::class;
    }
}
